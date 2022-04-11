<?php

namespace App\Controller;

use App\Entity\Device;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Cdf\BiCoreBundle\Controller\FiController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

/**
 * Device controller.
 */
class DeviceController extends FiController
{

    private string $chartdifftime = '-3 days';

    /**
     * Matches / exactly.
     *
     * @Route("/Device/List", name="Device_List", options={"expose"=true} )
     */
    public function getDevices(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $ret = [];
        $devices = $em->createQueryBuilder()
                ->select('d')
                ->from(Device::class, 'd')
                ->getQuery()
                ->getResult()
        ;

        foreach ($devices as $device) {
            $ret[] = [
                "id" => $device->getId(),
                "address" => $device->getAddress(),
                "name" => $device->getName()
            ];
        }


        return new JsonResponse($ret);
    }
    /**
     * Matches /Device/Chart exactly.
     *
     * @Route("/Device/Chart/{device}", name="Device_Chart", options={"expose"=true})
     */
    public function getDeviceChart(Request $request, string $device, EntityManagerInterface $em): JsonResponse
    {
        $date = (new DateTime())->modify($this->chartdifftime);

        $qb = $em->createQueryBuilder()
                ->select('l')
                ->from(\App\Entity\Log::class, 'l')
                ->where('l.device = :device')
                ->andWhere('l.data >= :data')
                ->setParameter('device', $device)
                ->setParameter('data', $date)
                ->getQuery();

        $devicerows = $qb->getResult();

        $dati = [];
        $chartType = ["role" => "tooltip", "type" => "string", "p" => ["html" => true]];
        $dati[] = ['Data', 'Volts', $chartType];

        foreach ($devicerows as $devicerows) {
            $locationInfo = "";
            if ($devicerows->getLocation()) {
                $locationInfo = '<img style="width: 60px; height:60px;" src="https://openweathermap.org/img/wn/' . $devicerows->getWeathericon() . '@2x.png" />'
                        . '<p>' . $devicerows->getLocation() . '</p>';
            }

            $tooltip = '<br/><div style="text-align: center;">'
                    . '<p>' . $devicerows->getData()->format("d/m/Y H:i") . '</p>' .
                    $locationInfo
                    . '</div><div style="color: #0073e6; font-family: Roboto; font-size: 18px; font-weight: bold;text-align: center;">'
                    . $devicerows->getVolt() . "</div><br/>"
            ;
            $dati[] = [$devicerows->getData()->format("c"), floatval($devicerows->getVolt()), $tooltip];
        }
        if (1 == count($dati)) {
            $dati[] = [new DateTime(), 0];
        }

        return new JsonResponse($dati);
    }
}
