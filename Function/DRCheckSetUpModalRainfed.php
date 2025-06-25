<?php

include '../connection.php';

    $yearInsert = $_POST['yearInsert'];
    $monthpick = $_POST['monthpick'];
    $rangeDate = $_POST['rangeDate'];
    $selectedType = $_POST['selectedType'];

    if ($selectedType == "Planting") {

        $SQL = "SELECT * FROM `planting` WHERE `landtype` = '2' AND `month` = '$monthpick' AND `year` = '$yearInsert' AND `range_date` = '$rangeDate'";
        $Result = mysqli_query($con, $SQL);

        if ($Result && mysqli_num_rows($Result) > 0) {
            $res = [
                'status' => 'EXISTS',
                'message' => 'Data already exists for the selected criteria.'
            ];
            echo json_encode($res);
            return;
        }else{
            $res = [
                'status' => 'SUCCESS',
            ];
            echo json_encode($res);
            return;
        }

    } elseif ($selectedType == "Harvesting") {

        $SQL = "SELECT * FROM `harvesting` WHERE `landtype` = '2' AND `month` = '$monthpick' AND `year` = '$yearInsert' AND `range_date` = '$rangeDate'";
        $Result = mysqli_query($con, $SQL);

        if ($Result && mysqli_num_rows($Result) > 0) {
            $res = [
                'status' => 'EXISTS',
                'message' => 'Data already exists for the selected criteria.'
            ];
            echo json_encode($res);
            return;
        }else{
            $res = [
                'status' => 'SUCCESS',
            ];
            echo json_encode($res);
            return;
        }
        
    } else {
        $res = [
            'status' => 'NOPICKED',
            'message' => '!ERROR BLANKED SLECTED TYPE.'
        ];
        echo json_encode($res);
        return;
    }

?>