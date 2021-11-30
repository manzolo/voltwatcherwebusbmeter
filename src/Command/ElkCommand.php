<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ElkCommand extends Command
{

    protected static $defaultName = 'voltwatcher:elk';
    private $client;
    private $em;

    protected function configure()
    {
        $this
                ->setDescription('Elk creation')
                ->setHelp('Elk')
        ;
    }
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, HttpClientInterface $client)
    {
        $this->em = $em;
        $this->client = $client;
        // you *must* call the parent constructor
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->em;

        $riepilogorows = $em->createQueryBuilder('l')
                ->select("l")
                ->from('App:Log', 'l')
                //->andWhere('l.data >= :data')
                //->setParameter("data", $date)
                //->setMaxResults(1)
                ->orderBy("l.data", "desc")
                ->getQuery()
                ->getResult();

        /* @var $row \App\Entity\Log  */
        foreach ($riepilogorows as $row) {
            $data = $row->getData();
            //$avgdeviceid = $row['deviceid'];
            $device = $row->getDevice()->getAddress();
            //$avgdevicename = $row['devicename'];
            $volt = (float) $row->getVolt();
            $temp = (float) $row->getTemp();
            $exttemp = (float) $row->getExternaltemp();
            $detectorperc = (int) $row->getDetectorperc();
            $whether = $row->getWeather();

            $informations = json_encode(['address' => $device,
                'data' => $data->format("c"),
                'detectorperc' => $detectorperc,
                'volt' => $volt,
                'temp' => $temp,
                'externaltemp' => $exttemp,
                'whether' => $whether,
                "location" => ["lon" => $row->getLongitude(), "lat" => $row->getLatitude()]
            ]);

            $response = $this->client->request(
                'POST',
                'http://localhst:9200/voltwatcher/_doc/' . $row->getId(),
                [
                        'headers' => [
                            'Content-Type' => 'application/json; charset=utf-8',
                            'Accept' => 'application/json'
                        ],
                        'body' => $informations
                    ]
            );

            //$statusCode = $response->getStatusCode();
            // $statusCode = 200
            //$contentType = $response->getHeaders()['content-type'][0];
            // $contentType = 'application/json'
            echo $response->getContent();
            //echo $content;
            // $content = '{"id":521583, "name":"symfony-docs", ...}'
            //$content = $response->toArray();
        }

        $output->writeln('<info>Done</info>');
        return 0;
    }
}
