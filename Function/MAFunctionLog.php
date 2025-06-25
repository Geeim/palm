<?php
//CODE DONE
    session_start();
    include "../connection.php";
   
    if(isset($_POST['loginbtn'])) {

        $Ausername = trim($_POST['Ausername']);
        $Apassword = trim($_POST['Apassword']);

        $hashed_Apassword = hash('sha256', $Apassword);


        if($Ausername != NULL && $Apassword != NULL)
        {
            //Database Checking
            $sql = "SELECT * FROM `admin` WHERE `Ausername` = '$Ausername' AND `Apassword` = '$hashed_Apassword'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_fetch_assoc($result);
            
            if(mysqli_num_rows($result) == 1){
               
                $_SESSION['MAstatus'] = 'MAvalid';
                $_SESSION['IDadmin'] = $row['IDadmin'];

                $res = [
                    'status' => 'success',
                    'message' => 'Welcome'
                ];
                
                echo json_encode($res);
                return;

            }else{
                $res = [
                    'status' => 'NODATA',
                    'message' => 'Wrong Username or Password. Please try again.'
                ];
                
                echo json_encode($res);
                return;
            }

        }else{
            $res = [
                'status' => 'NULL',
                'message' => 'Please provide your Username and Password.'
            ];
            
            echo json_encode($res);
            return;
        }

        

    }
?>