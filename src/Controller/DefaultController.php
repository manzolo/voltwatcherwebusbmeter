<?php

namespace App\Controller;

use App\Entity\Device;
use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart;
use DateTime;

class DefaultController extends AbstractController
{

    /**
     * Matches / exactly.
     *
     * @Route("/", name="welcome")
     */
    public function index(Request $request, Packages $assetsmanager, EntityManagerInterface $em): Response
    {
        $crudtemplate = 'Default/index.html.twig';

        return $this->render($crudtemplate);
    }

    /**
      @return array<LineChart> Charts
     */
    private function getCharts(EntityManagerInterface $em): array
    {
        /* chart */
        $charts = [];

        $date = (new DateTime())->modify($this->chartdifftime);
        $qb = $em->createQueryBuilder()
                ->select('d')
                ->from(Device::class, 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();

        foreach ($devicesrows as $device) {
            /* chart */
            $qb = $em->createQueryBuilder()
                    ->select('l')
                    ->from(Log::class, 'l')
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
                $dati[] = [new DateTime(), 0];
            }
            $chart = new LineChart();
            $chart->getData()->setArrayToDataTable($dati);
            $chart->setElementID($device->getId());
            $deviceName = $device->getName() ? $device->getName() : $device->getAddress();
            $chart->getOptions()->getChart()->setTitle($deviceName);
            /** @phpstan-ignore-next-line */
            $chart->getOptions()->setSeries([['axis' => 'Volts']])
            //->setAxes(['y' => ['Volts' => ['label' => 'Volts'],
            //'AvgVolts' => ['label' => 'Average Volts']/* , 'Temps' => ['label' => 'Temps (Celsius)'] */]])
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

    /*
     * Matches / exactly.
     *
     * @Route("/api/sendvolt", name="sendvolt")
     */
    /*
      public function sendvolt(Request $request, \Swift_Mailer $mailer)
      {
      if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
      $datavolt = json_decode($request->getContent(), true);
      $device = $datavolt['device'];
      if ($datavolt['data']) {
      //20200416201917.000
      $datepost = $datavolt['data'];
      if (18 == strlen($datepost)) {
      $data = Datetime::createFromFormat('YmdHis.000', $datepost, new DateTimeZone('UTC'));
      $data->setTimeZone(new DateTimeZone('Europe/Rome'));
      } else {
      $data = Datetime::createFromFormat('Y-m-d H:i:s', $datepost);
      }
      } else {
      $data = new DateTime();
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
      $data = Datetime::createFromFormat('YmdHis.000', $datepost, new DateTimeZone('UTC'));
      $data->setTimeZone(new DateTimeZone('Europe/Rome'));
      } else {
      $data = Datetime::createFromFormat('Y-m-d H:i:s', $datepost);
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
      ->setBody('WARNING from '.$newlog->getDevice().'! Received '.
      $newlog->getVolt().' (less of '.$threshold.' threshold) at '.$newlog->getData()->format('d/m/Y H:i:s'), 'text/html')
      // you can remove the following code if you don't define a text version for your emails
      ->addPart('WARNING from '.$newlog->getDevice().'! Received '.$newlog->getVolt().
     * ' (less of '.$threshold.' threshold) at '.$newlog->getData()->format('d/m/Y H:i:s'), 'text/plain')
      ;

      $mailer->send($message);
      }

      $owmappid = getenv('openweathermap_apikey');
      if ($owmappid && ($longitude || $latitude)) {
      try {
      $owmurl = 'https://api.openweathermap.org/data/2.5/weather?lon='.$longitude.'&lat='.$latitude.'&APPID='.$owmappid;
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
     */
    /*
     * Matches / exactly.
     *
     * @Route("/api/appgetsettings", name="appgetsettings")
     */
    /*
      public function appGetSettings(Request $request)
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
      return new JsonResponse($newsettings);
      }

     */
    /*
     * Matches / exactly.
     *
     * @Route("/api/getserverdatetime", name="getserverdatetime")
     */
    /*    public function appServerDatetime(Request $request)
      {
      $now = (new DateTime());

      return new JsonResponse(['datetime' => $now->format('Y-m-d H:i:s'), 'date' => $now->format('Y-m-d'), 'time' => $now->format('H:i:s')]);
      }
     */
}
