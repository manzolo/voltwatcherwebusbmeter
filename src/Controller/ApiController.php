<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Device;
use App\Entity\Log;

class ApiController extends AbstractController {

    /**
     * Matches / exactly
     *
     * @Route("/api/sendvolt", name="sendvolt")
     */
    public function sendvolt(Request $request) {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $datavolt = json_decode($request->getContent(), true);
            $device = $datavolt["device"];
            $data = \Datetime::createFromFormat("Y-m-d H:i:s", $datavolt["data"]);
            $volt = (float) $datavolt["volt"];
            $temp = (float) $datavolt["temp"];
            $batteryperc = (float) $datavolt["batteryperc"];
        } else {
            $datavolt = json_decode($request->get("data"), true);
            $device = $datavolt["device"];
            $data = \Datetime::createFromFormat("Y-m-d H:i:s", $datavolt["data"]);
            $volt = (float) $datavolt["volt"];
            $batteryperc = (float) $datavolt["batteryperc"];
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
        
        $em->persist($newlog);
        $em->flush();
        $em->clear();

        return new JsonResponse(array("errcode" => 0, "errmsg" => "OK"));
    }

    /**
     * Matches / exactly
     *
     * @Route("/api/appgetsettings", name="appgetsettings")
     */
    public function appGetSettings(Request $request) {
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

}
