<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Device;
use App\Entity\Log;

class ApiController extends AbstractController
{
    /**
     * Matches / exactly
     *
     * @Route("/api/sendvolt", name="sendvolt")
     */
    public function sendvolt(Request $request, \Swift_Mailer $mailer)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $datavolt = json_decode($request->getContent(), true);
            $device = $datavolt["device"];
            $data = \Datetime::createFromFormat("Y-m-d H:i:s", $datavolt["data"]);
            $volt = (float) $datavolt["volt"];
            $temp = (float) $datavolt["temp"];
            $batteryperc = (float) $datavolt["batteryperc"];
            $longitude = (float) $datavolt["longitude"];
            $latitude = (float) $datavolt["latitude"];
        } else {
            $datavolt = json_decode($request->get("data"), true);
            $device = $datavolt["device"];
            $data = \Datetime::createFromFormat("Y-m-d H:i:s", $datavolt["data"]);
            $volt = (float) $datavolt["volt"];
            $temp = (float) $datavolt["temp"];
            $batteryperc = (float) $datavolt["batteryperc"];
            $longitude = (float) $datavolt["longitude"];
            $latitude = (float) $datavolt["latitude"];
        }

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
            $em->clear();
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
        $recipient = getenv("mailer_user");
        if ($threshold && $recipient && $newlog->getVolt() < $threshold) {
            $message = (new \Swift_Message("WARNING from " . $newlog->getDevice() . " *** " . $newlog->getVolt() . " volt ***"))
                    ->setFrom("voltwatcheralert@manzolo.it")
                    ->setTo($recipient)
                    ->setBody("WARNING from " . $newlog->getDevice() . "! Received " . $newlog->getVolt() . " (less of " . $threshold . " threshold) at " . $newlog->getData()->format("d/m/Y H:i:s"), 'text/html')
                    // you can remove the following code if you don't define a text version for your emails
                    ->addPart("WARNING from " . $newlog->getDevice() . "! Received " . $newlog->getVolt() . " (less of " . $threshold . " threshold) at " . $newlog->getData()->format("d/m/Y H:i:s"), 'text/plain')
            ;

            $mailer->send($message);
        }

        $owmappid = getenv("openweathermap_apikey");
        if ($owmappid && ($longitude || $latitude)) {
            try {
                $owmurl = "http://api.openweathermap.org/data/2.5/weather?lon=" . $longitude . "&lat=" . $latitude . "&APPID=" . $owmappid;
                $weatherjson = \json_decode(file_get_contents($owmurl), true);

                $weather = $weatherjson["weather"][0]["main"];
                $externaltemp = $weatherjson["main"]["temp"] - 273.15;
                $cloudiness = $weatherjson["clouds"]["all"];
                $location = $weatherjson["name"];
                $weathericon = $weatherjson["weather"][0]["icon"];

                $newlog->setWeather($weather);
                $newlog->setExternaltemp($externaltemp);
                $newlog->setCloudiness($cloudiness);
                $newlog->setLocation($location);
                $newlog->setWeathericon($weathericon);
                $em->persist($newlog);
                $em->flush();
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        return new JsonResponse(array("errcode" => 0, "errmsg" => "OK"));
    }
    /**
     * Matches / exactly
     *
     * @Route("/api/appgetsettings", name="appgetsettings")
     */
    public function appGetSettings(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
                ->select('s')
                ->from('App:Settings', 's')
                ->getQuery();
        $settings = $qb->getResult();
        $newsettings = array();
        foreach ($settings as $setting) {
            $newsettings[$setting->getKey()] = $setting->getValue();
        }
        //array("seconds" => 300, "enabled" => "1", devices => "44:44:09:04:01:CC, 34:43:0B:07:0F:58")
        return new JsonResponse($newsettings);
    }
    /**
     * Matches / exactly
     *
     * @Route("/api/getserverdatetime", name="getserverdatetime")
     */
    public function appServerDatetime(Request $request)
    {

        return new Response(\DateTime::createFromFormat("Y-m-d_H:i:s", new \DateTime()));
    }
}
