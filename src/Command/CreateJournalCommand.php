<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em) {
        $this->em = $em;
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
        $to_date = clone new \DateTime();

        $avgdays = [];
        for ($date = $from_date; $date <= $to_date; $date->modify('+1 days')) {
            $qb = $em->createQueryBuilder('l')
                    ->select("d.id as deviceid, d.address as device, d.name as devicename, AVG(l.volt) avgvolt, CONCAT(SUBSTRING(l.data,12,4),'0') AS ora")
                    ->from('App:Log', 'l')
                    ->leftJoin('l.device', 'd')
                    //->andWhere('l.data >= :data')
                    //->setParameter("data", $date)
                    ->groupBy('l.device, ora')
                    ->orderBy('ora', 'DESC')
                    ->getQuery();
            $riepilogorows = $qb->getResult();
            foreach ($riepilogorows as $row) {
                $avgora = $row['ora'];
                $avgdeviceid = $row['deviceid'];
                $avgdevice = $row['device'];
                $avgdevicename = $row['devicename'];
                $avgvolt = $row['avgvolt'];
                $datechk = \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $avgora);
                $avgdays[] = ['device' => $avgdevice, 'deviceid' => $avgdeviceid, 'devicename' => $avgdevicename, 'ora' => $datechk, 'avgvolt' => $avgvolt];
            }
        }
        foreach ($avgdays as $avgday) {
            $device = $em->getRepository('App:Device')->find($avgday['deviceid']);
            $dal = clone $avgday['ora'];
            $al = clone $avgday['ora']->modify('+599 seconds');
            $newJournal = new \App\Entity\Journal();
            $newJournal->setDevice($device);
            $newJournal->setDal($dal);
            $newJournal->setAl($al);
            $newJournal->setAvgvolt($avgday['avgvolt']);
            $em->persist($newJournal);

            $qb = $em->createQueryBuilder('l')
                    ->select('l')
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.data between :dal and :al')
                    ->setParameter('device', $device)
                    ->setParameter('dal', $newJournal->getDal())
                    ->setParameter('al', $newJournal->getAl())
                    ->getQuery();

            $dettagliorows = $qb->getResult();
            /* if ($avgday["deviceid"] == 1 && $dal->format("Y-m-d H:i") == '2019-10-05 00:00'){
              dump(count($dettagliorows));
              dump($newJournal->getDal());
              dump($newJournal->getAl());
              dump($dal);
              dump($al);
              exit;
              } */
            if (count($dettagliorows) > 0) {
                $avg = 0;
                for ($index = 0; $index < count($dettagliorows); ++$index) {
                    $currvolt = (float) $dettagliorows[$index]->getVolt();
                    $avg = (float) $avg + $currvolt;
                }
                $newJournal->setVolt(round($avg / $index, 2));
                $newJournal->setDatarilevazione($dettagliorows[0]->getData());
                $em->persist($newJournal);
            }
            $em->flush();
        }

        $output->writeln('<info>Done</info>');
        return 0;
    }

}
