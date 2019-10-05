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

    private $journaldiffdays = '-3 days';
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

        $date = (new \DateTime())->modify($this->journaldiffdays);

        $from_date = clone $date;
        $to_date = clone (new \DateTime());

        $avgdays = array();
        for ($date = $from_date; $date <= $to_date; $date->modify("+1 days")) {
            $qb = $em->createQueryBuilder('l')
                    ->select("d.id as deviceid, d.address as device, d.name as devicename, AVG(l.volt) avgvolt, CONCAT(SUBSTRING(l.data,12,4),'0') AS ora")
                    ->from('App:Log', 'l')
                    ->leftJoin('l.device', 'd')
                    //->andWhere('l.data >= :data')
                    //->setParameter("data", $date)
                    ->groupBy('l.device, ora')
                    ->orderBy('ora', "DESC")
                    ->getQuery();
            $riepilogorows = $qb->getResult();
            foreach ($riepilogorows as $row) {
                $avgora = $row["ora"];
                $avgdeviceid = $row["deviceid"];
                $avgdevice = $row["device"];
                $avgdevicename = $row["devicename"];
                $avgvolt = $row["avgvolt"];
                $datechk = \DateTime::createFromFormat("Y-m-d H:i", $date->format('Y-m-d') . " " . $avgora);
                $avgdays[] = array("device" => $avgdevice, "deviceid" => $avgdeviceid, "devicename" => $avgdevicename, "ora" => $datechk, "avgvolt" => $avgvolt);
            }
        }
        foreach ($avgdays as $avgday) {
            $device = $em->getRepository("App:Device")->find($avgday["deviceid"]);

            $newJournal = new \App\Entity\Journal();
            $newJournal->setDevice($device);
            $newJournal->setDal(clone $avgday["ora"]);
            $newJournal->setAl(clone $avgday["ora"]->modify("+599 seconds"));
            $newJournal->setAvgvolt($avgday["avgvolt"]);
            $em->persist($newJournal);
            $em->flush();

            $qb = $em->createQueryBuilder('l')
                    ->select("l")
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.data between :dal and :al')
                    ->setParameter("device", $device->getId())
                    ->setParameter("dal", $newJournal->getDal())
                    ->setParameter("al", $newJournal->getAl())
                    ->getQuery();

            $dettagliorows = $qb->getResult();
            if (count($dettagliorows) == 1) {
                $newJournal->setVolt($dettagliorows[0]->getVolt());
                $newJournal->setDatarilevazione($dettagliorows[0]->getData());
            } else {
                if (count($dettagliorows) > 1) {
                    $avg = 0;
                    for ($index = 0; $index < count($dettagliorows); $index++) {
                        $currvolt = $dettagliorows[$index]->getVolt();
                        $avg = $avg + $currvolt;
                    }
                    $newJournal->setVolt($avg);
                    $newJournal->setDatarilevazione($dettagliorows[0]->getData());
                }
            }
        }



        $output->writeln('<info>Done</info>');
    }

}
