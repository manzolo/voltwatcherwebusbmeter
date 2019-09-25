<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Device;
use App\Entity\Log;

class DefaultController extends AbstractController {

    /**
     * Matches / exactly
     *
     * @Route("/", name="welcome")
     */
    public function index(Request $request) {
        return new Response("Welcome!");
    }

    /**
     * Matches / exactly
     *
     * @Route("/api/sendvolt", name="sendvolt")
     */
    public function sendvolt(Request $request) {
        $datavolt = json_decode($request->get("data"), true);
        $device = $datavolt["device"];
        $data = \Datetime::createFromFormat("Y-m-d H:i:s", $datavolt["data"]);
        $volt = (float) $datavolt["volt"];
        $temp = (float) $datavolt["temp"];

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
        $em->persist($newlog);
        $em->flush();
        $em->clear();

        return new JsonResponse(array("errcode" => 0, "errmsg" => "OK"));
    }

}
