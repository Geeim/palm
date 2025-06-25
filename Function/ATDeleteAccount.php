<?php

include "../connection.php";

if (!isset($_POST['IDemployee']) || empty($_POST['IDemployee'])) {
    $res = [
        'status' => 'ERROR',
        'message' => 'Employee ID is required'
    ];
    echo json_encode($res);
    return;
}

$IDemployee = trim($_POST['IDemployee']);

// Kukunin ang employee details mula sa employee table
$stmt = $con->prepare("SELECT * FROM `employee` WHERE `IDemployee` = ?");
$stmt->bind_param("s", $IDemployee);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Ililipat ang employee data sa deleted_employee table
    $DEfname = $row['Efname'];
    $DElname = $row['Elname'];
    $DEmname = $row['Emname'];
    $DEemail = $row['Eemail'];
    $DEusername = $row['Eusername'];
    $DEpassword = $row['Epassword'];

    // Insert data sa deleted_employee table
    $stmt1 = $con->prepare("INSERT INTO `deleted_employee`(`Efname`, `Elname`, `Emname`, `Eusername`, `Eemail`, `Epassword`) VALUES (?,?,?,?,?,?)");
    $stmt1->bind_param("ssssss", $DEfname, $DElname, $DEmname, $DEusername, $DEemail, $DEpassword);

    if ($stmt1->execute()) {
        // Pagkatapos mailipat sa deleted_employee, tatanggalin ang record sa employee table
        $stmt2 = $con->prepare("DELETE FROM `employee` WHERE `IDemployee` = ?");
        $stmt2->bind_param("s", $IDemployee);

        if ($stmt2->execute()) {
            $res = [
                'status' => 'SUCCESS',
            ];
            echo json_encode($res);
        } else {
            $res = [
                'status' => 'ERROR',
                'message' => 'Failed to delete employee from the original table'
            ];
            echo json_encode($res);
        }
    } else {
        $res = [
            'status' => 'ERROR',
            'message' => 'Failed to archive employee'
        ];
        echo json_encode($res);
    }
} else {
    $res = [
        'status' => 'ERROR',
        'message' => 'Employee not found'
    ];
    echo json_encode($res);
}

?>
