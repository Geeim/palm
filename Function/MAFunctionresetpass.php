<?php
//CODE DONE
include "../connection.php";

if(isset($_POST['btnreset'])) {
    
    $Acpassword = trim($_POST['Acpassword']);
    $Apassword = trim($_POST['Apassword']);
    $IDadmin = $_POST['IDadmin'];

    $hashed_Apassword = hash('sha256', $Apassword);

    if(empty($Apassword) || empty($Acpassword)){
        $res = [
            'status' => 'EMPTY'
        ];
        echo json_encode($res);
        return;
    }elseif(strlen($Apassword) < 6){
        $res = [
            'status' => 'WEAK'
        ];
        echo json_encode($res);
        return;
    }elseif($Apassword != $Acpassword){
        $res = [
            'status' => 'NOTMATCH'
        ];
        echo json_encode($res);
        return;
    }else{

        $resetsql = "UPDATE `admin` SET `Apassword`='$hashed_Apassword' WHERE `IDadmin` = '$IDadmin'";
        $resultreset = mysqli_query($con, $resetsql);

            if ($resultreset) {
                $res = [
                    'status' => 'SUCCESS'
                ];
                echo json_encode($res);
                return;

            }else{
                $res = [
                    'status' => 'ERROR'
                ];
                echo json_encode($res);
                return;
            }
    }
   
}

?>