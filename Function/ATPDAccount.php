<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../connection.php";

$IDadmin = $_SESSION['IDadmin'];

// Check if IDdeleted is passed
if (!isset($_POST['IDdeleted']) || empty($_POST['IDdeleted'])) {
    $res = [
        'status' => 'ERROR',
        'message' => 'IDdeleted is required'
    ];
    echo json_encode($res);  // Send JSON response back
    return;
}

$IDdeleted = trim($_POST['IDdeleted']);

// Check if the employee exists in deleted_employee table
$stmt = $con->prepare("SELECT * FROM `deleted_employee` WHERE `IDdeleted` = ?");
$stmt->bind_param("s", $IDdeleted);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    
    $stmt1 = $con->prepare("DELETE FROM `deleted_employee` WHERE `IDdeleted` = ? ");
    $stmt1->bind_param("s", $IDdeleted);
    $stmt1->execute();

    $res = [
        'status' => 'SUCCESS',
        'message' => 'Account has been successfully deleted and is permanently removed.'
    ];
    echo json_encode($res);

} else {
    $res = [
        'status' => 'ERROR',
        'message' => 'Employee not found or already Deleted'
    ];
    echo json_encode($res);
}

?>
