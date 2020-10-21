<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Log;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/**
 * @RouteResource("api", pluralize=false)
 */
class ApiController extends FOSRestController
{
    private $mailer;
    private $params;
    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->params = $params;
    }
    /**
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
        }
        $volt = (float) $datavolt['volt'];
        $temp = (float) $datavolt['temp'];
        $batteryperc = (float) $datavolt['batteryperc'];
        $longitude = (float) $datavolt['longitude'];
        $latitude = (float) $datavolt['latitude'];
        // } else {
        //     $datavolt = json_decode($request->get('data'), true);
        //     $device = $datavolt['device'];
        //     //20200416201917.000
        //     $datepost = $datavolt['data'];
        //     if (18 == strlen($datepost)) {
        //         $data = \Datetime::createFromFormat('YmdHis.000', $datepost, new \DateTimeZone('UTC'));
        //         $data->setTimeZone(new \DateTimeZone('Europe/Rome'));
        //     } else {
        //         $data = \Datetime::createFromFormat('Y-m-d H:i:s', $datepost);
        //     }
        //     $volt = (float) $datavolt['volt'];
        //     $temp = (float) $datavolt['temp'];
        //     $batteryperc = (float) $datavolt['batteryperc'];
        //     $longitude = (float) $datavolt['longitude'];
        //     $latitude = (float) $datavolt['latitude'];
        // }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
                ->select('d')
                ->from('App:Device', 'd')
                ->where('d.address = :address')
                ->setParameter('address', $device)
                ->getQuery();
        $devices = $qb->getResult();

        if (count($devices) <= 0) {
            $newdevice = new Device();
            $newdevice->setAddress($device);
            $em->persist($newdevice);
            $em->flush();
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

        $em->persist($newlog);
        $em->flush();

        $threshold = $newlog->getDevice()->getThreshold();
        $recipient = $this->params->get('mailer_user');
        if ($threshold && $recipient && $newlog->getVolt() < $threshold) {

            $email = (new Email())
                    ->from('voltwatcheralert@manzolo.it')
                    ->to($recipient)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    ->priority(Email::PRIORITY_HIGH)
                    ->subject('WARNING from ' . $newlog->getDevice() . ' *** ' . $newlog->getVolt() . ' volt ***')
                    ->text('WARNING from ' . $newlog->getDevice() . '! Received ' . $newlog->getVolt() . ' (less of ' . $threshold . ' threshold) at ' . $newlog->getData()->format('d/m/Y H:i:s'))
                    ->html('WARNING from ' . $newlog->getDevice() . '! Received ' . $newlog->getVolt() . ' (less of ' . $threshold . ' threshold) at ' . $newlog->getData()->format('d/m/Y H:i:s'));

            $this->mailer->send($email);
        }

        $owmappid = $this->params->get('openweathermap_apikey');
        if ($owmappid && ($longitude || $latitude)) {
            try {
                $owmurl = 'http://api.openweathermap.org/data/2.5/weather?lon=' . $longitude . '&lat=' . $latitude . '&APPID=' . $owmappid;
                $weatherjson = \json_decode(file_get_contents($owmurl), true);

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
                $em->persist($newlog);
                $em->flush();
            } catch (\Exception $exc) {
                return $this->view(['errcode' => -99, 'errmsg' => $exc->getTraceAsString()], Response::HTTP_OK);
            }
        }

        return $this->view(['errcode' => 0, 'errmsg' => 'OK'], Response::HTTP_OK);
    }
    public function appGetSettingsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
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
    public function appServerDatetimeAction()
    {
        $now = (new \DateTime());

        return $this->view(['datetime' => $now->format('Y-m-d H:i:s'), 'date' => $now->format('Y-m-d'), 'time' => $now->format('H:i:s')]);
    }
}
