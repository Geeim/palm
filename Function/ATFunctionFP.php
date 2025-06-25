<?php
//CODE DONE
include "../connection.php";
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Check Database of systememail - MAIN EMAIL     
$sqlmainemail = "SELECT * FROM `systemdefault` WHERE `Description` = 'Main_Email'";
$sqlmainemailresult = $con->query($sqlmainemail);


if($sqlmainemailresult->num_rows > 0){
    while ($row = $sqlmainemailresult->fetch_assoc()) {
        $mainemail = $row['Systememail'];
    }
}else{
    echo $mainemail = 'ERROR MAIN EMAIL SYSTEM';
}

// FUNCTION - If Click Button Submit 
if (isset($_POST['FPbuttonAT'])) {
    $emailfpAT = trim($_POST['emailfpAT']);

    $existingemailAT = "SELECT * FROM `employee` WHERE `Eemail` = '$emailfpAT'";
    $EmailresultAT = $con->query($existingemailAT);

    if (!empty($emailfpAT)) {
        if (filter_var($emailfpAT, FILTER_VALIDATE_EMAIL)) {
            if ($EmailresultAT->num_rows > 0) {
                while ($rowAT = $EmailresultAT->fetch_assoc()) {
                    $IDemployee = $rowAT['IDemployee'];
                    $Efname = $rowAT['Efname'];
                    $Elname = $rowAT['Elname'];
                }

                date_default_timezone_set('Asia/Manila');
                $token = uniqid();
                $expired = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                $inserttoken = "INSERT INTO `one_time_token`(`token`, `expiration`) VALUES ('$token','$expired')";


                if($con->query($inserttoken) === TRUE) {
                    
                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        //SMTP SERVER
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'palm03212024@gmail.com';
                        $mail->Password = 'ilkcdawnmkumkwng';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;
    
                        //Emails Process
                        $mailTo = $emailfpAT;
                        $mailFrom = $mainemail;
    
                         //ENCODE EMAIL ID OF ADMIN and TOKEN
                         $encoded_IDemployee = base64_encode($IDemployee);
                         $encodedToken = base64_encode($token);
    
                        // Set & Recipient Function
                        $mail->setFrom($mailFrom, 'PALM');
                        $mail->addAddress($mailTo, 'Request Recovery');
    
                        $mail->isHTML(true);
                        $mail->Subject = "Reset Password";
                        
                        $mail->Body = 
                        "<html>
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
                            a.button {
                                color: white;
                            }                                            
                            .button:hover {
                                background-color: #333333;
                            }
                            .button:active {
                                background-color: #222222;
                            }
                            </style>
                            </head>
                            <body>
                                <div class='container'>
                                    <h4>Request Recovery</h4>
                                    <p>Hello $Efname $Elname,</p>
                                    <p>We received a request to recover your account. If you initiated this request, please click the button below to proceed with the recovery process. If you did not initiate this request, please ignore this email. Rest assured that we keep your email secure. Thank you.</p>
                                    <p><a class='button' href='https://palm.onrender.com/ATresetpass.php?Get=$encoded_IDemployee&token=$encodedToken'>Recover Process</a></p>
                                    <br>
                                    <p><em>This is an automated message. Please do not reply.</em></p>
                                </div>
                            </body>
                        </html>
                        ";
                                        
                        $mail->AltBody = "Hello $Efname $Elname, please click the link below to recover your account.
                                            <p><a class='button' href='https://palm.onrender.com/ATresetpass.php?Get=$encoded_IDemployee&token=$encodedToken'>Recover Process</a></p>";
                        
                        $mail->send();
                        
                        $res = [
                            'status' => 'SUCCESS',
                        ];
                        echo json_encode($res);
                        return;
                    } catch (Exception $e) {
                        $res = [
                            'status' => 'ERROR',
                            'message' => $e->getMessage(),
                        ];
                        echo json_encode($res);
                        return;
                    }

                  
                }else{
                    $res = [
                        'status' => 'ERROR',
                    ];
                    echo json_encode($res);
                    return;
                }
                
            } else {
                $res = [
                    'status' => 'NODATA',
                ];
                echo json_encode($res);
                return;
            }
        } else {
            $res = [
                'status' => 'DOMAIN',
            ];
            echo json_encode($res);
            return;
        }
    } else {
        $res = [
            'status' => 'EMPTY',
        ];
        echo json_encode($res);
        return;
    }
}
?>
