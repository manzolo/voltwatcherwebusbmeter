<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use \Doctrine\ORM\EntityManagerInterface;
use \Twig\Environment;
use \DateTime;

class RiepilogoCommand extends Command
{

    private $journaldiffdays = '-7 days';
    protected static $defaultName = 'voltwatcher:weeklyreport';
    private $em;
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
    public function __construct(
        EntityManagerInterface $em,
        MailerInterface $mailer,
        Environment $templating,
        ParameterBagInterface $params
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->params = $params;
        // you *must* call the parent constructor
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = (new DateTime())->modify($this->journaldiffdays);
        $em = $this->em;

        /* weekly */
        $qbWeek = $em->createQueryBuilder('l')
                ->select('d.id as deviceid, d.address as device, d.name as devicename, AVG(l.volt) avgvolt, MIN(l.volt) minvolt, MAX(l.volt) maxvolt')
                ->from('App:Log', 'l')
                ->leftJoin('l.device', 'd')
                ->andWhere('l.data >= :data')
                ->setParameter('data', $date)
                ->groupBy('l.device')
                ->getQuery();

        $riepilogorows = $qbWeek->getResult();
        $bodyWeek = 'Weekly report<br/><br/>';
        $rowsWeek = [];
        foreach ($riepilogorows as $row) {
            $deviceid = $row['deviceid'];
            $avg = round($row['avgvolt'], 2);
            $min = round($row['minvolt'], 2);
            $max = round($row['maxvolt'], 2);
            $device = $em->getRepository('App:Device')->find($deviceid);
            $bodyWeek = $bodyWeek . $device->__toString() . ' Min:' . $min . ' Avg:' . $avg . ' Max:' . $max . '<br/><br/>';
            $rowsWeek[] = [$device->__toString(), $min, $avg, $max];
        }

        $tableWeek = new Table($output);
        $tableWeek
                ->setHeaders(['Device', 'Min volt', 'Avg volt', 'Max volt'])
                ->setRows($rowsWeek)

        ;
        $tableWeek->render();

        /* dayly */
        $qbDaily = $em->createQueryBuilder('l')
                ->select('d.id as deviceid, '
                        . 'd.address as device, '
                        . 'd.name as devicename, '
                        . 'SUBSTRING(l.data,1,10) AS day, '
                        . 'AVG(l.volt) avgvolt, '
                        . 'MIN(l.volt) minvolt, '
                        . 'MAX(l.volt) maxvolt')
                ->from('App:Log', 'l')
                ->leftJoin('l.device', 'd')
                ->andWhere('l.data >= :data')
                ->setParameter('data', $date)
                ->groupBy('l.device, day')
                ->orderBy('l.device', 'ASC')
                ->addOrderBy('day', 'DESC')
                ->getQuery();

        $riepilogodayrows = $qbDaily->getResult();
        $bodyDaily = '<br/><br/><br/><br/>Dayly report<br/><br/>';
        $rowsDaily = [];
        foreach ($riepilogodayrows as $row) {
            $deviceid = $row['deviceid'];
            $day = \DateTime::createFromFormat('Y-m-d', $row['day']);
            $avg = round($row['avgvolt'], 2);
            $min = round($row['minvolt'], 2);
            $max = round($row['maxvolt'], 2);
            $device = $em->getRepository('App:Device')->find($deviceid);
            $bodyDaily = $bodyDaily . $device->__toString() . ' Min:' . $min . ' Avg:' . $avg . ' Max:' . $max . '<br/><br/>';
            $rowsDaily[] = [$day->format('d/m/Y'), $device->__toString(), $min, $avg, $max];
        }

        $table = new Table($output);
        $table
                ->setHeaders(['Day', 'Device', 'Min volt', 'Avg volt', 'Max volt'])
                ->setRows($rowsDaily)

        ;
        $table->render();
        $sendmail = (false !== $input->getOption('sendmail'));
        if ($sendmail) {
            $recipient = $this->params->get('mailer_user');
            $output->writeln('<info>send mail to ' . $recipient . '</info>');

            $email = (new Email())
                    ->from($recipient)
                    ->to($recipient)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Energy report from ' . $date->format('d/m/Y H:i:s') . ' to ' . (new DateTime())->format('d/m/Y H:i:s'))
                    ->text(
                        $this->templating->render(
                                    // templates/emails/registration.html.twig
                            'Report/index.html.twig',
                            ['rows' => $riepilogodayrows, 'weeklyrows' => $riepilogorows]
                        ),
                        'text/html'
                    )
                    ->html(
                        $this->templating->render(
                            // templates/emails/registration.html.twig
                            'Report/index.html.twig',
                            ['rows' => $riepilogodayrows, 'weeklyrows' => $riepilogorows]
                        ),
                        'text/html'
                    )
            ;

            $this->mailer->send($email);
        }

        $output->writeln('<info>Done</info>');
        return 0;
    }
}
