<?php

include '../connection.php';

if (isset($_POST['year'], $_POST['month'], $_POST['range_date'])) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $range_date = $_POST['range_date'];

    $landtype = 2;

    $query = "DELETE FROM `harvesting` WHERE `landtype` = '$landtype' AND `range_date` = '$range_date' AND `year` = '$year' AND `month` = '$month'";
    $result = mysqli_query($con,$query);

    if($result){
        $res = [
            'status' => 'SUCCESS',
            'message' => 'SUCCESS DELETED DATA.'
        ];
        echo json_encode($res);
        return;
    }else{
        $res = [
            'status' => 'NOTSUCCESS',
            'message' => ''
        ];
        echo json_encode($res);
        return;
    }

} else {
    $res = [
        'status' => 'ERROR',
        'message' => 'NO DATA EXIST IN THAT DATA.'
    ];
    echo json_encode($res);
    return;
}
?>

