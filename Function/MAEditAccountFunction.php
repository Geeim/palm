<?php
session_start();
include '../connection.php';


$IDadmin = $_SESSION['IDadmin'];

$Afname = strtoupper($_POST['Afname']);
$Alname = strtoupper($_POST['Alname']);
$Amname = strtoupper($_POST['Amname']);
$Ausername = $_POST['Ausername'];
$Aemail = $_POST['Aemail'];


    $stmt = $con->prepare("SELECT * FROM `admin` WHERE `IDadmin` = ?");
    $stmt->bind_param("s", $IDadmin);
    $stmt->execute();
    $result = $stmt->get_result();

// Check if admin exists
if ($result->num_rows == 0) {
    $res = [
        'status' => 'ERROR',
        'message' => 'No Admin Found',
    ];
    echo json_encode($res);
    return;
} else if(empty($Afname) || empty($Alname) || empty($Ausername) || empty($Aemail)){
    $res = [
        'status' => 'ERROR',
        'message' => 'All Field Required.',
    ];
    echo json_encode($res);
    return;
}else {
    // Prepare UPDATE statement
    $stmt2 = $con->prepare("UPDATE `admin` SET `Afname` = ?, `Alname` = ?, `Amname` = ?, `Aemail` = ?, `Ausername` = ? WHERE `IDadmin` = ?");
    $stmt2->bind_param("ssssss", $Afname, $Alname, $Amname, $Aemail, $Ausername, $IDadmin);
    
    // Execute the query
    if ($stmt2->execute()) {
        $res = [
            'status' => 'SUCCESS',
            'message' => 'Account updated successfully.',
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 'ERROR',
            'message' => 'Failed to update account.',
        ];
        echo json_encode($res);
        return;
    }

   
}
?>
