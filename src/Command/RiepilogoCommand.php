<?php

namespace App\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;

class RiepilogoCommand extends Command
{

    private $journaldiffdays = '-7 days';
    protected static $defaultName = 'voltwatcher:weeklyreport';
    private $em;
    private $logger;
    private $mailer;

    protected function configure()
    {
        $this
                ->setDescription('Weekly report')
                ->setHelp('Report generator')
                ->addOption(
                        'sendmail',
                        null,
                        InputOption::VALUE_OPTIONAL,
                        'Should I send mail?',
                        false
        );
    }
    public function __construct(ObjectManager $em, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->mailer = $mailer;
        // you *must* call the parent constructor
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = (new \DateTime())->modify($this->journaldiffdays);
        $em = $this->em;
        $qb = $em->createQueryBuilder('l')
                ->select("d.id as deviceid, d.address as device, d.name as devicename, AVG(l.volt) avgvolt, MIN(l.volt) minvolt, MAX(l.volt) maxvolt")
                ->from('App:Log', 'l')
                ->leftJoin('l.device', 'd')
                ->andWhere('l.data >= :data')
                ->setParameter("data", $date)
                ->groupBy('l.device')
                ->getQuery();

        $riepilogorows = $qb->getResult();
        $body = "Weekly report<br/><br/>";
        foreach ($riepilogorows as $row) {
            $deviceid = $row["deviceid"];
            $avg = round($row["avgvolt"], 2);
            $min = round($row["minvolt"], 2);
            $max = round($row["maxvolt"], 2);
            $device = $em->getRepository("App:Device")->find($deviceid);
            $body = $body . $device->__toString() . " Min:" . $min . " Avg:" . $avg . " Max:" . $max . "<br/><br/>";
            $rows[] = [$device->__toString(), $min, $avg, $max];
        }

        $table = new Table($output);
        $table
                ->setHeaders(['Device', 'Min volt', 'Avg volt', 'Max volt'])
                ->setRows($rows)
                
        ;
        $table->render();

        $sendmail = ($input->getOption('sendmail') !== false);
        if ($sendmail) {

            $recipient = getenv("mailer_user");

            $message = (new \Swift_Message("Energy report from " . $date->format("d/m/Y H:i:s") . " to " . (new \DateTime)->format("d/m/Y H:i:s")))
                    ->setFrom("voltwatcheralert@manzolo.it")
                    ->setTo($recipient)
                    ->setBody($body, 'text/html')
                    // you can remove the following code if you don't define a text version for your emails
                    ->addPart($body, 'text/plain')
            ;

            $this->mailer->send($message);
        }

        $output->writeln('<info>Done</info>');
    }
}
