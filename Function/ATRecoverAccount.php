<?php
session_start();
// Enable error reporting to debug any issues
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
    $row = $result->fetch_assoc();

    $Efname = $row['Efname'];
    $Elname = $row['Elname'];
    $Emname = $row['Emname'];
    $Eemail = $row['Eemail'];
    $Eusername = $row['Eusername'];
    $Epassword = $row['Epassword'];

    // Recover the employee into the employee table
    $stmt1 = $con->prepare("INSERT INTO `employee`(`Efname`, `Elname`, `Emname`, `Eusername`, `Eemail`, `Epassword`, `IDadmin`) VALUES (?,?,?,?,?,?,?)");
    $stmt1->bind_param("sssssss", $Efname, $Elname, $Emname, $Eusername, $Eemail, $Epassword, $IDadmin);

    if ($stmt1->execute()) {
        // After recovering, delete the record from deleted_employee table
        $stmt2 = $con->prepare("DELETE FROM `deleted_employee` WHERE `IDdeleted` = ?");
        $stmt2->bind_param("s", $IDdeleted);

        if ($stmt2->execute()) {
            $res = [
                'status' => 'SUCCESS',
                'message' => 'Recovery Success.'
            ];

            echo json_encode($res);  // Send JSON response back to client
        } else {
            $res = [
                'status' => 'ERROR',
                'message' => 'Failed to delete from deleted_employee'
            ];
            echo json_encode($res);
        }
    } else {
        $res = [
            'status' => 'ERROR',
            'message' => 'Failed to recover employee'
        ];
        echo json_encode($res);
    }
} else {
    $res = [
        'status' => 'ERROR',
        'message' => 'Employee not found or already recovered'
    ];
    echo json_encode($res);
}

?>
