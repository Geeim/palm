<?php
session_start();
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

    //Check Database of systememail - EMAIL SUPPORT     
    $sqlsupportemail = "SELECT * FROM `systemdefault` WHERE `Description` = 'Email_Support'";
    $sqlsupportemailresult = $con->query($sqlsupportemail);
    
    
    if($sqlsupportemailresult->num_rows > 0){
        while ($row = $sqlsupportemailresult->fetch_assoc()) {
            $supportemail = $row['Systememail'];
        }
    }else{
        echo $supportemail = 'ERROR MAIN EMAIL SYSTEM';
    }



//FUNCTION - If Click Button Submit CA
if(isset($_POST['btnCA'])) {

    $emailca = trim($_POST['emailca']);

        if(!empty($emailca)){

            if(filter_var($emailca, FILTER_VALIDATE_EMAIL)){

                date_default_timezone_set('Asia/Manila');
                $token = uniqid();
                $expired = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                $inserttoken = "INSERT INTO `one_time_token`(`token`, `expiration`) VALUES ('$token','$expired')";
                
                if($con->query($inserttoken) === TRUE) {

                    $mail = new PHPMailer(true);
                
                    $mail->isSMTP();
    
                    //SMTP SERVER
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'palms7155@gmail.com';
                    $mail->Password = 'knhkfhrptdktnkan';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
    
                    //Emails Process
                    $mailTo = $mainemail;//Receiver
                    $mailFrom = $supportemail; //Sender
    
                    // Set & Recipient Function
                    $mail->addAddress($mailTo, 'Palm System'); //Receiver
                    $mail->setFrom($mailFrom, 'Palm Support'); //Sender
    
                    $mail->isHTML(true);
                    $mail->Subject = "Request Sign-Up";
    
    
                    //ENCODE THE EMAIL - mailTo & Token - Encode to not View in URL
                    $encoded_emailca = base64_encode($emailca);
                    $encodedToken = base64_encode($token);
         
                    $mail->Body = 
                    "
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
                            </style>
                        </head>
    
                        <body>
                            <div class='container'>
                                <h4>Admin Create Account Process</h4>
                                <p>Dear Admin,</p>
                                <p>The system has received a sign-up request from the email address $emailca. If this request was initiated by you, kindly click the button below to proceed with creating an account. If you did not initiate this request, please ignore this email. Rest assured that we keep your email secure. Thank you.</p>
                                <p><a class='button' style='color: white;' href='https://palm-kgsh.onrender.com/MAsignuppage.php?Get=$encoded_emailca&token=$encodedToken'>Proceed to Account Setup</a></p>
                                <br>
                                <p><em>Please dont share this mail.</em></p>
                            </div>
                        </body>
                    </html>";
    
                    $mail->AltBody = 
                        "$emailca if this is valid address click the button below to proceed Account Setup.
                        <p><a href='https://palm-kgsh.onrender.com/MAsignuppage.php?Get=$encoded_emailca&token=$encodedToken''>Link to Proceed to Account Setup</a></p>
                        ";
    
                
                    if($mail->send()){
                        $res = [
                            'status' => 'SUCCESS',
                        ];
                    
                        echo json_encode($res);
                        return;
    
                    }else{
                        $res = [
                            'status' => 'ERROR',
                        ];
                    
                        echo json_encode($res);
                        return;
                    }
    
                  
                } else {
                    $res = [
                        'status' => 'TOKEN INSERTING ERROR',
                    ];
            
                    echo json_encode($res);
                    return;
                }

            
            }else{
                $res = [
                    'status' => 'DOMAIN',
                ];
        
                echo json_encode($res);
                return;
            }

        }elseif (empty($emailca)) {
            $res = [
                'status' => 'EMPTY',
            ];

            echo json_encode($res);
            return;
        }else{
            $res = [
                'status' => 'CODE ERROR',
            ];

            echo json_encode($res);
            return;
        }
}

?>