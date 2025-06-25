<?php
//CODE DONE
session_start();
include "../connection.php";
               

if(isset($_POST['signupbtn'])) {

    $Afname =  strtoupper($_POST['Afname']);
    $Alname = strtoupper($_POST['Alname']);
    $Amname = strtoupper($_POST['Amname']);
    $Aemail = trim($_POST['Aemail']);
    $Apassword = trim($_POST['Apassword']);
    $Acpassword = trim($_POST['Acpassword']);
    $Ausername = trim($_POST['Ausername']);

    $hashed_Apassword = hash('sha256', $Apassword);


    if(empty($Afname) || empty($Alname) || empty($Aemail) || empty($Apassword) || empty($Acpassword) || empty($Ausername)){

        $res = [
            'status' => 'EMPTY',
        ];

        echo json_encode($res);
        return;

    }elseif(strlen($Apassword) < 6) {
       
        $res = [
            'status' => 'WEAK',
        ];

        echo json_encode($res);
        return;

    }elseif($Apassword != $Acpassword){
        $res = [
            'status' => 'NOTMATCH',
        ];

        echo json_encode($res);
        return;
    }else{

        $sql = "INSERT INTO `admin`(`Afname`, `Alname`, `Amname`, `Aemail`, `Ausername`, `Apassword`) VALUES ('$Afname','$Alname','$Amname','$Aemail','$Ausername','$hashed_Apassword ')";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $NewIDadmin = mysqli_insert_id($con);
           
            $existingemployee = "SELECT COUNT(*) AS allemployee FROM employee";
            $employeeresult = $con->query($existingemployee);

            $existingadmin = "SELECT COUNT(*) AS alladmin FROM admin";
            $adminresult = $con->query($existingadmin);

            if ($employeeresult && $employeeresult->num_rows > 0) {
                $employeerow = $employeeresult->fetch_assoc();
                $allemployee = $employeerow['allemployee'];
            }else{
                $allemployee = 0;
            }

            if ($adminresult && $adminresult->num_rows > 0) {
                $adminrow = $adminresult->fetch_assoc();
                $alladmin = $adminrow['alladmin'];
            }else{
                $alladmin = 0;
            }


            if($allemployee > 0){
                $employeeFKsql = "UPDATE `employee` SET `IDadmin`='$NewIDadmin'";
                $resultEmployeeUpdate  = mysqli_query($con, $employeeFKsql);
                
                if (!$resultEmployeeUpdate) {
                    $res = [
                        'status' => 'ERROR',
                    ];
            
                    echo json_encode($res);
                    return;
                }

            }

            if($alladmin > 1){
                $DeleteOldAdmin = "DELETE FROM `admin` WHERE `IDadmin` != '$NewIDadmin'";
                $resultDeleteOldAdmin = mysqli_query($con, $DeleteOldAdmin);
                
                if (!$resultDeleteOldAdmin) {
                    $res = [
                        'status' => 'ERROR',
                    ];
            
                    echo json_encode($res);
                    return;
                }

                
            }


            $res = [
                'status' => 'SUCCESS',
            ];
    
            echo json_encode($res);
            return;



        } else {
            $res = [
                'status' => 'ERROR',
            ];
    
            echo json_encode($res);
            return;
        }
       
    }


}


?>