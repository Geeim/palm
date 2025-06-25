<?php
session_start();
include "../connection.php";

$IDemployee = $_SESSION['IDemployee'];

$NewPassword = trim($_POST['newpass']);
$ConfirmNewPassword = trim($_POST['connewpass']);
$OldPassword = trim($_POST['oldpass']);

if (!isset($NewPassword) || empty($NewPassword) ||
    !isset($ConfirmNewPassword) || empty($ConfirmNewPassword) ||
    !isset($OldPassword) || empty($OldPassword)) {

    $res = [
        'status' => 'ERROR',
        'message' => 'All fields are required.'
    ];
    echo json_encode($res);
    return;

} else if (strlen($NewPassword) < 6 || strlen($ConfirmNewPassword) < 6) {
    $res = [
        'status' => 'ERROR',
        'message' => 'The new password must be at least 6 characters long.'
    ];
    echo json_encode($res);
    return;

} else if ($NewPassword != $ConfirmNewPassword) {
    $res = [
        'status' => 'ERROR',
        'message' => 'New password does not match.'
    ];
    echo json_encode($res);
    return;
} else {

    $hashed_OldPassword = hash('sha256', $OldPassword);
    $hashed_NewPassword = hash('sha256', $NewPassword);

    // Check if the old password is correct
    $stmt = $con->prepare("SELECT * FROM `employee` WHERE `IDemployee` = ? AND `Epassword` = ?");
    $stmt->bind_param("ss", $IDemployee, $hashed_OldPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Update the password
        $stmt1 = $con->prepare("UPDATE `employee` SET `Epassword` = ? WHERE `IDemployee` = ?");
        $stmt1->bind_param("ss", $hashed_NewPassword, $IDemployee);

        if ($stmt1->execute()) {
            $res = [
                'status' => 'SUCCESS',
                'message' => 'Password changed successfully.'
            ];
            echo json_encode($res);
            return;
        } else {
            $res = [
                'status' => 'ERROR',
                'message' => 'Failed to change password.'
            ];
            echo json_encode($res);
            return;
        }

    } else {
        $res = [
            'status' => 'ERROR',
            'message' => 'The current password is incorrect.'
        ];
        echo json_encode($res);
        return;
    }
}
?>
