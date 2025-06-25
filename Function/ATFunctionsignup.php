<?php

//NOT FINISH!

session_start();
include "../connection.php";
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    $sqlmainemail = "SELECT * FROM `systemdefault` WHERE `Description` = 'Main_Email'";
    $sqlmainemailresult = $con->query($sqlmainemail);
    
    
    if($sqlmainemailresult->num_rows > 0){
        while ($row = $sqlmainemailresult->fetch_assoc()) {
            $mainemail = $row['Systememail'];
        }
    }else{
        echo $mainemail = 'ERROR MAIN EMAIL SYSTEM';
    }



if(isset($_POST['addAT'])) {

    $IDadmin = trim($_POST['IDadmin']);      
    $Efname =  strtoupper($_POST['Efname']);
    $Elname = strtoupper($_POST['Elname']);
    $Emname = strtoupper($_POST['Emname']);
    $Eemail = trim($_POST['Eemail']);
    $Eusername = trim($_POST['Eusername']);
    $Epassword = uniqid();
    
    $hashed_Epassword = hash('sha256', $Epassword);

    //Check SAMENAME
    $sqlcheckname = "SELECT * FROM `employee` WHERE `Efname` = '$Efname' AND `Elname` = '$Elname' AND `Emname` = '$Emname'";
    $NameResult = $con->query($sqlcheckname);
    //Check SAMEEMAIL
    $sqlcheckemail = "SELECT * FROM `employee` WHERE `Eemail` = '$Eemail'";
    $EmailResult = $con->query($sqlcheckemail);
    //Check SAMEUSERNAME
    $sqlcheckusername = "SELECT * FROM `employee` WHERE `Eusername` = '$Eusername'";
    $UsernameResult = $con->query($sqlcheckusername);
    

    //CONCDITION PART - If not Empty Field
    if(empty($Efname) || empty($Elname) || empty($Eemail) || empty($Eusername)){
        $res = [
            'status' => 'EMPTY',
        ];

        echo json_encode($res);
        return;
    }elseif($NameResult->num_rows > 0){
        $res = [
            'status' => 'SAMENAME',
        ];

        echo json_encode($res);
        return;
    }elseif($EmailResult->num_rows > 0){
        $res = [
            'status' => 'SAMEEMAIL',
        ];

        echo json_encode($res);
        return;
    }elseif($UsernameResult->num_rows > 0){
        $res = [
            'status' => 'SAMEUSERNAME',
        ];

        echo json_encode($res);
        return;
    }else{

       $sqlEmployee = "INSERT INTO `employee`(`Efname`, `Elname`, `Emname`, `Eemail`, `Eusername`, `Epassword`, `IDadmin`) VALUES ('$Efname','$Elname','$Emname','$Eemail','$Eusername','$hashed_Epassword','$IDadmin')";
       $Insertresult = mysqli_query($con, $sqlEmployee);


        if($Insertresult){
            
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();

                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'palm03212024@gmail.com';
                $mail->Password = 'ilkcdawnmkumkwng'; // Make sure this password is valid
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Updated for PHPMailer 6.x
                $mail->Port = 587;
            
                // Recipients
                $mail->setFrom($mainemail, 'Palm System'); // Sender
                $mail->addAddress($Eemail); // Receiver
            
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Created Successfully';
                $mail->Body = "
                    <html>
                        <head>
                            <style>
                            
                                body {
                                    font-family: Arial, sans-serif;
                                }
                                .container {
                                    max-width: 600px;
                                    margin: 0 auto;
                                    padding: 20px;
                                    border: 1px solid #ccc;
                                    border-radius: 5px;
                                    background-color: #f9f9f9;
                                }
                                h4 {
                                    color: #333;
                                }
                                p {
                                    margin-bottom: 15px;
                                }

                                .button {
                                    display: inline-block;
                                    padding: 10px 20px;
                                    background-color: #444444;
                                    color: white;
                                    text-decoration: none;
                                    border-radius: 5px;
                                    font-size: 14px;
                                }
                                .button:hover {
                                    background-color: #333333;
                                }
                                .button:active {
                                    background-color: #222222;
                                }

                                h4{
                                    text-align: center;
                                }

                                .click{justify-content: center;}
                                    
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <h4>Account Details</h4>
                                <p>Hi Agricultural Technician,</strong></p>

                                <p>You have an active account with us. For your security, we recommend changing your password immediately after reviewing your account details to keep your information secure. If you haven't updated it recently, please do so to prevent unauthorized access.</p>
                               
                                <span><strong>Username:</strong> $Eusername</span><br>
                                <span><strong>Password:</strong> $Epassword</span><br><br>
                                <p class='click' style='display: flex; justify-content: center;'>
                                    <a class='button' style='color: white;' href='href='https://palm.onrender.com/index.php'>Log In to PALM</a>
                                </p>

                                <br>
                                <p><em>This email is confidential. Do not share it with others.</em></p>
                            </div>
                        </body>
                    </html>";
            
                $mail->AltBody = "Username: $Eusername, Password: $Epassword"; // Plain text version
            
                // Send the email
                if ($mail->send()) {
                    $res = [
                        'status' => 'SUCCESS',
                    ];
                } else {
                    $res = [
                        'status' => 'ERROR',
                    ];
                }
            } catch (Exception $e) {
                $res = [
                    'status' => 'ERROR',
                ];
            }
            
            echo json_encode($res);
            return;
            

        }else{
            $res = [
                'status' => 'ERROR',
            ];
    
            echo json_encode($res);
            return;
        }


    }

}

?>