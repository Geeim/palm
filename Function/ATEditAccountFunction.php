<?php
session_start();
include '../connection.php';

$IDemployee = $_SESSION['IDemployee'];
$Efname = strtoupper($_POST['Efname']);
$Elname = strtoupper($_POST['Elname']);
$Emname = strtoupper($_POST['Emname']);
$Eusername = $_POST['Eusername'];
$Eemail = $_POST['Eemail'];

$PreparedBy_NEW = trim($Efname. ' ' . $Emname . '. ' . $Elname);



if(empty($Efname) || empty($Elname) || empty($Eusername) || empty($Eemail)){
    $res = [
        'status' => 'ERROR',
        'message' => 'All Field Required.',
    ];
    echo json_encode($res);
    return;
}else{

    $stmt = $con->prepare("SELECT * FROM `employee` WHERE `IDemployee` = ?");
    $stmt->bind_param("s", $IDemployee);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $OLDEfname = $row['Efname'];
        $OLDElname = $row['Elname'];
        $OLDEmname = $row['Emname'];

        $PreparedBy_OLD = trim($OLDEfname. ' ' . $OLDEmname . '. ' . $OLDElname);

        $stmt1 = $con->prepare("UPDATE `planting` SET `prepared_by` = ? WHERE prepared_by = ?");
        $stmt1->bind_param("ss", $PreparedBy_NEW, $PreparedBy_OLD);
        $stmt2 = $con->prepare("UPDATE `harvesting` SET `prepared_by` = ? WHERE prepared_by = ?;");
        $stmt2->bind_param("ss", $PreparedBy_NEW, $PreparedBy_OLD);

        $stmt1_result = $stmt1->execute();
        $stmt2_result = $stmt2->execute();

        if($stmt1_result && $stmt2_result){

            $stmt3 = $con->prepare("UPDATE `employee` SET `Efname` = ?, `Elname` = ?, `emname` = ?, `Eemail` = ?, `Eusername` = ? WHERE `IDemployee` = ?");
            $stmt3->bind_param("ssssss", $Efname, $Elname, $Emname, $Eemail, $Eusername, $IDemployee);
            $stmt3_result = $stmt3->execute();

            if($stmt3_result){
                $res = [
                    'status' => 'SUCCESS',
                    'message' => 'Update Account Success',
                ];
                echo json_encode($res);
                return;
            }else{
                $res = [
                    'status' => 'ERROR',
                    'message' => 'Update Account Error',
                ];
                echo json_encode($res);
                return;
            }
            

        }else{
            $res = [
                'status' => 'ERROR',
                'message' => 'Update HP Error',
            ];
            echo json_encode($res);
            return;
        }



    }else{
        $res = [
            'status' => 'ERROR',
            'message' => 'No Accout Found.',
        ];
        echo json_encode($res);
        return;
    }

}

