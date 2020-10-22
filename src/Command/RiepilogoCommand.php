<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RiepilogoCommand extends Command
{

    private $journaldiffdays = '-7 days';
    protected static $defaultName = 'voltwatcher:weeklyreport';
    private $em;
    private $logger;
    private $mailer;
    private $templating;
    private $params;

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
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, LoggerInterface $logger, MailerInterface $mailer, \Twig\Environment $templating, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->params = $params;
        // you *must* call the parent constructor
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = (new \DateTime())->modify($this->journaldiffdays);
        $em = $this->em;

        /* weekly */
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

        /* dayly */
        $qb = $em->createQueryBuilder('l')
                ->select("d.id as deviceid, d.address as device, d.name as devicename, SUBSTRING(l.data,1,10) AS day, AVG(l.volt) avgvolt, MIN(l.volt) minvolt, MAX(l.volt) maxvolt")
                ->from('App:Log', 'l')
                ->leftJoin('l.device', 'd')
                ->andWhere('l.data >= :data')
                ->setParameter("data", $date)
                ->groupBy('l.device, day')
                ->orderBy('l.device', "ASC")
                ->addOrderBy('day', "DESC")
                ->getQuery();

        $riepilogodayrows = $qb->getResult();
        $body = "<br/><br/><br/><br/>Dayly report<br/><br/>";
        $rows = [];
        foreach ($riepilogodayrows as $row) {
            $deviceid = $row["deviceid"];
            $day = \DateTime::createFromFormat("Y-m-d", $row["day"]);
            $avg = round($row["avgvolt"], 2);
            $min = round($row["minvolt"], 2);
            $max = round($row["maxvolt"], 2);
            $device = $em->getRepository("App:Device")->find($deviceid);
            $body = $body . $device->__toString() . " Min:" . $min . " Avg:" . $avg . " Max:" . $max . "<br/><br/>";
            $rows[] = [$day->format("d/m/Y"), $device->__toString(), $min, $avg, $max];
        }

        $table = new Table($output);
        $table
                ->setHeaders(['Day', 'Device', 'Min volt', 'Avg volt', 'Max volt'])
                ->setRows($rows)

        ;
        $table->render();
        $sendmail = ($input->getOption('sendmail') !== false);
        if ($sendmail) {
            $recipient = $this->params->get("mailer_user");
            $output->writeln('<info>send mail to ' . $recipient . '</info>');
            
            $email = (new Email())
                    ->from('voltwatcheralert@manzolo.it')
                    ->to($recipient)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject("Energy report from " . $date->format("d/m/Y H:i:s") . " to " . (new \DateTime)->format("d/m/Y H:i:s"))
                    ->setBody($this->templating->render(
                                    // templates/emails/registration.html.twig
                                    'Report/index.html.twig',
                                    ['rows' => $riepilogodayrows, "weeklyrows" => $riepilogorows]
                            ),
                            'text/html');
            
            $this->mailer->send($email);
        }

        $output->writeln('<info>Done</info>');
    }
}
