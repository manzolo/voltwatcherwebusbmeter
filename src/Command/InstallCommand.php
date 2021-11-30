<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Cdf\BiCoreBundle\Entity\Menuapplicazione;

class InstallCommand extends Command
{
    protected static $defaultName = 'voltwatcher:install';
    private $em;

    protected function configure()
    {
        $this
                ->setDescription('Install')
                ->setHelp('Install')
        ;
    }

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->em;

        //Si controlla se è già presente l'utente
        $entiobj = $this->em->getRepository(Menuapplicazione::class)->findBy(["nome"=>"Gestione"]);
        if (count($entiobj) > 0) {
            $output->writeln("<error>Envirnoment already installed</error>");
            return -1;
        }

        $newMenuapplicazioneMain = new Menuapplicazione();
        $newMenuapplicazioneMain->setNome("Gestione");
        $newMenuapplicazioneMain->setOrdine(10);
        $newMenuapplicazioneMain->setAttivo(true);
        $this->em->persist($newMenuapplicazioneMain);
        $this->em->flush();       

        $ordine = 0;
        
        $ordine = $ordine + 10;
        $newMenuapplicazione = new Menuapplicazione();
        $newMenuapplicazione->setPadre($newMenuapplicazioneMain->getId());
        $newMenuapplicazione->setNome("Log");
        $newMenuapplicazione->setPercorso("Log_container");
        $newMenuapplicazione->setOrdine($ordine);
        $newMenuapplicazione->setAttivo(true);
        $this->em->persist($newMenuapplicazione);
        $this->em->flush();       

        $ordine = $ordine + 10;
        $newMenuapplicazione = new Menuapplicazione();
        $newMenuapplicazione->setPadre($newMenuapplicazioneMain->getId());
        $newMenuapplicazione->setNome("Report");
        $newMenuapplicazione->setPercorso("Report_container");
        $newMenuapplicazione->setOrdine($ordine);
        $newMenuapplicazione->setAttivo(true);
        $this->em->persist($newMenuapplicazione);
        $this->em->flush();       

        $ordine = $ordine + 10;
        $newMenuapplicazione = new Menuapplicazione();
        $newMenuapplicazione->setPadre($newMenuapplicazioneMain->getId());
        $newMenuapplicazione->setNome("Devices");
        $newMenuapplicazione->setPercorso("Devices_container");
        $newMenuapplicazione->setOrdine($ordine);
        $newMenuapplicazione->setAttivo(true);
        $this->em->persist($newMenuapplicazione);
        $this->em->flush();       

        $ordine = $ordine + 10;
        $newMenuapplicazione = new Menuapplicazione();
        $newMenuapplicazione->setPadre($newMenuapplicazioneMain->getId());
        $newMenuapplicazione->setNome("App Settings ");
        $newMenuapplicazione->setPercorso("Settings_container");
        $newMenuapplicazione->setOrdine($ordine);
        $newMenuapplicazione->setAttivo(true);
        $this->em->persist($newMenuapplicazione);
        $this->em->flush();       

        $ordine = $ordine + 10;
        $newMenuapplicazione = new Menuapplicazione();
        $newMenuapplicazione->setPadre($newMenuapplicazioneMain->getId());
        $newMenuapplicazione->setNome("Journal");
        $newMenuapplicazione->setPercorso("Journal_container");
        $newMenuapplicazione->setOrdine($ordine);
        $newMenuapplicazione->setAttivo(true);
        $this->em->persist($newMenuapplicazione);
        $this->em->flush();       

        $output->writeln('<info>Done</info>');
        return 0;
    }
}
