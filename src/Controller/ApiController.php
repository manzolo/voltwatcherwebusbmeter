<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Log;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as RestAnnotations;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class ApiController extends AbstractFOSRestController
{

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
     * @RestAnnotations\Put("api/volt/record.json")
     * @ParamConverter("datavolt", class="array", converter="fos_rest.request_body")
     */
    public function putVoltRecordAction(array $datavolt)
    {
        //if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $device = $datavolt['device'];
        if ($datavolt['data']) {
            //20200416201917.000
            $datepost = $datavolt['data'];
            if (18 == strlen($datepost)) {
                $data = \Datetime::createFromFormat('YmdHis.000', $datepost, new \DateTimeZone('UTC'));
                $data->setTimeZone(new \DateTimeZone('Europe/Rome'));
            } else {
                $data = \Datetime::createFromFormat('Y-m-d H:i:s', $datepost);
            }
        } else {
            $data = new \DateTime();
            $data->setTimeZone(new \DateTimeZone('Europe/Rome'));
        }
        $volt = (float) $datavolt['volt'];
        $temp = (float) $datavolt['temp'];
        $batteryperc = (float) $datavolt['batteryperc'];
        $longitude = floatval($datavolt['longitude']);
        $latitude = floatval($datavolt['latitude']);

        $qb = $this->em->createQueryBuilder()
                ->select('d')
                ->from('App:Device', 'd')
                ->where('d.address = :address')
                ->setParameter('address', $device)
                ->getQuery();
        $devices = $qb->getResult();

        if (count($devices) <= 0) {
            $newdevice = new Device();
            $newdevice->setAddress($device);
            $this->em->persist($newdevice);
            $this->em->flush();
            //$em->clear();
        } else {
            $newdevice = $devices[0];
        }
        $newlog = new Log();
        $newlog->setDevice($newdevice);
        $newlog->setData($data);
        $newlog->setVolt($volt);
        $newlog->setTemp($temp);
        $newlog->setDetectorperc($batteryperc);
        $newlog->setLongitude($longitude);
        $newlog->setLatitude($latitude);

        $this->em->persist($newlog);
        $this->em->flush();

        $threshold = $newlog->getDevice()->getThreshold();
        $recipient = $this->params->get('mailer_user');
        if ($threshold && $recipient && $newlog->getVolt() < $threshold) {
            $email = (new Email())
                    ->from($recipient)
                    ->to($recipient)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('WARNING from ' . $newlog->getDevice() . ' *** ' . $newlog->getVolt() . ' volt ***')
                    ->text('WARNING from ' . $newlog->getDevice() . '! Received ' . $newlog->getVolt() . ' (less of ' . $threshold . ' threshold) at ' . $newlog->getData()->format('d/m/Y H:i:s'))
                    ->html('WARNING from ' . $newlog->getDevice() . '! Received ' . $newlog->getVolt() . ' (less of ' . $threshold . ' threshold) at ' . $newlog->getData()->format('d/m/Y H:i:s'));
            try {
                $this->mailer->send($email);
            } catch (\Exception $exc) {
                //echo $exc->getTraceAsString();
            }
        }

        $owmappid = $this->params->get('openweathermap_apikey');
        if ($owmappid && ($longitude || $latitude)) {
            try {
                $owmurl = 'https://api.openweathermap.org/data/2.5/weather?lon=' . $longitude . '&lat=' . $latitude . '&APPID=' . $owmappid;
                //$weatherjson = \json_decode(file_get_contents($owmurl), true);
                $response = $this->client->request(
                        'GET',
                        $owmurl
                );

                $weatherjson = \json_decode($response->getContent(), true);
                $weather = $weatherjson['weather'][0]['main'];
                $externaltemp = $weatherjson['main']['temp'] - 273.15;
                $cloudiness = $weatherjson['clouds']['all'];
                $location = $weatherjson['name'];
                $weathericon = $weatherjson['weather'][0]['icon'];

                $newlog->setWeather($weather);
                $newlog->setExternaltemp($externaltemp);
                $newlog->setCloudiness($cloudiness);
                $newlog->setLocation($location);
                $newlog->setWeathericon($weathericon);
                $this->em->persist($newlog);
                $this->em->flush();
            } catch (\Exception $exc) {
                return $this->view(['errcode' => -99, 'errmsg' => $exc->getTraceAsString()], Response::HTTP_OK);
            }
        }

        return $this->view(['errcode' => 0, 'errmsg' => 'OK'], Response::HTTP_OK);
    }
    /**
     * @RestAnnotations\Post("api/volt/record.json")
     * @ParamConverter("datavolt", class="array", converter="fos_rest.request_body")
     */
    public function postVoltRecordAction(array $datavolt)
    {
        return $this->putVoltRecordAction($datavolt);
    }
    /**
     * @RestAnnotations\Get("api/get/settings/app.json")
     */
    public function appGetSettingsAction()
    {
        $qb = $this->em->createQueryBuilder()
                ->select('s')
                ->from('App:Settings', 's')
                ->getQuery();
        $settings = $qb->getResult();
        $newsettings = [];
        foreach ($settings as $setting) {
            $newsettings[$setting->getKey()] = $setting->getValue();
        }
        //array("seconds" => 300, "enabled" => "1", devices => "44:44:09:04:01:CC, 34:43:0B:07:0F:58")
        return $this->view($newsettings);
    }
    /**
     * @RestAnnotations\Get("api/get/server/datetime.json")
     */
    public function appServerDatetimeAction()
    {
        $now = (new \DateTime());

        return $this->view(['datetime' => $now->format('Y-m-d H:i:s'), 'date' => $now->format('Y-m-d'), 'time' => $now->format('H:i:s')]);
    }
}
