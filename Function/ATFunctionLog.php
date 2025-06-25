<?php
//CODE DONE
    session_start();
    include "../connection.php";
   
    if(isset($_POST['loginbtn'])) {

        $Eusername = trim($_POST['Eusername']);
        $Epassword = trim($_POST['Epassword']);

        $hashed_Epassword = hash('sha256', $Epassword);

        if($Eusername != NULL && $Epassword != NULL){
            //Database Checking
            $sql = "SELECT * FROM `employee` WHERE `Eusername` = '$Eusername' AND `Epassword` = '$hashed_Epassword'";
            $result = mysqli_query($con,$sql);
            $row = mysqli_fetch_assoc($result);
            
            if(mysqli_num_rows($result) == 1){
               
                $_SESSION['ATstatus'] = 'ATvalid';
                $_SESSION['IDemployee'] = $row['IDemployee'];

                $res = [
                    'status' => 'success',
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

