<?php

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ReportController extends AbstractController
{
    /**
     * Matches / exactly.
     *
     * @Route("/report", name="report")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        set_time_limit(960);
        ini_set('memory_limit', '2048M');

        $dettagliorows = $em->createQueryBuilder()
                ->select('l')
                ->from('App:Log', 'l')
                ->orderBy('l.data', 'DESC')
                ->getQuery()
                ->getResult();
        $riepilogorows = $em->createQueryBuilder()
                ->select("CONCAT(CONCAT(d.address,' '),d.name) as device, MAX(l.volt) maxvolt, MIN(l.volt) minvolt, SUBSTRING(l.data,1,10) AS grData")
                ->from('App:Log', 'l')
                ->leftJoin('l.device', 'd')
                ->groupBy('l.device, grData')
                ->orderBy('grData', 'DESC')
                ->getQuery()
                ->getResult();

        //Creare un nuovo file
        $spreadsheet = new Spreadsheet();
        $objPHPExcel = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);

        // Set properties
        $spreadsheet->getProperties()->setCreator('Manzolo');
        $spreadsheet->getProperties()->setLastModifiedBy('Manzolo');

        $dettagliosheet = $spreadsheet->createSheet();
        $dettagliosheet->setTitle('Dettaglio');
        $dettagliosheet->setCellValueByColumnAndRow(1, 1, 'Device');
        $dettagliosheet->setCellValueByColumnAndRow(2, 1, 'Volt');
        $dettagliosheet->setCellValueByColumnAndRow(3, 1, 'Temp');
        $dettagliosheet->setCellValueByColumnAndRow(4, 1, 'Data');

        $rowDettaglio = 1;
        $col = 1;
        foreach ($dettagliorows as $dettaglio) {
            ++$rowDettaglio;
            $col = 1;
            $dettagliosheet->setCellValueByColumnAndRow($col, $rowDettaglio, $dettaglio->getDevice()->__toString());
            $col++;
            $dettagliosheet->setCellValueByColumnAndRow($col, $rowDettaglio, $dettaglio->getVolt());
            $col++;
            $dettagliosheet->setCellValueByColumnAndRow($col, $rowDettaglio, $dettaglio->getTemp());
            $col++;

            $datatmp = $dettaglio->getData()->format('Y-m-d H:i:s');
            $d = (int) substr($datatmp, 8, 2);
            $m = (int) substr($datatmp, 5, 2);
            $y = (int) substr($datatmp, 0, 4);
            $h = (int) substr($datatmp, 11, 2);
            $i = (int) substr($datatmp, 14, 2);
            $dataval = \PhpOffice\PhpSpreadsheet\Shared\Date::formattedPHPToExcel($y, $m, $d, $h, $i, 0);
            $dettagliosheet->setCellValueByColumnAndRow($col, $rowDettaglio, $dataval);
        }

        $dettagliosheet->getStyle('D2:' . 'D' . $rowDettaglio)
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yyyy hh:mm:ss');

        for ($index = 0; $index < $col + 1; ++$index) {
            $letteracolonna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
            $dettagliosheet->getColumnDimension($letteracolonna)->setAutoSize(true);
        }

        // ***************  Scrittura Riepilogo  ********************
        //Scrittura su file
        $spreadsheet->setActiveSheetIndex(0);
        $riepilogosheet = $spreadsheet->getActiveSheet();
        $riepilogosheet->setTitle('Riepilogo');

        $riepilogosheet->getParent()->getDefaultStyle()->getFont()->setName('Verdana');

        //$letteracolonna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(0);
        $riepilogosheet->setCellValueByColumnAndRow(1, 1, 'Device');
        $riepilogosheet->setCellValueByColumnAndRow(2, 1, 'Max volt');
        $riepilogosheet->setCellValueByColumnAndRow(3, 1, 'Min volt');
        $riepilogosheet->setCellValueByColumnAndRow(4, 1, 'Data');

        $rowRiepilogo = 1;
        $col = 1;
        foreach ($riepilogorows as $dettaglio) {
            ++$rowRiepilogo;
            $col = 1;
            $riepilogosheet->setCellValueByColumnAndRow($col, $rowRiepilogo, $dettaglio['device']);
            $col++;
            $riepilogosheet->setCellValueByColumnAndRow($col, $rowRiepilogo, $dettaglio['maxvolt']);
            $col++;
            $riepilogosheet->setCellValueByColumnAndRow($col, $rowRiepilogo, $dettaglio['minvolt']);
            $col++;
            $datatmp = $dettaglio['grData'];
            $d = (int) substr($datatmp, 8, 2);
            $m = (int) substr($datatmp, 5, 2);
            $y = (int) substr($datatmp, 0, 4);
            $dataval = \PhpOffice\PhpSpreadsheet\Shared\Date::formattedPHPToExcel($y, $m, $d, 0, 0, 0);
            $riepilogosheet->setCellValueByColumnAndRow($col, $rowRiepilogo, $dataval);
        }

        $riepilogosheet->getStyle('B2:' . 'B' . $rowRiepilogo)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

        $riepilogosheet->getStyle('C2:' . 'C' . $rowRiepilogo)
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');

        $riepilogosheet->getStyle('D2:' . 'D' . $rowRiepilogo)
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yyyy');

        for ($index = 0; $index < $col + 1; ++$index) {
            $letteracolonna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
            $riepilogosheet->getColumnDimension($letteracolonna)->setAutoSize(true);
        }

        //Si crea un oggetto
        $todaydate = date('d-m-y');

        $filename = 'Exportazione';
        $filename = $filename . '-' . $todaydate . '-' . strtoupper(md5(uniqid((string) rand(), true)));
        $filename = $filename . '.xlsx';
        $filename = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($filename)) {
            unlink($filename);
        }

        $objPHPExcel->save($filename);

        return new Response(
            file_get_contents($filename),
            200,
            [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="Estrazione.xlsx"',]
        );
    }
}
