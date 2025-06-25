<?php
include "../connection.php";
require '../vendor/autoload.php'; 

// PhpSpreadsheet namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


//REUSABLE FUNCTION

function getSeasonName($seasonType) {
    $seasonMapping = [
        1 => 'WET SEASON',
        2 => 'DRY SEASON'
    ];
    return $seasonMapping[$seasonType];  
}

function getMonthName($month) {
    $monthMapping = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];

    return strtoupper($monthMapping[$month]);
}



$month = $_GET['month'];
$range_date = $_GET['range_date'];
$year = $_GET['year'];
$Mayor = $_GET['Mayor'];
$monthName = getMonthName($month);

$month = $_GET['month'];
$range_date = $_GET['range_date'];
$year = $_GET['year'];
$Mayor = $_GET['Mayor'];


    $stmtcheckPlanting = $con->prepare("SELECT * FROM `planting` WHERE  `landtype` = 3 AND `month` = ? AND `range_date` = ? AND `year` = ?");
    $stmtcheckPlanting->bind_param("isi",$month ,$range_date, $year);
    $stmtcheckPlanting->execute();
    $resultcheckPlanting = $stmtcheckPlanting->get_result();
    $rowcheckPlanting = $resultcheckPlanting->fetch_assoc();

    $stmtcheckHarvesting = $con->prepare("SELECT * FROM `harvesting` WHERE `landtype` = 3 AND `month` = ? AND `range_date` = ? AND `year` = ?");
    $stmtcheckHarvesting->bind_param("isi",$month ,$range_date, $year);
    $stmtcheckHarvesting->execute();
    $resultcheckHarvesting = $stmtcheckHarvesting->get_result();
    $rowcheckHarvesting = $resultcheckHarvesting->fetch_assoc();

    
   

    if ($rowcheckPlanting) {
        $headerSeasonPlanting = getSeasonName($rowcheckPlanting['season_type']) . ' ' . $year;
        $PreparedPlanted = $rowcheckPlanting['prepared_by'];
    }else{
        $headerSeasonPlanting = 'SEASON ' . $year; 
        $PreparedPlanted = 'Technician';
    }

    if ($rowcheckHarvesting) {
        $headerSeasonHarvesting = getSeasonName($rowcheckHarvesting['season_type']) . ' ' . $year;
        $PreparedHarvest = $rowcheckHarvesting['prepared_by'];
    }else{
        $headerSeasonHarvesting = 'SEASON ' . $year; 
        $PreparedHarvest = 'Technician';
    }



//SPREAD SHEET SET UP

    $spreadsheet = new Spreadsheet();
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet2 = $spreadsheet->createSheet();


    //FOR PLANTING AND HARVESTING
    $header1 = 'RICE PROGRAM';
    $header2 = 'Municipality of MARAGGONDON, CAVITE';
    $headerPeriod = 'For the Period ' . $monthName . ' ' . $range_date . ' ' . $year;

    $sqlAdmin = "SELECT * FROM `admin` WHERE 1 LIMIT 1";
    $resultAdmin = $con->query($sqlAdmin);
    $rowAdmin = $resultAdmin->fetch_assoc();
    $MA = $rowAdmin['Afname']. ' ' .$rowAdmin['Amname']. '. ' .$rowAdmin['Alname'];
  

     //FORMAT SAME DEFAULT DESIGN

        // Define thick border for the outline
        $outerBorderStyle = [
            'borders' => [
                'outline' => [ // Outer border
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Define dotted border for inside
        $dottedBorderStyle = [
            'borders' => [
                'vertical' => [ // Vertical inner borders
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                    'color' => ['rgb' => '000000'],
                ],
                'horizontal' => [ // Horizontal inner borders
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        foreach (range('A', 'AQ') as $columnID) {
            $sheet1->getColumnDimension($columnID)->setAutoSize(true);
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }


        
// WORKSHEET FOR HARVESTING (sheet1)
    $sheet1->setTitle('HARVESTING');
    $headerHarvesting = 'HARVESTING ACCOMPLISHMENT REPORT';

    //MERGE CELL
        $sheet1->mergeCells('A1:AQ1'); // Rice Program
        $sheet1->mergeCells('A2:AQ2'); // Planting Accomplishment
        $sheet1->mergeCells('A3:AQ3'); // Municipal of MARAGONDON CAVITE
        $sheet1->mergeCells('A4:AQ4'); // SEASON
        $sheet1->mergeCells('A5:AQ5'); // PERIOD
        $sheet1->mergeCells('A7:A13'); // Barangay
        $sheet1->mergeCells('B7:D9'); // Provincial Summary
        $sheet1->mergeCells('B10:D11'); // All Seed Type
        $sheet1->mergeCells('E7:AQ7'); //UPLAND
        $sheet1->mergeCells('E8:AE8'); //FORMAL
        $sheet1->mergeCells('AF8:AN9'); //INFORMAL
        $sheet1->mergeCells('AO8:AQ11'); //FARMER SAVE SEED
        $sheet1->mergeCells('E9:M9'); //NPR
        $sheet1->mergeCells('N9:V9'); //RCEF
        $sheet1->mergeCells('W9:AE9'); //OWNOTHERS
        $sheet1->mergeCells('E10:G11'); //HYBRID
        $sheet1->mergeCells('H10:J11'); //REGISTERED
        $sheet1->mergeCells('K10:M11'); //CERTIFIED
        $sheet1->mergeCells('N10:P11'); //HYBRID
        $sheet1->mergeCells('Q10:S11'); //REGISTERED
        $sheet1->mergeCells('T10:V11'); //CERTIFIED
        $sheet1->mergeCells('W10:Y11'); //HYBRID
        $sheet1->mergeCells('Z10:AB11'); //REGISTERED
        $sheet1->mergeCells('AC10:AE11'); //CERTIFIED
        $sheet1->mergeCells('AF10:AH10'); //STARTER
        $sheet1->mergeCells('AI10:AK10'); //TAGGED
        $sheet1->mergeCells('AL10:AN10'); //TRADITIONAL
        $sheet1->mergeCells('AF11:AH11'); //STARTER
        $sheet1->mergeCells('AI11:AK11'); //TAGGED
        $sheet1->mergeCells('AL11:AN11'); //TRADITIONAL

        $sheet1->mergeCells('B39:C39'); // Prepared by
        $sheet1->mergeCells('H39:I39'); // Reviewed by
        $sheet1->mergeCells('P39:Q39'); // Noted by 
        $sheet1->mergeCells('B42:C42'); // AT Name
        $sheet1->mergeCells('H42:I42'); // MA Name
        $sheet1->mergeCells('P42:Q42'); // Mayor
        $sheet1->mergeCells('B43:C43'); // AT  
        $sheet1->mergeCells('H43:I43'); // MA  
        $sheet1->mergeCells('P43:Q43'); // Mayor


    //VALUE DESIGN
        $sheet1->setCellValue('A1', $header1);
        $sheet1->setCellValue('A2', $headerHarvesting);
        $sheet1->setCellValue('A3', $header2);
        $sheet1->setCellValue('A4', $headerSeasonHarvesting);
        $sheet1->setCellValue('A5', $headerPeriod);

        $sheet1->setCellValue('E7', 'UPLAND');
        $sheet1->setCellValue('A7', 'BARANGAY');
        $sheet1->setCellValue('B7', 'Provincial Summary');
        $sheet1->setCellValue('B10', 'All Seed Type');
        $sheet1->setCellValue('E8', 'FORMAL SEED SYSTEM');
        $sheet1->setCellValue('E9', 'NRP');
        $sheet1->setCellValue('N9', 'RCEF');
        $sheet1->setCellValue('W9', 'OWN/OTHERS');

        $sheet1->setCellValue('AF8', 'INFORMAL SEED SYSTEM');
        $sheet1->setCellValue('AF10', 'Good Seeds from Starter');
        $sheet1->setCellValue('AF11', 'RS and CS by CSB');
        $sheet1->setCellValue('AI10', 'Good Seeds from Tagged');
        $sheet1->setCellValue('AI11', 'FS/RS by Accr. Seed Grower');
        $sheet1->setCellValue('AL10', 'Good Seeds from');
        $sheet1->setCellValue('AL11', 'Traditional Varieties');

        $sheet1->setCellValue('AO8', 'Farmers Saved Seeds');
        $sheet1->setCellValue('A35', 'TOTAL');
        $sheet1->setCellValue('A36', 'Remarks :');
        $sheet1->setCellValue('B39', 'Preperade by:');
        $sheet1->setCellValue('H39', 'Reviewed by:');
        $sheet1->setCellValue('P39', 'Noted by:');
        $sheet1->setCellValue('B42', $PreparedHarvest); // AT Name
        $sheet1->setCellValue('H42', $MA); // MA Name
        $sheet1->setCellValue('P42', $Mayor); // Mayor
        $sheet1->setCellValue('B43', 'Agricultural Technician'); // AT  
        $sheet1->setCellValue('H43', 'Municipal Agriculturist'); // MA  
        $sheet1->setCellValue('P43', 'Mayor'); // Mayor


        //Repeated Value
            $HybridSeed = ['E10', 'N10', 'W10'];
            $RegisteredSeed = ['H10', 'Q10', 'Z10'];
            $CertifiedSeed = ['T10', 'AC10', 'K10'];

            foreach ($HybridSeed as $cell) {
                $sheet1->setCellValue($cell, 'Hybrid Seeds');
                $sheet1->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID);  // Set fill type to solid
                $sheet1->getStyle($cell)->getFill()->getStartColor()->setRGB('DADADA');
            }
            foreach ($RegisteredSeed as $cell) {
                $sheet1->setCellValue($cell, 'Registered Seeds');
                $sheet1->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID);  // Set fill type to solid
                $sheet1->getStyle($cell)->getFill()->getStartColor()->setRGB('FFCCFF'); 
            }
            foreach ($CertifiedSeed as $cell) {
                $sheet1->setCellValue($cell, 'Certified Seeds');
                $sheet1->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID);  // Set fill type to solid
                $sheet1->getStyle($cell)->getFill()->getStartColor()->setRGB('ADB9CA'); 
            }

            //Reapeated (Area Harvested,Average Yield,Production)
            $AreaHarvested = ['B12', 'E12', 'H12', 'K12', 'N12', 'Q12', 'T12', 'W12', 'Z12', 'AC12', 'AF12', 'AI12', 'AL12', 'AO12'];
            $AverageYield =  ['C12', 'F12', 'I12', 'L12', 'O12', 'R12', 'U12', 'X12', 'AA12', 'AD12', 'AG12', 'AJ12', 'AM12', 'AP12'];
            $Production =    ['D12', 'G12', 'J12', 'M12', 'P12', 'S12', 'V12', 'Y12', 'AB12', 'AE12', 'AH12', 'AK12', 'AN12', 'AQ12'];


            foreach ($AreaHarvested as $cell) {
                $text = 'Area Harvested'; 
                $sheet1->setCellValue($cell, $text);
                $column = preg_replace('/[0-9]+/', '', $cell);
                $maxLength = strlen($text);  
                $extraWidth = 3;
                $sheet1->getColumnDimension($column)->setWidth($maxLength + $extraWidth); 
            }
            foreach ($AverageYield as $cell) {
                $text = 'Ave. Yield'; 
                $sheet1->setCellValue($cell, $text);
                $column = preg_replace('/[0-9]+/', '', $cell);
                $maxLength = strlen($text);  
                $extraWidth = 3;
                $sheet1->getColumnDimension($column)->setWidth($maxLength + $extraWidth);
            }
            foreach ($Production as $cell) {
                $text = 'Production'; 
                $sheet1->setCellValue($cell, $text);
                $column = preg_replace('/[0-9]+/', '', $cell);
                $maxLength = strlen($text);  
                $extraWidth = 3;
                $sheet1->getColumnDimension($column)->setWidth($maxLength + $extraWidth);
            }

            //Reapeated (HA,(MT/Ha), MT)
            $Ha = ['B13', 'E13', 'H13', 'K13', 'N13', 'Q13', 'T13', 'W13', 'Z13', 'AC13', 'AF13', 'AI13', 'AL13', 'AO13'];
            $MTHA = ['C13', 'F13', 'I13', 'L13', 'O13', 'R13', 'U13', 'X13', 'AA13', 'AD13', 'AG13', 'AJ13', 'AM13', 'AP13'];
            $MT = ['D13', 'G13', 'J13', 'M13', 'P13', 'S13', 'V13', 'Y13', 'AB13', 'AE13', 'AH13', 'AK13', 'AN13', 'AQ13'];


            foreach ($Ha as $cell) {
                $sheet1->setCellValue($cell, 'Ha.');
            }
            foreach ($MTHA as $cell) {
                $sheet1->setCellValue($cell, '(MT/Ha)');
            }
            foreach ($MT as $cell) {
                $sheet1->setCellValue($cell, 'MT');
            }

    //STYLE EXCEL HARVESTING
    
        //Center All
        $sheet1->getStyle($sheet1->calculateWorksheetDimension())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet1->getStyle($sheet1->calculateWorksheetDimension())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet1->getColumnDimension('A')->setWidth(50); 

    // Apply the border style to range A7:A13
        $sheet1->getStyle('A7:A13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('A1:AQ5')->getFont()->setName('Calibri');
        $sheet1->getStyle('A1:AQ5')->getFont()->setSize(11);
        $sheet1->getStyle('A1:AQ11')->getFont()->setBold(true);
        $sheet1->getStyle('A35:AQ35')->getFont()->setBold(true);
        $sheet1->getStyle('B42:P42')->getFont()->setBold(true);

            
    //COLOR
        $sheet1->getStyle('A7:AQ35')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet1->getStyle('E7')->getFill()->getStartColor()->setRGB('A8D08D');  // UPLAND COLOR
        $sheet1->getStyle('B7:D34')->getFill()->getStartColor()->setRGB('DEEAF6');  // Barangay Color
        $sheet1->getStyle('E8:AN8')->getFill()->getStartColor()->setRGB('FEF2CB'); // FORMAL / INFORMAL
        $sheet1->getStyle('E9')->getFill()->getStartColor()->setRGB('92d050'); // NRP
        $sheet1->getStyle('N9')->getFill()->getStartColor()->setRGB('FFFF00'); // RCEF
        $sheet1->getStyle('W9')->getFill()->getStartColor()->setRGB('FFC000'); // OWN/OTHERS
        $sheet1->getStyle('AF10:AN11')->getFill()->getStartColor()->setRGB('DADADA'); // GOODSEED
        $sheet1->getStyle('AO8')->getFill()->getStartColor()->setRGB('A8D08D'); // FARMER SAVE SEED
        $sheet1->getStyle('B35:AQ35')->getFill()->getStartColor()->setRGB('DADADA'); // TOTAL
        
        //SUMMARY OK
        $sheet1->getStyle('B7:D9')->applyFromArray($outerBorderStyle); 
        $sheet1->getStyle('B10:D13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('B10:D13')->applyFromArray($outerBorderStyle);

        //UPLAND
        $sheet1->getStyle('E7:AQ7')->applyFromArray($outerBorderStyle);  

        //FORMAL
        $sheet1->getStyle('E8:AE8')->applyFromArray($outerBorderStyle); //FORMAL

        $sheet1->getStyle('E9:M9')->applyFromArray($outerBorderStyle); //NPR
        $sheet1->getStyle('N9:V9')->applyFromArray($outerBorderStyle); //RCEF
        $sheet1->getStyle('W9:AE9')->applyFromArray($outerBorderStyle); //OWNOTHERS


        //NPR
        $sheet1->getStyle('E10:G13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('E10:G13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('H10:J13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('H10:J13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('K10:M13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('K10:M13')->applyFromArray($outerBorderStyle);

        //RCEF
        $sheet1->getStyle('N10:P13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('N10:P13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('Q10:S13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('Q10:S13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('T10:V13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('T10:V13')->applyFromArray($outerBorderStyle);

        //OWNOTHERS
        $sheet1->getStyle('W10:Y13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('W10:Y13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('Z10:AB13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('Z10:AB13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AC10:AE13')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('AC10:AE13')->applyFromArray($outerBorderStyle);

        //INFORMAL
        $sheet1->getStyle('AF8:AN9')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AF10:AN13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AF10:AN13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AF10:AN13')->applyFromArray($dottedBorderStyle);

        //FSS
        $sheet1->getStyle('AO8:AQ13')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AO8:AQ13')->applyFromArray($dottedBorderStyle);

        //BARANGAY
        $sheet1->getStyle('A35')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('A14:A34')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('A14:A34')->applyFromArray($dottedBorderStyle);
        
        //SUMMARY
        $sheet1->getStyle('B35:D35')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('B35:D35')->applyFromArray($dottedBorderStyle);
        $sheet1->getStyle('B14:D34')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('B14:D34')->applyFromArray($dottedBorderStyle);

        //FORMAL
        $sheet1->getStyle('E14:M34')->applyFromArray($outerBorderStyle); //NPR
        $sheet1->getStyle('E14:M34')->applyFromArray($dottedBorderStyle);
        $sheet1->getStyle('N14:V34')->applyFromArray($outerBorderStyle); //RCEF
        $sheet1->getStyle('N14:V34')->applyFromArray($dottedBorderStyle);
        $sheet1->getStyle('W14:AE34')->applyFromArray($outerBorderStyle); //OWNOTHERS
        $sheet1->getStyle('W14:AE34')->applyFromArray($dottedBorderStyle);
        $sheet1->getStyle('E35:M35')->applyFromArray($outerBorderStyle); //NPR
        $sheet1->getStyle('E35:M35')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('N35:V35')->applyFromArray($outerBorderStyle); //RCEF
        $sheet1->getStyle('N35:V35')->applyFromArray($dottedBorderStyle); 
        $sheet1->getStyle('W35:AE35')->applyFromArray($outerBorderStyle); //OWNOTHERS
        $sheet1->getStyle('W35:AE35')->applyFromArray($dottedBorderStyle);

        //INFORMAL
        $sheet1->getStyle('AF14:AN34')->applyFromArray($outerBorderStyle); 
        $sheet1->getStyle('AF14:AN34')->applyFromArray($dottedBorderStyle);
        $sheet1->getStyle('AF35:AN35')->applyFromArray($outerBorderStyle); 
        $sheet1->getStyle('AF35:AN35')->applyFromArray($dottedBorderStyle);

        //FSS
        $sheet1->getStyle('AO14:AQ34')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AO14:AQ34')->applyFromArray($dottedBorderStyle);
        $sheet1->getStyle('AO35:AQ35')->applyFromArray($outerBorderStyle);
        $sheet1->getStyle('AO35:AQ35')->applyFromArray($dottedBorderStyle);

        //NAMES PREPARED
        $sheet1->getStyle('B42:C42')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN); // Line under B42
        $sheet1->getStyle('H42:I42')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN); // Line under I42
        $sheet1->getStyle('P42:Q42')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN); // Line under P42
            


    //SUM COMPUTATION - LINE 35
        $Cellsheet1 = ['B', 'D', 'E', 'G', 'H', 'J', 'K', 'M', 'N', 'P', 'Q', 'S', 'T', 'V', 'W', 'Y', 'Z', 'AB', 'AC', 'AE', 'AF', 'AH', 'AI', 'AK', 'AL', 'AN', 'AO', 'AQ'];
        foreach ($Cellsheet1 as $column) {
            $sumFormula = "=SUM({$column}14:{$column}34)";
            $sheet1->setCellValue("{$column}35", $sumFormula);
            $sheet1->getStyle("{$column}35")->getNumberFormat()->setFormatCode('#,##0.00');
        }

        //Total Average Yield
        $sheet1->getStyle("B35:AQ35")->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet1->setCellValue("C35", "=IFERROR(D35/B35, 0)");
        $sheet1->setCellValue("F35", "=IFERROR(G35/E35, 0)");
        $sheet1->setCellValue("I35", "=IFERROR(J35/H35, 0)");
        $sheet1->setCellValue("L35", "=IFERROR(M35/K35, 0)");
        $sheet1->setCellValue("O35", "=IFERROR(P35/N35, 0)");
        $sheet1->setCellValue("R35", "=IFERROR(S35/Q35, 0)");
        $sheet1->setCellValue("U35", "=IFERROR(V35/T35, 0)");
        $sheet1->setCellValue("X35", "=IFERROR(Y35/W35, 0)");
        $sheet1->setCellValue("AA35", "=IFERROR(AB35/AZ35, 0)");
        $sheet1->setCellValue("AD35", "=IFERROR(AE35/AC35, 0)");
        $sheet1->setCellValue("AG35", "=IFERROR(AH35/AF35, 0)");
        $sheet1->setCellValue("AJ35", "=IFERROR(AK35/AI35, 0)");
        $sheet1->setCellValue("AM35", "=IFERROR(AN35/AL35, 0)");
        $sheet1->setCellValue("AP35", "=IFERROR(AQ35/AO35, 0)");
       



        // WORKSHEET FOR PLANTING (sheet2)
            $headerPlanting = 'PLANTING ACCOMPLISHMENT REPORT';
            $sheet2->setTitle('PLANTING');

        //MERGE CELL ??
        $sheet2->mergeCells('A1:AC1'); // Rice Program
        $sheet2->mergeCells('A2:AC2'); // Planting Accomplishment
        $sheet2->mergeCells('A3:AC3'); // Municipal of MARAGONDON CAVITE
        $sheet2->mergeCells('A4:AC4'); // SEASON

        // OK
        $sheet2->mergeCells('A5:AC5'); // PERIOD
        $sheet2->mergeCells('A7:A13'); // Barangay
        $sheet2->mergeCells('B7:C9'); // Provincial Summary
        $sheet2->mergeCells('B10:C11'); // All Seed Type
        // OK
        $sheet2->mergeCells('D7:AC7'); //UPLAND
        $sheet2->mergeCells('D8:U8'); //FORMAL
        $sheet2->mergeCells('V8:AA9'); //INFORMAL
        $sheet2->mergeCells('AB8:AC11'); //FARMER SAVE SEED
        // OK
        $sheet2->mergeCells('D9:I9'); //NPR
        $sheet2->mergeCells('J9:O9'); //RCEF
        $sheet2->mergeCells('P9:U9'); //OWNOTHERS
        //OK
        $sheet2->mergeCells('D10:E11'); //HYBRID
        $sheet2->mergeCells('F10:G11'); //REGISTERED
        $sheet2->mergeCells('H10:I11'); //CERTIFIED
        //OK
        $sheet2->mergeCells('J10:K11'); //HYBRID
        $sheet2->mergeCells('L10:M11'); //REGISTERED
        $sheet2->mergeCells('N10:O11'); //CERTIFIED
        //OK
        $sheet2->mergeCells('P10:Q11'); //HYBRID
        $sheet2->mergeCells('R10:S11'); //REGISTERED
        $sheet2->mergeCells('T10:U11'); //CERTIFIED

        $sheet2->mergeCells('V10:W10'); //STARTER
        $sheet2->mergeCells('X10:Y10'); //TAGGED
        $sheet2->mergeCells('Z10:AA10'); //TRADITIONAL
        $sheet2->mergeCells('V11:W11'); //STARTER
        $sheet2->mergeCells('X11:Y11'); //TAGGED
        $sheet2->mergeCells('Z11:AA11'); //TRADITIONAL


        //No Farmers & Area Planted
            // I-merge ang mga cells mula B12 hanggang AC13
            for ($col = 2; $col <= 29; $col++) { 
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet2->mergeCells("{$columnLetter}12:{$columnLetter}13");
            }


        //OK
        $sheet2->mergeCells('B39:C39'); // Prepared by
        $sheet2->mergeCells('H39:I39'); // Reviewed by
        $sheet2->mergeCells('P39:Q39'); // Noted by 
        $sheet2->mergeCells('B42:C42'); // AT Name
        $sheet2->mergeCells('H42:I42'); // MA Name
        $sheet2->mergeCells('P42:Q42'); // Mayor
        $sheet2->mergeCells('B43:C43'); // AT  
        $sheet2->mergeCells('H43:I43'); // MA  
        $sheet2->mergeCells('P43:Q43'); // Mayor



    // VALUE
        $sheet2->setCellValue('A1', $header1);
        $sheet2->setCellValue('A2', $headerPlanting);
        $sheet2->setCellValue('A3', $header2);
        $sheet2->setCellValue('A4', $headerSeasonPlanting);
        $sheet2->setCellValue('A5', $headerPeriod);

        $sheet2->setCellValue('D7', 'UPLAND');
        $sheet2->setCellValue('A7', 'BARANGAY');
        $sheet2->setCellValue('B7', 'Provincial Summary');
        $sheet2->setCellValue('B10', 'All Seed Type');
        $sheet2->setCellValue('D8', 'FORMAL SEED SYSTEM');
        $sheet2->setCellValue('D9', 'NRP');
        $sheet2->setCellValue('J9', 'RCEF');
        $sheet2->setCellValue('P9', 'OWN/OTHERS');

        $sheet2->setCellValue('V8', 'INFORMAL SEED SYSTEM');
        $sheet2->setCellValue('V10', 'Good Seeds from Starter');
        $sheet2->setCellValue('V11', 'RS and CS by CSB');
        $sheet2->setCellValue('X10', 'Good Seeds from Tagged');
        $sheet2->setCellValue('X11', 'FS/RS by Accr. Seed Grower');
        $sheet2->setCellValue('Z10', 'Good Seeds from');
        $sheet2->setCellValue('Z11', 'Traditional Varieties');

        $sheet2->setCellValue('AB8', 'Farmers Saved Seeds');
        $sheet2->setCellValue('A35', 'TOTAL');
        $sheet2->setCellValue('A36', 'Remarks :');
        $sheet2->setCellValue('B39', 'Preperade by:');
        $sheet2->setCellValue('H39', 'Reviewed by:');
        $sheet2->setCellValue('P39', 'Noted by:');
        $sheet2->setCellValue('B42', $PreparedPlanted); // AT Name
        $sheet2->setCellValue('H42', $MA); // MA Name
        $sheet2->setCellValue('P42', $Mayor); // Mayor
        $sheet2->setCellValue('B43', 'Agricultural Technician'); // AT  
        $sheet2->setCellValue('H43', 'Municipal Agriculturist'); // MA  
        $sheet2->setCellValue('P43', 'Mayor'); // Mayor

        //Repeater Value
            $HybridSeed = ['D10', 'J10', 'P10'];
            $RegisteredSeed = ['F10', 'L10', 'R10'];
            $CertifiedSeed = ['H10', 'N10', 'T10'];

            foreach ($HybridSeed as $cell) {
                $sheet2->setCellValue($cell, 'Hybrid Seeds');
                $sheet2->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID);  // Set fill type to solid
                $sheet2->getStyle($cell)->getFill()->getStartColor()->setRGB('DADADA');
            }
            foreach ($RegisteredSeed as $cell) {
                $sheet2->setCellValue($cell, 'Registered Seeds');
                $sheet2->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID);  // Set fill type to solid
                $sheet2->getStyle($cell)->getFill()->getStartColor()->setRGB('FFCCFF'); 
            }
            foreach ($CertifiedSeed as $cell) {
                $sheet2->setCellValue($cell, 'Certified Seeds');
                $sheet2->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID);  // Set fill type to solid
                $sheet2->getStyle($cell)->getFill()->getStartColor()->setRGB('ADB9CA'); 
            }   


        //REPEATED VALUE 
            $AreaPlanted = ['B12', 'D12', 'F12', 'H12', 'J12', 'L12', 'N12', 'P12', 'R12', 'T12', 'V12', 'X12', 'Z12', 'AB12'];
            $NoFarmers =  ['C12', 'E12', 'G12', 'I12', 'K12', 'M12', 'O12', 'Q12', 'S12', 'U12', 'W12', 'Y12', 'AA12', 'AC12'];
        
            foreach ($AreaPlanted as $cell) {
                $text = 'Area Planted (Ha.)'; 
                $sheet2->setCellValue($cell, $text);
                $column = preg_replace('/[0-9]+/', '', $cell);
                $maxLength = strlen($text);  
                $extraWidth = 3;
                $sheet2->getColumnDimension($column)->setWidth($maxLength + $extraWidth); 
            }
            foreach ($NoFarmers as $cell) {
                $text = 'No. of Farmers'; 
                $sheet2->setCellValue($cell, $text);
                $column = preg_replace('/[0-9]+/', '', $cell);
                $maxLength = strlen($text);  
                $extraWidth = 3;
                $sheet2->getColumnDimension($column)->setWidth($maxLength + $extraWidth);
            }



            //STYLE EXCEL HARVESTING

        //Center All
        $sheet2->getStyle($sheet2->calculateWorksheetDimension())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet2->getStyle($sheet2->calculateWorksheetDimension())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet2->getColumnDimension('A')->setWidth(50); 

    // Header Design
        $sheet2->getStyle('A1:AC5')->getFont()->setName('Calibri');
        $sheet2->getStyle('A1:AC5')->getFont()->setSize(11);
        $sheet2->getStyle('A1:AC11')->getFont()->setBold(true);
        $sheet2->getStyle('A35:AC35')->getFont()->setBold(true);
        $sheet2->getStyle('B42:P42')->getFont()->setBold(true);


    //COLOR
        $sheet2->getStyle('A7:AC35')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet2->getStyle('D7')->getFill()->getStartColor()->setRGB('A8D08D');  // UPLAND COLOR
        $sheet2->getStyle('B7:C34')->getFill()->getStartColor()->setRGB('DEEAF6');  // Barangay Color
        $sheet2->getStyle('D8:V8')->getFill()->getStartColor()->setRGB('FEF2CB'); // FORMAL / INFORMAL
        $sheet2->getStyle('D9')->getFill()->getStartColor()->setRGB('92d050'); // NRP
        $sheet2->getStyle('J9')->getFill()->getStartColor()->setRGB('FFFF00'); // RCEF
        $sheet2->getStyle('P9')->getFill()->getStartColor()->setRGB('FFC000'); // OWN/OTHERS
        $sheet2->getStyle('V10:AA11')->getFill()->getStartColor()->setRGB('DADADA'); // GOODSEED
        $sheet2->getStyle('AB8')->getFill()->getStartColor()->setRGB('A8D08D'); // FARMER SAVE SEED
        $sheet2->getStyle('B35:AC35')->getFill()->getStartColor()->setRGB('DADADA'); // TOTAL


    //BORDER
        $sheet2->getStyle('A7:A13')->applyFromArray($outerBorderStyle); // BORDER BARANGAY

        //SUMMARY OK
        $sheet2->getStyle('B7:C9')->applyFromArray($outerBorderStyle); 
        $sheet2->getStyle('B10:C13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('B10:C13')->applyFromArray($outerBorderStyle);

        //UPLAND
        $sheet2->getStyle('D7:AC7')->applyFromArray($outerBorderStyle);  

        //FORMAL
        $sheet2->getStyle('D8:U8')->applyFromArray($outerBorderStyle); //FORMAL

        $sheet2->getStyle('D9:I9')->applyFromArray($outerBorderStyle); //NPR
        $sheet2->getStyle('J9:O9')->applyFromArray($outerBorderStyle); //RCEF
        $sheet2->getStyle('P9:U9')->applyFromArray($outerBorderStyle); //OWNOTHERS

        //NPR
        $sheet2->getStyle('D10:E13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('D10:E13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('F10:G13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('F10:G13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('H10:I13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('H10:I13')->applyFromArray($outerBorderStyle);

        //RCEF
        $sheet2->getStyle('J10:K13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('J10:K13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('L10:M13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('L10:M13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('N10:O13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('N10:O13')->applyFromArray($outerBorderStyle);

        //OWNOTHERS
        $sheet2->getStyle('P10:Q13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('P10:Q13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('R10:S13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('R10:S13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('T10:U13')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('T10:U13')->applyFromArray($outerBorderStyle);

        //INFORMAL
        $sheet2->getStyle('V8:AA9')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('V10:AA13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('V10:AA13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('V10:AA13')->applyFromArray($dottedBorderStyle);

        //FSS
        $sheet2->getStyle('AB8:AC13')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('AB8:AC13')->applyFromArray($dottedBorderStyle);

        //BARANGAY
        $sheet2->getStyle('A35')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('A14:A34')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('A14:A34')->applyFromArray($dottedBorderStyle);
        
        //SUMMARY
        $sheet2->getStyle('B35:C35')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('B35:C35')->applyFromArray($dottedBorderStyle);
        $sheet2->getStyle('B14:C34')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('B14:C34')->applyFromArray($dottedBorderStyle);

        //FORMAL
        $sheet2->getStyle('D14:I34')->applyFromArray($outerBorderStyle); //NPR
        $sheet2->getStyle('D14:I34')->applyFromArray($dottedBorderStyle);
        $sheet2->getStyle('J14:O34')->applyFromArray($outerBorderStyle); //RCEF
        $sheet2->getStyle('J14:O34')->applyFromArray($dottedBorderStyle);
        $sheet2->getStyle('P14:AA34')->applyFromArray($outerBorderStyle); //OWNOTHERS
        $sheet2->getStyle('P14:AA34')->applyFromArray($dottedBorderStyle);
        $sheet2->getStyle('D35:I35')->applyFromArray($outerBorderStyle); //NPR
        $sheet2->getStyle('D35:I35')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('J35:O35')->applyFromArray($outerBorderStyle); //RCEF
        $sheet2->getStyle('J35:O35')->applyFromArray($dottedBorderStyle); 
        $sheet2->getStyle('P35:U35')->applyFromArray($outerBorderStyle); //OWNOTHERS
        $sheet2->getStyle('P35:U35')->applyFromArray($dottedBorderStyle);

        //INFORMAL
        $sheet2->getStyle('V14:AA34')->applyFromArray($outerBorderStyle); 
        $sheet2->getStyle('V14:AA34')->applyFromArray($dottedBorderStyle);
        $sheet2->getStyle('V35:AA35')->applyFromArray($outerBorderStyle); 
        $sheet2->getStyle('V35:AA35')->applyFromArray($dottedBorderStyle);

        //FSS
        $sheet2->getStyle('AB14:AC34')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('AB14:AC34')->applyFromArray($dottedBorderStyle);
        $sheet2->getStyle('AB35:AC35')->applyFromArray($outerBorderStyle);
        $sheet2->getStyle('AB35:AC35')->applyFromArray($dottedBorderStyle);

    
        //NAMES PREPARED
        $sheet2->getStyle('B42:C42')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN); // Line under B42
        $sheet2->getStyle('H42:I42')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN); // Line under I42
        $sheet2->getStyle('P42:Q42')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN); // Line under P42
  
     
        //SUM COMPUTATION - LINE 35   
        $Cellsheet2 = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','AA', 'AB', 'AC'];
        foreach ($Cellsheet2 as $column) {
            $sumFormula = "=SUM({$column}14:{$column}34)";
            $sheet2->setCellValue("{$column}35", $sumFormula);
            $sheet2->getStyle("{$column}35")->getNumberFormat()->setFormatCode('#,##0.00');
        }
        


    //DATABASE VALUE 

        $HarvestedValue = 
        " WITH GroupedData AS (
            SELECT 
                h.landtype, 
                h.season_type, 
                h.seed_system_type, 
                h.project_type, 
                h.seed_name, 
                h.barangay, 
                h.area_harvested, 
                h.average_yield,
                h.production
            FROM harvesting h
            JOIN barangay b ON h.barangay = b.IDbarangay
            WHERE 
                h.landtype = 3 
                AND h.`month` = ?
                AND h.`year` = ?
                AND h.`range_date` = ?
        )
        SELECT 
            b.BarangayName AS Barangay,
            -- SUMMARY
            ROUND(SUM(gd.area_harvested), 2) AS Total_Area_Harvested,
            ROUND(CASE WHEN SUM(gd.area_harvested) = 0 OR SUM(gd.production) = 0 THEN 0 
                ELSE SUM(gd.production) / NULLIF(SUM(gd.area_harvested), 0)END, 2) AS Total_AverageYield,
            ROUND(SUM(gd.production), 2) AS Total_Production,
            
            -- FORMAL NRP HYBRID (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 1 
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested111,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 1 
                THEN gd.average_yield ELSE 0 END) AS AverageYield111,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 1 
                THEN gd.production ELSE 0 END) AS Production111,
                
            -- FORMAL NPR REGISTERED (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 2 
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested112,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 2 
                THEN gd.average_yield ELSE 0 END) AS AverageYield112,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 2 
                THEN gd.production ELSE 0 END) AS Production112,
        
            -- FORMAL NPR CERTIFIED (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 3 
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested113,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 3 
                THEN gd.average_yield ELSE 0 END) AS AverageYield113,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 3 
                THEN gd.production ELSE 0 END) AS Production113,
                
            -- FORMAL RCEF HYBRID (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 1
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested121,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 1 
                THEN gd.average_yield ELSE 0 END) AS AverageYield121,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 1 
                THEN gd.production ELSE 0 END) AS Production121,
                
            -- FORMAL RCEF REGISTERED (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 2
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested122,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 2 
                THEN gd.average_yield ELSE 0 END) AS AverageYield122,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 2 
                THEN gd.production ELSE 0 END) AS Production122,
            
            -- FORMAL RCEF CERTIFIED (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 3
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested123,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 3 
                THEN gd.average_yield ELSE 0 END) AS AverageYield123,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 3 
                THEN gd.production ELSE 0 END) AS Production123,
            
            -- FORMAL OWNOTHERS HYBRID (3) 
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 1
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested131,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 1
                THEN gd.average_yield ELSE 0 END) AS AverageYield131,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 1 
                THEN gd.production ELSE 0 END) AS Production131,
                
            -- FORMAL OWNOTHERS REGISTERED (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 2
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested132,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 2
                THEN gd.average_yield ELSE 0 END) AS AverageYield132,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 2 
                THEN gd.production ELSE 0 END) AS Production132,
                
            -- FORMAL OWNOTHERS CERTIFIED (3)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 3
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested133,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 3
                THEN gd.average_yield ELSE 0 END) AS AverageYield133,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 3 
                THEN gd.production ELSE 0 END) AS Production133,
                
            -- INFORMAL NULL STARTER  (3)
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 4
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested2N4,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 4
                THEN gd.average_yield ELSE 0 END) AS AverageYield2N4,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 4 
                THEN gd.production ELSE 0 END) AS Production2N4,
                
            -- INFORMAL NULL TAGGED (3)
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 5
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested2N5,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 5
                THEN gd.average_yield ELSE 0 END) AS AverageYield2N5,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 5 
                THEN gd.production ELSE 0 END) AS Production2N5,
                
            -- INFORMAL NULL TRADITIONAL (3) 
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 6
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested2N6,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 6
                THEN gd.average_yield ELSE 0 END) AS AverageYield2N6,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 6 
                THEN gd.production ELSE 0 END) AS Production2N6,
                
            -- FSS NULL NULL (3)
            SUM(CASE WHEN gd.seed_system_type = 3 AND gd.project_type IS NULL AND gd.seed_name IS NULL
                THEN gd.area_harvested ELSE 0 END) AS AreaHarvested3NN,
            SUM(CASE WHEN gd.seed_system_type = 3 AND gd.project_type IS NULL AND gd.seed_name IS NULL
                THEN gd.average_yield ELSE 0 END) AS AverageYield3NN,
            SUM(CASE WHEN gd.seed_system_type = 3 AND gd.project_type IS NULL AND gd.seed_name IS NULL
                THEN gd.production ELSE 0 END) AS Production3NN
                
    
    
        FROM GroupedData gd  
        JOIN barangay b ON gd.barangay = b.IDbarangay  
        GROUP BY b.BarangayName 
        ORDER BY b.BarangayName;
    
        ";
    
    
    
    $PlantedValue = 
        "WITH GroupedData AS (
                SELECT 
                    p.landtype, 
                    p.season_type, 
                    p.seed_system_type, 
                    p.project_type, 
                    p.seed_name, 
                    p.barangay, 
                    p.area_planted, 
                    p.no_farmers
                FROM planting p
                JOIN barangay b ON p.barangay = b.IDbarangay
                WHERE 
                    p.landtype = 3 
                    AND p.`month` = ?
                    AND p.`year` = ? 
                    AND p.`range_date` = ?
            )
    
            SELECT 
                b.BarangayName AS Barangay,
                -- SUMMARY
                ROUND(SUM(gd.area_planted), 2) AS Total_Area_Planted,
                SUM(gd.no_farmers) AS Total_Farmers,
                
            -- FORMAL NRP HYBRID (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 1 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted111,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 1 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers111,
                
            -- FORMAL NPR REGISTERED (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 2 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted112,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 2 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers112,
            
            -- FORMAL NPR CERTIFIED (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 3 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted113,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 1 AND gd.seed_name = 3 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers113,
                
            -- FORMAL RCEF HYBRID (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 1 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted121,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 1 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers121,
                
            -- FORMAL RCEF REGISTERED (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 2 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted122,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 2 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers122,
            
            -- FORMAL RCEF CERTIFIED (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 3 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted123,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 2 AND gd.seed_name = 3 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers123,
            
            -- FORMAL OWNOTHERS HYBRID (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 1 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted131,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 1 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers131, 
            
            -- FORMAL OWNOTHERS REGISTERED (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 2 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted132,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 2 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers132, 
    
            -- FORMAL OWNOTHERS CERTIFIED (2)
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 3 
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted133,
            SUM(CASE WHEN gd.seed_system_type = 1 AND gd.project_type = 3 AND gd.seed_name = 3 
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers133, 
                
            -- INFORMAL NULL STARTER  (2)
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 4
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted2N4,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 4
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers2N4, 
                
            -- INFORMAL NULL TAGGED (2)
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 5
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted2N5,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 5
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers2N5, 
                
            -- INFORMAL NULL TRADITIONAL (2) 
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 6
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted2N6,
            SUM(CASE WHEN gd.seed_system_type = 2 AND gd.project_type IS NULL AND gd.seed_name = 6
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers2N6, 
                
            -- FSS NULL NULL (2)
            SUM(CASE WHEN gd.seed_system_type = 3 AND gd.project_type IS NULL AND gd.seed_name IS NULL
                THEN gd.area_planted ELSE 0 END) AS AreaPlanted3NN,
            SUM(CASE WHEN gd.seed_system_type = 3 AND gd.project_type IS NULL AND gd.seed_name IS NULL
                THEN gd.no_farmers ELSE 0 END) AS NoFarmers3NN
    
        
            FROM GroupedData gd  
            JOIN barangay b ON gd.barangay = b.IDbarangay  
            GROUP BY b.BarangayName 
            ORDER BY b.BarangayName;
    
        ";



        $stmt1 = $con->prepare($HarvestedValue);
        $stmt1->bind_param('iis', $month, $year, $range_date);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        $stmt2 = $con->prepare($PlantedValue);
        $stmt2->bind_param('iis', $month, $year, $range_date);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        //SHEET FOR HARVESTING 
        if($result1->num_rows > 0){
            $row1 = 14; // Start from row A14 for data
            while ($data = $result1->fetch_assoc()) {
                $sheet1->setCellValue('A' . $row1, $data['Barangay']);
            
                $sheet1->setCellValue('B' . $row1, $data['Total_Area_Harvested']);
                $sheet1->setCellValue('C' . $row1, $data['Total_AverageYield']);
                $sheet1->setCellValue('D' . $row1, $data['Total_Production']);
                $sheet1->setCellValue('E' . $row1, $data['AreaHarvested111']);
                $sheet1->setCellValue('F' . $row1, $data['AverageYield111']);
                $sheet1->setCellValue('G' . $row1, $data['Production111']);
                $sheet1->setCellValue('H' . $row1, $data['AreaHarvested112']);
                $sheet1->setCellValue('I' . $row1, $data['AverageYield112']);
                $sheet1->setCellValue('J' . $row1, $data['Production112']);
                $sheet1->setCellValue('K' . $row1, $data['AreaHarvested113']);
                $sheet1->setCellValue('L' . $row1, $data['AverageYield113']);
                $sheet1->setCellValue('M' . $row1, $data['Production113']);
                $sheet1->setCellValue('N' . $row1, $data['AreaHarvested121']);
                $sheet1->setCellValue('O' . $row1, $data['AverageYield121']);
                $sheet1->setCellValue('P' . $row1, $data['Production121']);
                $sheet1->setCellValue('Q' . $row1, $data['AreaHarvested122']);
                $sheet1->setCellValue('R' . $row1, $data['AverageYield122']);
                $sheet1->setCellValue('S' . $row1, $data['Production122']);
                $sheet1->setCellValue('T' . $row1, $data['AreaHarvested123']);
                $sheet1->setCellValue('U' . $row1, $data['AverageYield123']);
                $sheet1->setCellValue('V' . $row1, $data['Production123']);
                $sheet1->setCellValue('W' . $row1, $data['AreaHarvested131']);
                $sheet1->setCellValue('X' . $row1, $data['AverageYield131']);
                $sheet1->setCellValue('Y' . $row1, $data['Production131']);
                $sheet1->setCellValue('Z' . $row1, $data['AreaHarvested132']);
                $sheet1->setCellValue('AA' . $row1, $data['AverageYield132']);
                $sheet1->setCellValue('AB' . $row1, $data['Production132']);
                $sheet1->setCellValue('AC' . $row1, $data['AreaHarvested133']);
                $sheet1->setCellValue('AD' . $row1, $data['AverageYield133']);
                $sheet1->setCellValue('AE' . $row1, $data['Production133']);
                $sheet1->setCellValue('AF' . $row1, $data['AreaHarvested2N4']);
                $sheet1->setCellValue('AG' . $row1, $data['AverageYield2N4']);
                $sheet1->setCellValue('AH' . $row1, $data['Production2N4']);
                $sheet1->setCellValue('AI' . $row1, $data['AreaHarvested2N5']);
                $sheet1->setCellValue('AJ' . $row1, $data['AverageYield2N5']);
                $sheet1->setCellValue('AK' . $row1, $data['Production2N5']);
                $sheet1->setCellValue('AL' . $row1, $data['AreaHarvested2N6']);
                $sheet1->setCellValue('AM' . $row1, $data['AverageYield2N6']);
                $sheet1->setCellValue('AN' . $row1, $data['Production2N6']);
                $sheet1->setCellValue('AO' . $row1, $data['AreaHarvested3NN']);
                $sheet1->setCellValue('AP' . $row1, $data['AverageYield3NN']);
                $sheet1->setCellValue('AQ' . $row1, $data['Production3NN']);
                
                $row1++;
            }    
        }

        //SHEET FOR PLANTING
        if($result2->num_rows > 0){
            $row2 = 14; // Start from row A14 for data
            while ($data = $result2->fetch_assoc()) {
                $sheet2->setCellValue('A' . $row2, $data['Barangay']);
                $sheet2->setCellValue('B' . $row2, $data['Total_Area_Planted']);
                $sheet2->setCellValue('C' . $row2, $data['Total_Farmers']);
                $sheet2->setCellValue('D' . $row2, $data['AreaPlanted111']);
                $sheet2->setCellValue('E' . $row2, $data['NoFarmers111']);
                $sheet2->setCellValue('F' . $row2, $data['AreaPlanted112']);
                $sheet2->setCellValue('G' . $row2, $data['NoFarmers112']);
                $sheet2->setCellValue('H' . $row2, $data['AreaPlanted113']);
                $sheet2->setCellValue('I' . $row2, $data['NoFarmers113']);
                $sheet2->setCellValue('J' . $row2, $data['AreaPlanted121']);
                $sheet2->setCellValue('K' . $row2, $data['NoFarmers121']);
                $sheet2->setCellValue('L' . $row2, $data['AreaPlanted122']);
                $sheet2->setCellValue('M' . $row2, $data['NoFarmers122']);
                $sheet2->setCellValue('N' . $row2, $data['AreaPlanted123']);
                $sheet2->setCellValue('O' . $row2, $data['NoFarmers123']);
                $sheet2->setCellValue('P' . $row2, $data['AreaPlanted131']);
                $sheet2->setCellValue('Q' . $row2, $data['NoFarmers131']);
                $sheet2->setCellValue('R' . $row2, $data['AreaPlanted132']);
                $sheet2->setCellValue('S' . $row2, $data['NoFarmers132']);
                $sheet2->setCellValue('T' . $row2, $data['AreaPlanted133']);
                $sheet2->setCellValue('U' . $row2, $data['NoFarmers133']);
                $sheet2->setCellValue('V' . $row2, $data['AreaPlanted2N4']);
                $sheet2->setCellValue('W' . $row2, $data['NoFarmers2N4']);
                $sheet2->setCellValue('X' . $row2, $data['AreaPlanted2N5']);
                $sheet2->setCellValue('Y' . $row2, $data['NoFarmers2N5']);
                $sheet2->setCellValue('Z' . $row2, $data['AreaPlanted2N6']);
                $sheet2->setCellValue('AA' . $row2, $data['NoFarmers2N6']);
                $sheet2->setCellValue('AB' . $row2, $data['AreaPlanted3NN']);
                $sheet2->setCellValue('AC' . $row2, $data['NoFarmers3NN']);
                
                $row2++;
            }
        }

        
    $filename ='UPLAND ' . $monthName . ' ' . $range_date . ' ' . $year . ' REPORT';
    $filename = preg_replace('/[^a-zA-Z0-9-_\. ]/', '_', $filename);
    $filename = preg_replace('/\.$/', '', $filename); 
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');


    // Create writer and save the file to output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');


        exit();


?>

