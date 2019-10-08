<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;
use Cdf\BiCoreBundle\Controller\FiController;
use Cdf\BiCoreBundle\Utils\Tabella\ParametriTabella;
use Cdf\BiCoreBundle\Utils\Entity\EntityUtils;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Device;
use App\Entity\Log;

class DefaultController extends AbstractController {

    private $chartdifftime = '-1 days';

    /**
     * Matches / exactly
     *
     * @Route("/", name="welcome")
     */
    public function index(Request $request, Packages $assetsmanager) {

        /* chart */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('d')
                ->select("d")
                ->from('App:Device', 'd')
                ->getQuery();
        $devicesrows = $qb->getResult();

        $infodevices = array();
        foreach ($devicesrows as $device) {
            $qb = $em->createQueryBuilder('l')
                    ->select("l")
                    ->from('App:Log', 'l')
                    ->where("l.device = :device")
                    ->setParameter(":device", $device)
                    ->orderBy("l.data", "DESC")
                    ->setMaxResults(1)
                    ->getQuery();
            $resultrows = $qb->getResult();
            if (count($resultrows) == 1) {
                $infodevice = $resultrows[0];
                $infodevices[$device->getAddress()] = array("deviceinfo" => $infodevice->getDevice(), "volt" => $infodevice->getVolt(), "data" => $infodevice->getData());
            }
        }

        $charts = $this->getCharts($devicesrows);
        $crudtemplate = "Default/index.html.twig";
        return $this->render($crudtemplate, array("infodevices" => $infodevices, 'charts' => $charts));
    }

    private function getCharts($devices) {
        /* chart */
        $charts = array();

        $date = (new \DateTime())->modify($this->chartdifftime);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('d')
                ->select("d")
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        foreach ($devicesrows as $device) {

            /* chart */
            $qb = $em->createQueryBuilder('j')
                    ->select("j")
                    ->from('App:Journal', 'j')
                    ->where('j.device = :device')
                    ->andWhere('j.dal <= :ora')
                    ->andWhere('j.volt is not null')
                    ->setParameter("device", $device->getId())
                    ->setParameter("ora", new \DateTime())
                    ->getQuery();

            $journalrows = $qb->getResult();


            $dati = array();
            $dati[] = ['Data', 'Volts'/* , 'Temps' */, 'Avg'];

            foreach ($journalrows as $journalrows) {
                $dati[] = [$journalrows->getDatarilevazione(), floatval($journalrows->getVolt()), round(floatval($journalrows->getAvgvolt()), 2)];
            }
            if (count($dati) == 1) {
                $dati[] = [new \DateTime(), 0, 0];
            }
            $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
            $chart->getData()->setArrayToDataTable($dati);
            $chart->setElementID($device->getId());

            $chart->getOptions()->getChart()->setTitle($device->getName());
            $chart->getOptions()
                    ->setSeries([['axis' => 'Volts'], ['axis' => 'AvgVolts']/* , ['axis' => 'Temps'] */])
            ->setAxes(['y' => ['Volts' => ['label' => 'Volts'], 'AvgVolts' => ['label' => 'Average Volts']/* , 'Temps' => ['label' => 'Temps (Celsius)'] */]])
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

}
