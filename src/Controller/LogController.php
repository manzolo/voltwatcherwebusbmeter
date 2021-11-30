<?php

namespace App\Controller;

use App\Entity\Log;
use Cdf\BiCoreBundle\Controller\FiController;
use Cdf\BiCoreBundle\Utils\Entity\EntityUtils;
use Cdf\BiCoreBundle\Utils\Tabella\ParametriTabella;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Log controller.
 */
class LogController extends FiController
{
    private $chartdifftime = '-3 days';

    //private $batterystatus = array(
    //    "perc"=>100,"volt"=>12.92,"perc"=>90,"volt"=>12.80,"perc"=>80,"volt"=>12.66,"perc"=>70,"volt"=>12.52,"perc"=>60,"volt"=>12.38,"perc"=>50,"volt"=>12.32,
    //    "perc"=>40,"volt"=>12.06,"perc"=>30,"volt"=>12.00,"perc"=>80,"volt"=>12.66,"perc"=>70,"volt"=>12.52,"perc"=>60,"volt"=>12.38,"perc"=>10,"volt"=>11.50);

    /**
     * Matches / exactly.
     *
     * @Route("/Log", name="Log_container")
     */
    public function index(Request $request, Packages $assetsmanager)
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
            $controller.'.id' => [
                'nometabella' => $controller,
                'nomecampo' => $controller.'.id',
                'etichetta' => 'Id',
                'ordine' => 5,
                'larghezza' => 50,
                'escluso' => true,
            ],
            $controller.'.data' => [
                'nometabella' => $controller,
                'nomecampo' => $controller.'.data',
                'etichetta' => 'Rilevazione',
                'ordine' => 10,
                'larghezza' => 200,
                'escluso' => false,
            ],
            $controller.'.volt' => [
                'nometabella' => $controller,
                'nomecampo' => $controller.'.volt',
                'etichetta' => 'Volt',
                'ordine' => 20,
                'larghezza' => 80,
                'escluso' => false,
            ],
            $controller.'.device' => [
                'nometabella' => $controller,
                'nomecampo' => $controller.'.device',
                'etichetta' => 'Rilevatore',
                'ordine' => 30,
                'larghezza' => 50,
                'escluso' => false,
            ],
            $controller.'.detectorperc' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.detectorperc',
                'etichetta' => 'Temperatura',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.temp' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.temp',
                'etichetta' => 'Temperatura',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.longitude' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.longitude',
                'etichetta' => 'Longitudine',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.latitude' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.latitude',
                'etichetta' => 'Latitudine',
                'ordine' => 100,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.weather' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.weather',
                'etichetta' => 'Weather',
                'ordine' => 200,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.externaltemp' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.externaltemp',
                'etichetta' => 'External temp',
                'ordine' => 210,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.location' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.location',
                'etichetta' => 'Location',
                'ordine' => 220,
                'larghezza' => 50,
                'escluso' => true,
            ],
            $controller.'.cloudiness' => [
                'nometabella' => $controller,
                'nomecampo' => 'Log.cloudiness',
                'etichetta' => 'Cloudiness %',
                'ordine' => 230,
                'larghezza' => 30,
                'escluso' => true,
            ],
            $controller.'.weathericon' => [
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
        $entityutils = new EntityUtils($this->get('doctrine')->getManager());
        $tablenamefromentity = $entityutils->getTableFromEntity($entityclass);
        $colonneordinamento = [$tablenamefromentity.'.data' => 'DESC', $tablenamefromentity.'.device_id' => 'ASC'];
        $parametritabella = ['em' => ParametriTabella::setParameter('default'),
            'tablename' => ParametriTabella::setParameter($tablenamefromentity),
            'nomecontroller' => ParametriTabella::setParameter($controller),
            'bundle' => ParametriTabella::setParameter($bundle),
            'entityname' => ParametriTabella::setParameter($entityclassnotation),
            'entityclass' => ParametriTabella::setParameter($entityclass),
            'formclass' => ParametriTabella::setParameter($formclass),
            'modellocolonne' => ParametriTabella::setParameter(json_encode($modellocolonne)),
            'permessi' => ParametriTabella::setParameter(json_encode($this->getPermessi()->toJson($controller))),
            'urltabella' => ParametriTabella::setParameter($assetsmanager->getUrl('/').$controller.'/'.'tabella'),
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
            'graficodal' => ParametriTabella::setParameter((new \DateTime())->modify($this->chartdifftime)->format('d/m/Y H:i')),
        ];

        /* chart */
        $qb = $this->em->createQueryBuilder('d')
                ->select('d')
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        $charts = $this->getCharts($devicesrows);

        return $this->render($crudtemplate, ['charts' => $charts, 'parametritabella' => $parametritabella]);
    }

    public function tabella(Request $request)
    {
        if (!$this->permessi->canRead($this->getController())) {
            throw new AccessDeniedException('Non si hanno i permessi per visualizzare questo contenuto');
        }

        $parametripassati = array_merge($request->get('parametri'), ['user' => $this->getUser()]);
        $parametriform = isset($parametripassati['parametriform']) ?
                json_decode(ParametriTabella::getParameter($parametripassati['parametriform']), true) : [];
        $classbundle = ParametriTabella::getParameter($parametripassati['entityclass']);
        $formbundle = ParametriTabella::getParameter($parametripassati['formclass']);
        $formType = $formbundle.'Type';

        $entity = new $classbundle();
        $controller = ParametriTabella::getParameter($parametripassati['nomecontroller']);
        $form = $this->createForm($formType, $entity, ['attr' => ['id' => 'formdati'.$controller], 'action' => $this->generateUrl($controller.'_new'), 'parametriform' => $parametriform]);
        
        $qb = $this->em->createQueryBuilder('d')
                ->select('d')
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        $charts = $this->getCharts($devicesrows);
        $parametri = array_merge($parametripassati, $this->getParametriTabella($parametripassati));
        $parametri['charts'] = $charts;
        $parametri['form'] = $form->createView();
        $templateobj = $this->getTabellaTemplateInformations($controller);
        $parametri['templatelocation'] = $templateobj['path'];

        return $this->render($templateobj['template'], ['parametri' => $parametri]);
    }

    private function getCharts($devices)
    {
        /* chart */
        $charts = [];

        $date = (new \DateTime())->modify($this->chartdifftime);
        $qb = $this->em->createQueryBuilder('d')
                ->select('d')
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();

        foreach ($devicesrows as $device) {
            /* chart */
            $qb = $this->em->createQueryBuilder('j')
                    ->select('j')
                    ->from('App:Journal', 'j')
                    ->where('j.device = :device')
                    ->andWhere('j.dal <= :ora')
                    ->andWhere('j.volt is not null')
                    ->setParameter('device', $device->getId())
                    ->setParameter('ora', new \DateTime())
                    ->getQuery();

            $journalrows = $qb->getResult();

            $dati = [];
            $dati[] = ['Data', 'Volts'/* , 'Temps' */, 'Avg'];

            foreach ($journalrows as $journalrows) {
                $dati[] = [$journalrows->getDatarilevazione(), floatval($journalrows->getVolt()), round(floatval($journalrows->getAvgvolt()), 2)];
            }
            if (1 == count($dati)) {
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

        return $charts;
    }

    private function searchInArray($array, $search_list)
    {
        // Create the result array
        $result = [];

        // Iterate over each array element
        foreach ($array as $key => $value) {
            // Iterate over each search condition
            foreach ($search_list as $k => $v) {
                // If the array element does not meet
                // the search condition then continue
                // to the next element
                if (!isset($value[$k]) || $value[$k] != $v) {
                    // Skip two loops
                    continue 2;
                }
            }
            // Append array element's key to the
            //result array
            $result[] = $value;
        }

        // Return result
        return $result;
    }
}
