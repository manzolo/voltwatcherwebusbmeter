<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Device;
use App\Entity\Journal;
use App\Service\Battery;
use Cdf\BiCoreBundle\Controller\FiController;
use Cdf\BiCoreBundle\Utils\Entity\EntityUtils;
use Cdf\BiCoreBundle\Utils\Tabella\ParametriTabella;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * Log controller.
 */
/**
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LogController extends FiController
{

    private string $chartdifftime = '-3 days';

    //private $batterystatus = array(
    //    "perc"=>100,"volt"=>12.92,"perc"=>90,"volt"=>12.80,"perc"=>80,"volt"=>12.66,"perc"=>70,"volt"=>12.52,"perc"=>60,"volt"=>12.38,"perc"=>50,"volt"=>12.32,
    //    "perc"=>40,"volt"=>12.06,"perc"=>30,"volt"=>12.00,"perc"=>80,"volt"=>12.66,"perc"=>70,"volt"=>12.52,"perc"=>60,"volt"=>12.38,"perc"=>10,"volt"=>11.50);

    /**
     * Matches / exactly.
     *
     * @Route("/Log", name="Log_container")
     */
    public function index(Request $request, Packages $assetsmanager): Response
    {
        $bundle = $this->getBundle();
        $controller = $this->getController();
        $idpassato = $request->get('id');

        if (!$this->getPermessi()->canRead($this->getController())) {
            throw new AccessDeniedException('Non si hanno i permessi per visualizzare questo contenuto');
        }
        $crudtemplate = $this->getCrudTemplate($bundle, $controller, $this->getThisFunctionName());

        $entityclassnotation = $this->getEntityClassNotation();
        $entityclass = $this->getEntityClassName();

        $formclass = str_replace('Entity', 'Form', $entityclass);

        $modellocolonne = [
            $controller . '.id' => [
                'nometabella' => $controller,
                'nomecampo' => $controller . '.id',
                'etichetta' => 'Id',
                'ordine' => 5,
                'larghezza' => 50,
                'escluso' => true,
            ],
            $controller . '.data' => [
                'nometabella' => $controller,
                'nomecampo' => $controller . '.data',
                'etichetta' => 'Rilevazione',
                'ordine' => 10,
                'larghezza' => 200,
                'escluso' => false,
            ],
            $controller . '.volt' => [
                'nometabella' => $controller,
                'nomecampo' => $controller . '.volt',
                'etichetta' => 'Volt',
                'ordine' => 20,
                'larghezza' => 80,
                'escluso' => false,
            ],
            $controller . '.device' => [
                'nometabella' => $controller,
                'nomecampo' => $controller . '.device',
                'etichetta' => 'Rilevatore',
                'ordine' => 30,
                'larghezza' => 50,
                'escluso' => false,
            ],
            $controller . '.detectorperc' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.detectorperc',
                'etichetta' => 'Temperatura',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.temp' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.temp',
                'etichetta' => 'Temperatura',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.longitude' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.longitude',
                'etichetta' => 'Longitudine',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.latitude' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.latitude',
                'etichetta' => 'Latitudine',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.weather' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.weather',
                'etichetta' => 'Weather',
                'ordine' => 200,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.externaltemp' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.externaltemp',
                'etichetta' => 'External temp',
                'ordine' => 210,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.location' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.location',
                'etichetta' => 'Location',
                'ordine' => 220,
                'larghezza' => 50,
                'escluso' => true,
            ],
            $controller . '.cloudiness' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.cloudiness',
                'etichetta' => 'Cloudiness %',
                'ordine' => 230,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller . '.weathericon' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.weathericon',
                'etichetta' => 'Weather Icon',
                'ordine' => 240,
                'larghezza' => 30,
                'escluso' => true,
            ],
        ];

        $filtri = [];
        $prefiltri = [];
        $entityutils = new EntityUtils($this->em);
        $tablenamefromentity = $entityutils->getTableFromEntity($entityclass);
        $colonneordinamento = [$tablenamefromentity . '.data' => 'DESC', $tablenamefromentity . '.device_id' => 'ASC'];
        $parametritabella = ['em' => ParametriTabella::setParameter('default'),
            'tablename' => ParametriTabella::setParameter($tablenamefromentity),
            'nomecontroller' => ParametriTabella::setParameter($controller),
            'bundle' => ParametriTabella::setParameter($bundle),
            'entityname' => ParametriTabella::setParameter($entityclassnotation),
            'entityclass' => ParametriTabella::setParameter($entityclass),
            'formclass' => ParametriTabella::setParameter($formclass),
            'modellocolonne' => ParametriTabella::setParameter(json_encode($modellocolonne)),
            'permessi' => ParametriTabella::setParameter(json_encode($this->getPermessi()->toJson($controller))),
            'urltabella' => ParametriTabella::setParameter($assetsmanager->getUrl('/') . $controller . '/' . 'tabella'),
            'baseurl' => ParametriTabella::setParameter($assetsmanager->getUrl('/')),
            'idpassato' => ParametriTabella::setParameter($idpassato),
            'titolotabella' => ParametriTabella::setParameter($controller),
            'multiselezione' => ParametriTabella::setParameter('0'),
            'editinline' => ParametriTabella::setParameter('0'),
            'paginacorrente' => ParametriTabella::setParameter('1'),
            'paginetotali' => ParametriTabella::setParameter(''),
            'righetotali' => ParametriTabella::setParameter('0'),
            'righeperpagina' => ParametriTabella::setParameter('8'),
            'estraituttirecords' => ParametriTabella::setParameter('0'),
            'colonneordinamento' => ParametriTabella::setParameter(json_encode($colonneordinamento)),
            'filtri' => ParametriTabella::setParameter(json_encode($filtri)),
            'prefiltri' => ParametriTabella::setParameter(json_encode($prefiltri)),
            'traduzionefiltri' => ParametriTabella::setParameter(''),
            'graficodal' => ParametriTabella::setParameter((new DateTime())->modify($this->chartdifftime)->format('d/m/Y H:i')),
        ];

        /* chart */
        $qb = $this->em->createQueryBuilder()
                ->select('d')
                ->from(Device::class, 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        $charts = $this->getCharts($devicesrows);

        return $this->render($crudtemplate, ['charts' => $charts, 'parametritabella' => $parametritabella]);
    }

    public function tabella(Request $request, ManagerRegistry $doctrine): Response
    {
        if (!$this->permessi->canRead($this->getController())) {
            throw new AccessDeniedException('Non si hanno i permessi per visualizzare questo contenuto');
        }

        $parametripassati = array_merge($request->get('parametri'), ['user' => $this->getUser()]);
        $parametriform = isset($parametripassati['parametriform']) ?
                json_decode(ParametriTabella::getParameter($parametripassati['parametriform']), true) : [];
        $classbundle = ParametriTabella::getParameter($parametripassati['entityclass']);
        $formbundle = ParametriTabella::getParameter($parametripassati['formclass']);
        $formType = $formbundle . 'Type';

        $entity = new $classbundle();
        $controller = ParametriTabella::getParameter($parametripassati['nomecontroller']);

        $formParameters = ['attr' => ['id' => 'formdati' . $controller],
            'action' => $this->generateUrl($controller . '_new'),
            'parametriform' => $parametriform
        ];
        $form = $this->createForm($formType, $entity, $formParameters);

        $qb = $this->em->createQueryBuilder()
                ->select('d')
                ->from(Device::class, 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        $charts = $this->getCharts($devicesrows);
        $parametri = array_merge($parametripassati, $this->getParametriTabella($doctrine, $parametripassati));
        $parametri['charts'] = $charts;
        $parametri['form'] = $form->createView();
        $templateobj = $this->getTabellaTemplateInformations($controller);
        $parametri['templatelocation'] = $templateobj['path'];

        return $this->render($templateobj['template'], ['parametri' => $parametri]);
    }

    /**
      @param array<AreaChart> $devices Charts
      @return array<AreaChart> Charts
     */
    private function getCharts(array $devices): array
    {
        /* chart */
        $charts = [];

        (new DateTime())->modify($this->chartdifftime);
        $qb = $this->em->createQueryBuilder()
                ->select('d')
                ->from(Device::class, 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();

        foreach ($devicesrows as $device) {
            /* chart */
            $qb = $this->em->createQueryBuilder()
                    ->select('j')
                    ->from(Journal::class, 'j')
                    ->where('j.device = :device')
                    ->andWhere('j.dal <= :ora')
                    ->andWhere('j.volt is not null')
                    ->setParameter('device', $device->getId())
                    ->setParameter('ora', new DateTime())
                    ->getQuery();

            $journalrows = $qb->getResult();

            $dati = [];
            $dati[] = ['Data', 'Volts'/* , 'Temps' */, 'Avg'];

            foreach ($journalrows as $journalrows) {
                $dati[] = [$journalrows->getDatarilevazione(), floatval($journalrows->getVolt()), round(floatval($journalrows->getAvgvolt()), 2)];
            }
            if (1 == count($dati)) {
                $dati[] = [new DateTime(), 0, 0];
            }
            $chart = new AreaChart();
            $chart->getData()->setArrayToDataTable($dati);
            $chart->setElementID($device->getId());
            $deviceName = $device->getName() ? $device->getName() : $device->getAddress();
            $chart->getOptions()->setTitle($deviceName);
            /** @phpstan-ignore-next-line */
            $chart->getOptions()->setSeries([['axis' => 'Volts'], ['axis' => 'AvgVolts']/* , ['axis' => 'Temps'] */])
            //->setAxes(['y' => ['Volts' => ['label' => 'Volts'],
            //'AvgVolts' => ['label' => 'Average Volts']/* , 'Temps' => ['label' => 'Temps (Celsius)'] */]])
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

    /**
     * Matches / exactly.
     *
     * @Route("/Log/Last/{device}", name="Log_last", options={"expose"=true} )
     */
    public function getLastLog(Request $request, string $device, EntityManagerInterface $em, Battery $battery): JsonResponse
    {
        $ret = null;
        $qb = $em->createQueryBuilder()
                ->select('l')
                ->from(Log::class, 'l')
                ->where('l.device = :device')
                ->setParameter(':device', $device)
                ->orderBy('l.data', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult()
        ;

        if (count($qb) > 0) {
            $mylog = $qb[0];
            $ret = [
                "id" => $mylog->getId(),
                "address" => $mylog->getDevice()->getAddress(),
                "devicename" => $mylog->getDevice()->getName(),
                "date" => $mylog->getData()->format("c"),
                "volt" => $mylog->getVolt(),
                "temp" => $mylog->getTemp(),
                "batteryperc" => $battery->batteryLevel($mylog->getVolt()),
                "detectorperc" => $mylog->getDetectorperc(),
                "latitude" => $mylog->getLatitude(),
                "longitude" => $mylog->getLongitude(),
                "weather" => $mylog->getWeather(),
                "externaltemp" => $mylog->getExternaltemp(),
                "location" => $mylog->getLocation(),
                "cloudiness" => $mylog->getCloudiness(),
                "weathericon" => $mylog->getWeathericon(),
            ];
        }

        return new JsonResponse($ret);
    }

    /**
     * Matches / exactly.
     *
     * @Route("/Log/LastWeek/{device}", name="Log_last_week", options={"expose"=true} )
     */
    public function getLastWeekLog(Request $request, string $device, EntityManagerInterface $em, Battery $battery): JsonResponse
    {
        $ret = [];

        $qb = $em->createQueryBuilder()
                ->select('l')
                ->from(Log::class, 'l')
                ->where('l.device = :device')
                ->setParameter(':device', $device)
                ->orderBy('l.data', 'DESC')
                ->setMaxResults(1)
                ->getQuery();
        $resultrows = $qb->getResult();
        if (1 == count($resultrows)) {
            $infodevice = $resultrows[0];
            $hour = substr($infodevice->getData()->format('H:i:s'), 0, 4);
            $lastweek = clone $infodevice->getData();
            $lastweeks = $em->createQueryBuilder()
                    ->select('l')
                    ->from(Log::class, 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.data < :data')
                    ->andWhere('l.data > :datachk')
                    ->andWhere('substring(l.data,12,4) = :ora')
                    ->setParameter(':device', $device)
                    ->setParameter(':data', $infodevice->getData())
                    ->setParameter(':datachk', $lastweek->modify('- 7 days'))
                    ->setParameter(':ora', $hour)
                    ->orderBy('l.data', 'DESC')
                    ->getQuery()
                    ->getResult();
            foreach ($lastweeks as $mylog) {
                $ret[] = [
                    "id" => $mylog->getId(),
                    "address" => $mylog->getDevice()->getAddress(),
                    "devicename" => $mylog->getDevice()->getName(),
                    "date" => $mylog->getData()->format("c"),
                    "volt" => $mylog->getVolt(),
                    "temp" => $mylog->getTemp(),
                    "batteryperc" => $battery->batteryLevel($mylog->getVolt()),
                    "detectorperc" => $mylog->getDetectorperc(),
                    "latitude" => $mylog->getLatitude(),
                    "longitude" => $mylog->getLongitude(),
                    "weather" => $mylog->getWeather(),
                    "externaltemp" => $mylog->getExternaltemp(),
                    "location" => $mylog->getLocation(),
                    "cloudiness" => $mylog->getCloudiness(),
                    "weathericon" => $mylog->getWeathericon(),
                ];
            }
        }


        return new JsonResponse($ret);
    }
}
