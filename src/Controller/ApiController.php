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
        //$now = (new \DateTime());
        //return new JsonResponse(array("datetime" => $now->format("Y-m-d H:i:s"), "date" => $now->format("Y-m-d"), "time" => $now->format("H:i:s")));
        set_time_limit(960);
        ini_set('memory_limit', '2048M');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('l')
                ->select("l")
                ->from('App:Log', 'l')
                ->orderBy('l.data', "DESC")
                ->getQuery();
        $dettagliorows = $qb->getResult();

        $qb = $em->createQueryBuilder('l')
                ->select("CONCAT(CONCAT(d.address,' '),d.name) as device, MAX(l.volt) maxvolt, MIN(l.volt) minvolt, SUBSTRING(l.data,1,10) AS grData")
                ->from('App:Log', 'l')
                ->leftJoin('l.device', 'd')
                ->groupBy('l.device, grData')
                ->orderBy('grData', "DESC")
                ->getQuery();
        $riepilogorows = $qb->getResult();
        
        //Creare un nuovo file
        $spreadsheet = new Spreadsheet();
        $objPHPExcel = new Xls($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);

        // Set properties
        $spreadsheet->getProperties()->setCreator('Manzolo');
        $spreadsheet->getProperties()->setLastModifiedBy('Manzolo');

        $dettagliosheet = $spreadsheet->createSheet();
        $dettagliosheet->setTitle('Dettaglio');
        $dettagliosheet->setCellValueByColumnAndRow(1, 1, "Device");
        $dettagliosheet->setCellValueByColumnAndRow(2, 1, "Volt");
        $dettagliosheet->setCellValueByColumnAndRow(3, 1, "Temp");
        $dettagliosheet->setCellValueByColumnAndRow(4, 1, "Data");

        $row = 1;
        foreach ($dettagliorows as $dettaglio) {
            $row ++;
            $col = 1;
            $dettagliosheet->setCellValueByColumnAndRow($col, $row, $dettaglio->getDevice()->__toString());
            $col = $col + 1;
            $dettagliosheet->setCellValueByColumnAndRow($col, $row, $dettaglio->getVolt());
            $col = $col + 1;
            $dettagliosheet->setCellValueByColumnAndRow($col, $row, $dettaglio->getTemp());
            $col = $col + 1;

            $datatmp = $dettaglio->getData()->format("Y-m-d H:i:s");
            $d = (int) substr($datatmp, 8, 2);
            $m = (int) substr($datatmp, 5, 2);
            $y = (int) substr($datatmp, 0, 4);
            $h = (int) substr($datatmp, 11, 2);
            $i = (int) substr($datatmp, 14, 2);
            $dataval = \PhpOffice\PhpSpreadsheet\Shared\Date::formattedPHPToExcel($y, $m, $d, $h, $i, 0);
            $dettagliosheet->setCellValueByColumnAndRow($col, $row, $dataval);
        }

        $dettagliosheet->getStyle('D2:' . "D" . $row)
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yyyy hh:mm:ss');

        for ($index = 0; $index < $col + 1; $index++) {
            $letteracolonna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
            $dettagliosheet->getColumnDimension($letteracolonna)->setAutoSize(true);
        }


        // ***************  Scrittura Riepilogo  ********************
        //Scrittura su file
        $spreadsheet->setActiveSheetIndex(0);
        $riepilogosheet = $spreadsheet->getActiveSheet();
        $riepilogosheet->setTitle('Riepilogo');

        $riepilogosheet->getParent()->getDefaultStyle()->getFont()->setName('Verdana');


        //$letteracolonna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(0);
        $riepilogosheet->setCellValueByColumnAndRow(1, 1, "Device");
        $riepilogosheet->setCellValueByColumnAndRow(2, 1, "Max volt");
        $riepilogosheet->setCellValueByColumnAndRow(3, 1, "Min volt");
        $riepilogosheet->setCellValueByColumnAndRow(4, 1, "Data");

        $row = 1;
        foreach ($riepilogorows as $dettaglio) {
            $row ++;
            $col = 1;
            $riepilogosheet->setCellValueByColumnAndRow($col, $row, $dettaglio["device"]);
            $col = $col + 1;
            $riepilogosheet->setCellValueByColumnAndRow($col, $row, $dettaglio["maxvolt"]);
            $col = $col + 1;
            $riepilogosheet->setCellValueByColumnAndRow($col, $row, $dettaglio["minvolt"]);
            $col = $col + 1;
            $datatmp = $dettaglio["grData"];
            $d = (int) substr($datatmp, 8, 2);
            $m = (int) substr($datatmp, 5, 2);
            $y = (int) substr($datatmp, 0, 4);
            $dataval = \PhpOffice\PhpSpreadsheet\Shared\Date::formattedPHPToExcel($y, $m, $d, 0, 0, 0);
            $riepilogosheet->setCellValueByColumnAndRow($col, $row, $dataval);
        }

        $riepilogosheet->getStyle('B2:' . "B" . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

        $riepilogosheet->getStyle('C2:' . "C" . $row)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

        $riepilogosheet->getStyle('D2:' . "D" . $row)
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yyyy');

        for ($index = 0; $index < $col + 1; $index++) {
            $letteracolonna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
            $riepilogosheet->getColumnDimension($letteracolonna)->setAutoSize(true);
        }


        //Si crea un oggetto
        $todaydate = date('d-m-y');

        $filename = 'Exportazione';
        $filename = $filename . '-' . $todaydate . '-' . strtoupper(md5(uniqid(rand(), true)));
        $filename = $filename . '.xls';
        $filename = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filename)) {
            unlink($filename);
        }

        $objPHPExcel->save($filename);

        return new Response(
                file_get_contents($filename),
                200,
                array(
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="Estrazione.xls"')
        );
    }
}
