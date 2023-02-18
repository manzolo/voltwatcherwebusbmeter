<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Settings;
use App\Entity\Log;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotations;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use DateTimeZone;
use Exception;

/**
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ApiController extends AbstractFOSRestController
{

    private const WEATHER_API_BASE_URL = 'https://api.openweathermap.org/data/2.5/weather';
    private const ABSOLUTE_ZERO_CELSIUS = -273.15;

    private MailerInterface $mailer;
    private ParameterBagInterface $params;
    private HttpClientInterface $client;
    private EntityManagerInterface $em;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params, HttpClientInterface $client, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->params = $params;
        $this->client = $client;
        $this->em = $em;
    }
    /**
     * @RestAnnotations\Post("api/volt/record.json")
     * @ParamConverter("datavolt", class="array", converter="fos_rest.request_body")
     * @param array<string> $datavolt
     */
    public function postVoltRecordAction(array $datavolt): View
    {
        return $this->putVoltRecordAction($datavolt);
    }
    /**
     * @RestAnnotations\Put("api/volt/record.json")
     * @ParamConverter("datavolt", class="array", converter="fos_rest.request_body")
     * @param array<string> $datavolt
     */
    public function putVoltRecordAction(array $datavolt): View
    {
//        if (0 !== strpos($request->headers->get('Content-Type'), self::CONTENT_TYPE_JSON)) {
//            return View::create(['message' => 'Invalid content type'], Response::HTTP_BAD_REQUEST);
//        }
        try {
            $device = $datavolt['device'];
            $data = $this->getDatetime($datavolt['data']);
            $volt = (float) $datavolt['volt'];
            $temp = (float) $datavolt['temp'];
            $batteryperc = (float) $datavolt['batteryperc'];
            $longitude = floatval($datavolt['longitude']);
            $latitude = floatval($datavolt['latitude']);

            $deviceEntity = $this->getOrCreateDevice($device);

            $logEntity = new Log();
            $logEntity->setDevice($deviceEntity)
                    ->setData($data)
                    ->setVolt($volt)
                    ->setTemp($temp)
                    ->setDetectorperc($batteryperc)
                    ->setLongitude($longitude)
                    ->setLatitude($latitude);

            $this->em->persist($logEntity);
            $this->em->flush();

            $emailStatus = $this->sendWarningEmailIfNecessary($logEntity);
            $weatherStatus = $this->fetchWeatherDataAndPersist($logEntity);
        } catch (Exception $exc) {
            return $this->view(['errcode' => -100, 'errmsg' => $exc->getMessage()], Response::HTTP_OK);
        }

        return $this->view(['errcode' => 0, 'email' => $emailStatus, 'weather' => $weatherStatus, 'errmsg' => 'OK'], Response::HTTP_OK);
    }
    /**
     * Retrieves the Device entity with the given address, or creates a new one if it doesn't exist.
     *
     * @param string $address The address of the device to retrieve or create.
     *
     * @return Device The Device entity.
     */
    private function getOrCreateDevice(string $address): Device
    {
        $deviceRepository = $this->getDoctrine()->getRepository(Device::class);

        $device = $deviceRepository->findOneBy(['address' => $address]);

        if (!$device) {
            $device = new Device();
            $device->setAddress($address);
            $this->em->persist($device);
            $this->em->flush();
        }

        return $device;
    }
    /**
     * @return array{errcode: int, errmsg: string} Array con errcode intero e errmsg stringa
     */
    private function sendWarningEmailIfNecessary(Log $logEntity): array
    {
        $threshold = $logEntity->getDevice()->getThreshold();
        $recipient = $this->params->get('mailer_user');
        $devicename = $this->getDeviceName($logEntity);

        if (!$threshold || !$recipient || $logEntity->getVolt() >= $threshold) {
            return ['errcode' => 0, 'errmsg' => 'Nessun avviso da inviare'];
        }

        if (!is_string($recipient)) {
            throw new Exception("Email non valida");
        }

        $email = (new Email())
                ->from($recipient)
                ->to($recipient)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('WARNING from ' . $devicename . ' *** ' . $logEntity->getVolt() . ' volt ***')
                ->text('WARNING from ' . $devicename .
                        '! Received ' . $logEntity->getVolt() .
                        ' (less of ' . $threshold . ' threshold) at ' . $logEntity->getData()->format('d/m/Y H:i:s'))
                ->html('WARNING from ' . $devicename . '! Received ' . $logEntity->getVolt() .
                ' (less of ' . $threshold . ' threshold) at ' . $logEntity->getData()->format('d/m/Y H:i:s'));

        try {
            $this->mailer->send($email);
        } catch (Exception $exc) {
            return ['errcode' => -1, 'errmsg' => $exc->getMessage()];
        }
        return ['errcode' => 0, 'errmsg' => 'mail inviata'];
    }
    
    /**
     * @return array{errcode: int, errmsg: string} Array con errcode intero e errmsg stringa
     */
    private function fetchWeatherDataAndPersist(Log $logEntity): array
    {
        $owmappid = $this->params->get('openweathermap_apikey');
        if (!is_string($owmappid)) {
            return ['errcode' => -1, 'errmsg' => 'Openweathermap api key non valida'];
        }
        if (!$owmappid || (!$logEntity->getLongitude() && !$logEntity->getLatitude())) {
            return ['errcode' => -1, 'errmsg' => 'Impossibile trovare le coordinate geografiche'];
        }

        $owmurl = self::WEATHER_API_BASE_URL . '?lon=' . $logEntity->getLongitude() . '&lat=' . $logEntity->getLatitude() . '&APPID=' . $owmappid;
        $response = $this->client->request('GET', $owmurl);

        $weatherjson = json_decode($response->getContent(), true);
        if (!isset($weatherjson['weather'][0]['main']) ||
                !isset($weatherjson['main']['temp']) ||
                !isset($weatherjson['clouds']['all']) ||
                !isset($weatherjson['name']) ||
                !isset($weatherjson['weather'][0]['icon'])) {
            return ['errcode' => -2, 'errmsg' => 'Dati meteo non validi.'];
        }
        $weather = $weatherjson['weather'][0]['main'];
        $externaltemp = $weatherjson['main']['temp'] - self::ABSOLUTE_ZERO_CELSIUS;
        $cloudiness = $weatherjson['clouds']['all'];
        $location = $weatherjson['name'];
        $weathericon = $weatherjson['weather'][0]['icon'];

        $logEntity->setWeather($weather);
        $logEntity->setExternaltemp($externaltemp);
        $logEntity->setCloudiness($cloudiness);
        $logEntity->setLocation($location);
        $logEntity->setWeathericon($weathericon);
        $this->em->persist($logEntity);
        $this->em->flush();

        return ['errcode' => 0, 'errmsg' => 'Location: ' . $location];
    }
    private function getDatetime(?string $dataRaw): DateTime
    {
        $timezone = new DateTimeZone('Europe/Rome');

        if (!$dataRaw) {
            return new DateTime('now', $timezone);
        }

        $data = null;

        if (18 === strlen($dataRaw)) {
            $data = DateTime::createFromFormat('YmdHis.000', $dataRaw, new DateTimeZone('UTC'));
        } else {
            $data = DateTime::createFromFormat('Y-m-d H:i:s', $dataRaw);
        }

        if (!($data instanceof DateTime)) {
            throw new Exception("Data non valida");
        }

        $data->setTimeZone($timezone);

        return $data;
    }
    /**
     * @RestAnnotations\Get("api/get/settings/app.json")
     */
    public function appGetSettingsAction(): View
    {
        $settings = $this->em->getRepository(Settings::class)->findAll();
        $newsettings = array_column($settings, 'value', 'key');
        return $this->view($newsettings);
    }
    /**
     * @RestAnnotations\Get("api/get/server/datetime.json")
     */
    public function appServerDatetimeAction(): View
    {
        $now = new DateTime();
        return $this->view([
                    'datetime' => $now->format('Y-m-d H:i:s'),
                    'date' => $now->format('Y-m-d'),
                    'time' => $now->format('H:i:s')
        ]);
    }
    private function getDeviceName(Log $newlog): string
    {
        $device = $newlog->getDevice();
        return $device->getName() ?: $device->getAddress();
    }
}
