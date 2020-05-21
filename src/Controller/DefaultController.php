<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    private $chartdifftime = '-2 days';

    /**
     * Matches / exactly.
     *
     * @Route("/", name="welcome")
     */
    public function index(Request $request, Packages $assetsmanager)
    {
        /* chart */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('d')
                ->select('d')
                ->from('App:Device', 'd')
                ->getQuery();
        $devicesrows = $qb->getResult();

        $infodevices = [];
        foreach ($devicesrows as $device) {
            $qb = $em->createQueryBuilder('l')
                    ->select('l')
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->setParameter(':device', $device)
                    ->orderBy('l.data', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery();
            $resultrows = $qb->getResult();
            if (1 == count($resultrows)) {
                $infodevice = $resultrows[0];
                $hour = substr($infodevice->getData()->format('H:i:s'), 0, 4);
                $lastweek = clone $infodevice->getData();
                $qb = $em->createQueryBuilder('l')
                        ->select('l')
                        ->from('App:Log', 'l')
                        ->where('l.device = :device')
                        ->andWhere('l.data < :data')
                        ->andWhere('l.data > :datachk')
                        ->andWhere('substring(l.data,12,4) = :ora')
                        ->setParameter(':device', $device)
                        ->setParameter(':data', $infodevice->getData())
                        ->setParameter(':datachk', $lastweek->modify('- 7 days'))
                        ->setParameter(':ora', $hour)
                        ->orderBy('l.data', 'DESC')
                        ->getQuery();
                $resultoldrows = $qb->getResult();
                $infodevices[$device->getAddress()] = ['deviceinfo' => $infodevice->getDevice(), 'volt' => $infodevice->getVolt(), 'data' => $infodevice->getData(), 'weathericon' => $infodevice->getWeathericon(), 'oldrows' => $resultoldrows];
            }
        }
        $charts = $this->getCharts($devicesrows);
        $crudtemplate = 'Default/index.html.twig';

        return $this->render($crudtemplate, ['infodevices' => $infodevices, 'charts' => $charts]);
    }

    private function getCharts($devices)
    {
        /* chart */
        $charts = [];

        $date = (new \DateTime())->modify($this->chartdifftime);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('d')
                ->select('d')
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();

        foreach ($devicesrows as $device) {
            /* chart */
            $qb = $em->createQueryBuilder('l')
                    ->select('l')
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.data >= :data')
                    ->setParameter('device', $device->getId())
                    ->setParameter('data', $date)
                    ->getQuery();

            $devicerows = $qb->getResult();

            $dati = [];
            $dati[] = ['Data', 'Volts'];

            foreach ($devicerows as $devicerows) {
                $dati[] = [$devicerows->getData(), floatval($devicerows->getVolt())];
            }
            if (1 == count($dati)) {
                $dati[] = [new \DateTime(), 0, 0];
            }
            $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
            $chart->getData()->setArrayToDataTable($dati);
            $chart->setElementID($device->getId());

            $chart->getOptions()->getChart()->setTitle($device->getName());
            $chart->getOptions()
                    ->setSeries([['axis' => 'Volts']])
            //->setAxes(['y' => ['Volts' => ['label' => 'Volts'], 'AvgVolts' => ['label' => 'Average Volts']/* , 'Temps' => ['label' => 'Temps (Celsius)'] */]])
            ;

            $chart->getOptions()->setHeight(400);

            //$chart->getOptions()->getHAxis()->setFormat('dd/MM/Y HH:mm');
            $chart->getOptions()->getHAxis()->setFormat('dd/MM H:mm');
            //$chart->getOptions()->getHAxis()->setFormat('HH:mm');
            $chart->getOptions()->getVAxis()->setFormat('#0.00');
            $chart->getOptions()->getVAxis()->setMinValue(11);
            $chart->getOptions()->getVAxis()->setMaxValue(16);
            $chart->getOptions()->getLegend()->setPosition('none');
            $charts[] = $chart;
            /* chart */
        }

        return $charts;
    }

    /**
     * Matches / exactly.
     *
     * @Route("/api/sendvolt", name="sendvolt")
     */
    public function sendvolt(Request $request, \Swift_Mailer $mailer)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $datavolt = json_decode($request->getContent(), true);
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
        } else {
            $datavolt = json_decode($request->get('data'), true);
            $device = $datavolt['device'];
            //20200416201917.000
            $datepost = $datavolt['data'];
            if (18 == strlen($datepost)) {
                $data = \Datetime::createFromFormat('YmdHis.000', $datepost, new \DateTimeZone('UTC'));
                $data->setTimeZone(new \DateTimeZone('Europe/Rome'));
            } else {
                $data = \Datetime::createFromFormat('Y-m-d H:i:s', $datepost);
            }
            $volt = (float) $datavolt['volt'];
            $temp = (float) $datavolt['temp'];
            $batteryperc = (float) $datavolt['batteryperc'];
            $longitude = (float) $datavolt['longitude'];
            $latitude = (float) $datavolt['latitude'];
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
        $recipient = getenv('mailer_user');
        if ($threshold && $recipient && $newlog->getVolt() < $threshold) {
            $message = (new \Swift_Message('WARNING from '.$newlog->getDevice().' *** '.$newlog->getVolt().' volt ***'))
                 ->setFrom('voltwatcheralert@manzolo.it')
                 ->setTo($recipient)
                 ->setBody('WARNING from '.$newlog->getDevice().'! Received '.$newlog->getVolt().' (less of '.$threshold.' threshold) at '.$newlog->getData()->format('d/m/Y H:i:s'), 'text/html')
                 // you can remove the following code if you don't define a text version for your emails
                 ->addPart('WARNING from '.$newlog->getDevice().'! Received '.$newlog->getVolt().' (less of '.$threshold.' threshold) at '.$newlog->getData()->format('d/m/Y H:i:s'), 'text/plain')
         ;

            $mailer->send($message);
        }

        $owmappid = getenv('openweathermap_apikey');
        if ($owmappid && ($longitude || $latitude)) {
            try {
                $owmurl = 'http://api.openweathermap.org/data/2.5/weather?lon='.$longitude.'&lat='.$latitude.'&APPID='.$owmappid;
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
                echo $exc->getTraceAsString();
            }
        }

        return new JsonResponse(['errcode' => 0, 'errmsg' => 'OK']);
    }
}
