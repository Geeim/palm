<?php

include "../connection.php";

// Get form data
$month = trim($_POST['monthexport']);
$range_date = trim($_POST['rangeDateExport']);
$year = trim($_POST['yearExport']);
$Mayor = strtoupper($_POST['mayorsnameexport']);

// Check if the planting record exists
$stmtcheckPlanting = $con->prepare("SELECT * FROM `planting` WHERE `landtype` = 3 AND `month` = ? AND `range_date` = ? AND `year` = ?");
$stmtcheckPlanting->bind_param("isi", $month, $range_date, $year);
$stmtcheckPlanting->execute();
$resultcheckPlanting = $stmtcheckPlanting->get_result();
$rowcheckPlanting = $resultcheckPlanting->fetch_assoc();

// Check if the harvesting record exists
$stmtcheckHarvesting = $con->prepare("SELECT * FROM `harvesting` WHERE `landtype` = 3 AND `month` = ? AND `range_date` = ? AND `year` = ?");
$stmtcheckHarvesting->bind_param("isi", $month, $range_date, $year);
$stmtcheckHarvesting->execute();
$resultcheckHarvesting = $stmtcheckHarvesting->get_result();
$rowcheckHarvesting = $resultcheckHarvesting->fetch_assoc();

    // Check if both queries returned any rows
    if ($resultcheckPlanting->num_rows > 0 || $resultcheckHarvesting->num_rows > 0) {
        $res = [
            'status' => 'SUCCESS',
            'redirect_url' => 'Function/generate_upland.php?month=' . $month . '&range_date=' . $range_date . '&year=' . $year . '&Mayor=' . $Mayor
        ];
        
        // Encode the response as JSON and return it
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 'ERROR',
        ];

        echo json_encode($res);
        return;
    }


?>
