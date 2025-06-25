<?php
//CODE DONE
include "../connection.php";

if(isset($_POST['btnreset'])) {
    
    $Ecpassword = trim($_POST['Ecpassword']);
    $Epassword = trim($_POST['Epassword']);
    $IDemployee = $_POST['IDemployee'];

    $hashed_Epassword = hash('sha256', $Epassword);

    if(empty($Epassword) || empty($Ecpassword)){
        $res = [
            'status' => 'EMPTY'
        ];
        echo json_encode($res);
        return;
    }elseif(strlen($Epassword) < 6){
        $res = [
            'status' => 'WEAK'
        ];
        echo json_encode($res);
        return;
    }elseif($Epassword != $Ecpassword){
        $res = [
            'status' => 'NOTMATCH'
        ];
        echo json_encode($res);
        return;
    }else{

        $resetsql = "UPDATE `employee` SET `Epassword`='$hashed_Epassword' WHERE `IDemployee` = '$IDemployee'";
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