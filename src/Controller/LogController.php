<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Cdf\BiCoreBundle\Controller\FiController;
use Cdf\BiCoreBundle\Utils\Tabella\ParametriTabella;
use Cdf\BiCoreBundle\Utils\Entity\EntityUtils;
use App\Entity\Log;
use App\Form\LogType;

/**
 * Log controller.
 *
 */
class LogController extends FiController
{
    /**
     * Lists all tables entities.
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

        $modellocolonne = array(
            $controller . ".id" => array(
                "nometabella" => $controller,
                "nomecampo" => $controller . ".id",
                "etichetta" => "Id",
                "ordine" => 5,
                "larghezza" => 50,
                "escluso" => true
            ),
            $controller . ".data" => array(
                "nometabella" => $controller,
                "nomecampo" => $controller . ".data",
                "etichetta" => "Rilevazione",
                "ordine" => 20,
                "larghezza" => 200,
                "escluso" => false
            ),
            $controller . ".volt" => array(
                "nometabella" => $controller,
                "nomecampo" => $controller . ".volt",
                "etichetta" => "Volt",
                "ordine" => 30,
                "larghezza" => 100,
                "escluso" => false
            ),
            $controller . ".device" => array(
                "nometabella" => $controller,
                "nomecampo" => $controller . ".device",
                "etichetta" => "Rilevatore",
                "ordine" => 10,
                "larghezza" => 100,
                "escluso" => false
            ),
            $controller . ".temp" => array(
                "nometabella" => $controller,
                "nomecampo" => "Log.temp",
                "etichetta" => "Temperatura",
                "ordine" => 100,
                "larghezza" => 100,
                "escluso" => false
            ),
        );

        $filtri = array();
        $prefiltri = array();
        $entityutils = new EntityUtils($this->get('doctrine')->getManager());
        $tablenamefromentity = $entityutils->getTableFromEntity($entityclass);
        $colonneordinamento = array($tablenamefromentity . '.id' => 'DESC');
        $parametritabella = array('em' => ParametriTabella::setParameter('default'),
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
            'traduzionefiltri' => ParametriTabella::setParameter('')
        );

        $charts = array();

        /* chart */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('d')
                ->select("d")
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        foreach ($devicesrows as $device) {
            /* chart */
            $qb = $em->createQueryBuilder('l')
                    ->select("l")
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.temp != 0')
                    ->setParameter("device", $device->getId())
                    ->orderBy('l.data', "DESC")
                    ->getQuery();

            $dettagliorows = $qb->getResult();
            $dati = array();
            $dati[] = ['Data', 'Volts', 'Temps'];
            foreach ($dettagliorows as $row) {
                $dati[] = [$row->getData(), floatval($row->getVolt()), floatval($row->getTemp())];
                //$dati[] = [$row->getData(), floatval($row->getVolt())];
            }
            $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
            $chart->getData()->setArrayToDataTable($dati);

            $chart->getOptions()->getChart()
                    ->setTitle($device->getName());
            $chart->getOptions()
                    ->setSeries([['axis' => 'Volts'], ['axis' => 'Temps']])
                    ->setAxes(['y' => ['Volts' => ['label' => 'Volts'], 'Temps' => ['label' => 'Temps (Celsius)']]])
            ;
            $chart->setElementID($device->getId());
            $chart->getOptions()->getLegend()->setPosition('none');
            $charts[] = $chart;
            /* chart */
        }

        return $this->render($crudtemplate, array('charts' => $charts, 'parametritabella' => $parametritabella));
    }
    public function tabella(Request $request)
    {
        if (!$this->permessi->canRead($this->getController())) {
            throw new AccessDeniedException('Non si hanno i permessi per visualizzare questo contenuto');
        }

        $parametripassati = array_merge($request->get('parametri'), array('user' => $this->getUser()));
        $parametriform = isset($parametripassati['parametriform']) ?
                json_decode(ParametriTabella::getParameter($parametripassati['parametriform']), true) : array();
        $classbundle = ParametriTabella::getParameter($parametripassati['entityclass']);
        $formbundle = ParametriTabella::getParameter($parametripassati['formclass']);
        $formType = $formbundle . 'Type';

        $entity = new $classbundle();
        $controller = ParametriTabella::getParameter($parametripassati['nomecontroller']);
        $form = $this->createForm(
                $formType,
                $entity,
                array('attr' => array(
                        'id' => 'formdati' . $controller,
                    ),
                    'action' => $this->generateUrl($controller . '_new'),
                    'parametriform' => $parametriform,
                )
        );

        /* chart */
        $charts = array();

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('d')
                ->select("d")
                ->from('App:Device', 'd')
                ->getQuery();

        $devicesrows = $qb->getResult();
        foreach ($devicesrows as $device) {
            /* chart */
            $qb = $em->createQueryBuilder('l')
                    ->select("l")
                    ->from('App:Log', 'l')
                    ->where('l.device = :device')
                    ->andWhere('l.temp != 0')
                    ->setParameter("device", $device->getId())
                    ->orderBy('l.data', "DESC")
                    ->getQuery();

            $dettagliorows = $qb->getResult();
            $dati = array();
            $dati[] = ['Data', 'Volts', 'Temps'];
            foreach ($dettagliorows as $row) {
                $dati[] = [$row->getData(), floatval($row->getVolt()), floatval($row->getTemp())];
                //$dati[] = [$row->getData(), floatval($row->getVolt())];
            }
            $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart();
            $chart->getData()->setArrayToDataTable($dati);

            $chart->getOptions()->getChart()
                    ->setTitle($device->getName());
            $chart->getOptions()
                    ->setSeries([['axis' => 'Volts'], ['axis' => 'Temps']])
                    ->setAxes(['y' => ['Volts' => ['label' => 'Volts'], 'Temps' => ['label' => 'Temps (Celsius)']]])
            ;
            $chart->setElementID($device->getId());
            $chart->getOptions()->getLegend()->setPosition('none');
            $charts[] = $chart;
            /* chart */
        }
        $parametri = array_merge($parametripassati, $this->getParametriTabella($parametripassati));
        $parametri["charts"] = $charts;
        $parametri['form'] = $form->createView();
        $templateobj = $this->getTabellaTemplateInformations($controller);
        $parametri['templatelocation'] = $templateobj["path"];

        return $this->render(
                        $templateobj["template"],
                        array('parametri' => $parametri)
        );
    }
}
