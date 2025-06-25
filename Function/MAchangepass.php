<?php
session_start();
include "../connection.php";

$IDadmin = $_SESSION['IDadmin'];

$NewPassword = trim($_POST['newpass']);
$ConfirmNewPassword = trim($_POST['connewpass']);
$OldPassword = trim($_POST['oldpass']);


if (!isset($NewPassword) || empty($NewPassword) ||
!isset($ConfirmNewPassword) || empty($ConfirmNewPassword) ||
!isset($OldPassword) || empty($OldPassword) ){

    $res = [
        'status' => 'ERROR',
        'message' => 'All Field is Required'
    ];
    echo json_encode($res);
    return;

}else if (strlen($NewPassword) < 6 || strlen($ConfirmNewPassword) < 6){
    $res = [
        'status' => 'ERROR',
        'message' => 'The new password must be at least 6 characters long.'
    ];
    echo json_encode($res);
    return;

}else if ($NewPassword != $ConfirmNewPassword){
    $res = [
        'status' => 'ERROR',
        'message' => 'New Password Not Match.'
    ];
    echo json_encode($res);
    return;
}else{


    $hashed_OldPassword = hash('sha256', $OldPassword);
    $hashed_NewPassword = hash('sha256', $NewPassword);

    $stmt = $con->prepare("SELECT * FROM `admin` WHERE `IDadmin` = ? AND `Apassword` = ?");
    $stmt->bind_param("ss",$IDadmin ,$hashed_OldPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
       
        $stmt1 = $con->prepare("UPDATE `admin` SET `Apassword`= '$hashed_NewPassword' WHERE `IDadmin` = ?");
        $stmt1->bind_param("s", $IDadmin);
        $stmt1->execute();
        $result1 = $stmt1->get_result();

        if ($stmt1->execute()) {
            $res = [
                'status' => 'SUCCESS',
                'message' => 'Change Password Success.'
            ];
            echo json_encode($res);
            return;
        }else{
            $res = [
                'status' => 'ERROR',
                'message' => 'Change Password Not Success.'
            ];
            echo json_encode($res);
            return;
        }

       
    }else{
        $res = [
            'status' => 'ERROR',
            'message' => 'The current password is incorrect.'
        ];
        echo json_encode($res);
        return;
    }


}



?>