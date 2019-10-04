<?php

namespace App\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Device;
use App\Entity\Log;
use Exception;

class CreateJournalCommand extends Command {
    private $chartdifftime = '-5 days';


    protected static $defaultName = 'voltwatcher:createjournal';
    private $em;

    protected function configure() {
        $this
                ->setDescription('Journal creation')
                ->setHelp('Store journal')
        ;
    }

    public function __construct(ObjectManager $em, LoggerInterface $logger) {
        $this->em = $em;
        $this->logger = $logger;
        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->em;
        $query = $em->createQueryBuilder()
                ->delete('App:Journal', 'd')
                ->getQuery()
                ->execute();

        $date = (new \DateTime())->modify($this->chartdifftime);
        $qb = $em->createQueryBuilder('d')
                ->select("d")
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        foreach ($devicesrows as $device) {
            $qb = $em->createQueryBuilder('l')
                    ->select("l")
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.data >= :data')
                    ->setParameter("device", $device->getId())
                    ->setParameter("data", $date)
                    ->orderBy('l.data', "DESC")
                    ->getQuery();

            $dettagliorows = $qb->getResult();

            $qb = $em->createQueryBuilder('l')
                    ->select("d.address as device, AVG(l.volt) avgvolt, CONCAT(SUBSTRING(l.data,12,4),'0') AS ora")
                    ->from('App:Log', 'l')
                    ->leftJoin('l.device', 'd')
                    ->andWhere('l.data >= :data')
                    ->setParameter("data", $date)
                    ->groupBy('l.device, ora')
                    ->orderBy('ora', "DESC")
                    ->getQuery();
            $riepilogorows = $qb->getResult();
            //dump($riepilogorows);exit;

            $dati = array();
            foreach ($dettagliorows as $row) {
                $datej = substr($row->getData()->format("H:i"), 0, 4) . "0";
                dump($datej);exit;
            }
            if (count($dati) == 1) {
                $dati[] = [new \DateTime(), 0, 0];
            }
            $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart();
            $chart->getData()->setArrayToDataTable($dati);
            $chart->setElementID($device->getId());

            $chart->getOptions()->setTitle($device->getName());
            $chart->getOptions()
                    ->setSeries([['axis' => 'Volts'], ['axis' => 'AvgVolts']/* , ['axis' => 'Temps'] */])
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

        $output->writeln('<info>Done</info>');
    }

}
