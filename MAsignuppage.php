<?php
session_start();
include "connection.php";

// Check if token parameter exists and decode it URL CHECK! GOODSSS
if (!isset($_GET['token']) || !($decoded_token = base64_decode($_GET['token']))) {
    // Redirect to index.php if token parameter is not provided or if decoding fails
    echo "<script>window.location.href = 'index.php';</script>";
    exit; // Add exit to stop script execution
}

$encoded_emailca = $_GET['Get'];
$decoded_email = base64_decode($encoded_emailca);
$encoded_token = $_GET['token'];
$decoded_token = base64_decode($encoded_token);

// Check if Format of Email in URL is not change GOODSSSS
if (!filter_var($decoded_email, FILTER_VALIDATE_EMAIL)) {
    // Redirect to index.php if 'Get' parameter is not in the expected format
    echo "<script>window.location.href = 'index.php';</script>";
    exit; // Add exit to stop script execution
}

//Set Philippine Time Zone GOODSSSS
date_default_timezone_set('Asia/Manila');

$sqlchecktoken = "SELECT * FROM `one_time_token` WHERE `token` = '$decoded_token' AND expiration > NOW()";
$resultchecktoken = $con->query($sqlchecktoken);


if($resultchecktoken->num_rows > 0){
    $tokenvalidation = 1;
}else{
    $tokenvalidation = 0;
}

// Check admin count
$existingadmin = "SELECT COUNT(*) AS admin_count FROM admin";
$adminresult = $con->query($existingadmin);

if ($adminresult && $adminresult->num_rows > 0) {
    $row = $adminresult->fetch_assoc();
    $admin_count = $row['admin_count'];
}else{
    $admin_count = 0;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PALM Sign-Up</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/MAsignuppage.css">

</head>
<body class="bg-image">
    <!--Container Invalid Token-->
    <div class="container invalidtoken d-none">
        <div class="row shadow d-flex justify-content-center align-items-center shadow bg-light">
            <img src="systemimg/Denied.png" alt="Logo image">
            <h4>Access Denied!</h4> 
            <p class="text-secondary">Oops! It seems there was an issue with your sign-up request. Please try submitting a new request.</p>
        </div>
    </div>

    <!--Container Confirmation Update Admin-->
    <div class="container confirmation d-none">
        <div class="row d-flex justify-content-center align-items-center shadow bg-light">
            <img src="systemimg/Confirm.png" alt="Logo image">
            <p>Caution: An existing Municipal Agriculturist account has been detected in the system. 
                Are you ready to replace it with a new Municipal Agriculturist?</p>
            
            <div class="button">
                <button id="btnYesConfirmation">Yes</button>
                <button id="btnNoConfirmation">No</button>
            </div>
        </div>
    </div>

    <!-- Container Cancel SignUp-->
    <div class="container cancelSU d-none">
        <div class="row shadow d-flex justify-content-center align-items-center shadow bg-light">
            <img src="systemimg/Cancel.png" alt="Logo image">
            <h4>Cancel Sign Up.</h4> 
            <p class="text-secondary">Cancel Sign Up Success. Please wait a moment to be redirected to the Sign In page</p>
        </div>
    </div>

    <!-- Container SUCCESS SignUp-->
    <div class="container successSU d-none">
        <div class="row shadow d-flex justify-content-center align-items-center shadow bg-light">
            <img src="systemimg/Success.png" alt="Logo image">
            <h4>Sign Up Successful!</h4> 
            <p class="text-secondary">Account Creation Successful! Please proceed to Sign In now. Kindly wait a moment while we redirect you to the Sign In page.</p>
        </div>
    </div>


    <!--FORM - Confirmation Yes or Token is Valid-->
    <div class="form d-none container mt-5 mb-5 pt-5 d-flex justify-content-center align-items-center main">
        <div class="row mt-3 rowone shadow bg-light">
            
            <div class="col-5 left">
                <!--Image Design-->
            </div>

            <div class="col-7 right">
                <div class="text-start textdesign mb-3">
                    <h4>Create Account</h4>
                    <div class="Errorfield mt-1 d-none"></div>
                </div>

                <form id="MASU">
                    <div class="row fullname mb-2">
                        <div class="col-5">
                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="Afname">First Name</label>
                                <input  type="text" class="form-control" name="Afname" id="Afname" placeholder="Enter First Name" oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="Alname">Last Name</label>
                                <input type="text" class="form-control" name="Alname" id="Alname" placeholder="Enter last name" oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="Amname">M.I</label>
                                <input type="text" class="form-control" name="Amname" id="Amname" maxlength="1" placeholder="M.I" oninput="this.value = this.value.toUpperCase();">
                            </div>
                        </div>

                        <div class="col-6 mt-2">
                            <div class="form-group inputemail">
                                <label class="inputLogLabels text-secondary" for="Aemail">Email</label>
                                <input type="text" class="form-control" name="Aemail" id="Aemail" value="<?php echo $decoded_email?>" readonly>
                            </div>
                        </div>


                        <div class="col-6 mt-2">
                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="Ausername">Username</label>
                                <input type="text" class="form-control" name="Ausername" id="Ausername" placeholder="Create Username" oninput="this.value = this.value.replace(/\s+/g, '');">
                            </div>
                        </div>

                        <div class="col-6 mt-2">
                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="Apassword">Password</label>
                                <input type="password" class="form-control" name="Apassword" id="Apassword" placeholder="Create Password" oninput="this.value = this.value.replace(/\s+/g, '');">
                                <div class="Errorpass mt-1 d-none" style="color: red; font-size:12px;"></div>
                            </div>
                        </div>

                        <div class="col-6 mt-2">
                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="Acpassword">Confirm Password</label>
                                <input type="password" class="form-control" name="Acpassword" id="Acpassword" placeholder="Confirm Password" oninput="this.value = this.value.replace(/\s+/g, '');">
                                <div class="ErrorCpass mt-1 d-none" style="color: red; font-size:12px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="Signup text-center mt-4">
                        <input type="submit" name="signupbtn" value="SIGN UP ">
                    </div>
                </form>
                
            </div>

        </div>
    </div>

    <!-- Script files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>


    <script>
        $(document).ready(function() {
            if(<?php echo $tokenvalidation;?> == 0){
                
                //No Token Found OK na
                $('.invalidtoken').removeClass('d-none');

                setTimeout(function() {
                window.location.href = "index.php";
                }, 3000);

            } else if(<?php echo $admin_count; ?> > 0) {
                $('.confirmation').removeClass('d-none');

                $('#btnYesConfirmation').click(function() {
                    $('.confirmation').addClass('d-none');
                    $('.form').removeClass('d-none');
                });

                $('#btnNoConfirmation').click(function() {
                    $('.confirmation').addClass('d-none');
                    $('.cancelSU').removeClass('d-none');
                    
                    setTimeout(function() {
                    window.location.href = "deletetoken.php?token=<?php echo $encoded_token?>";
                    }, 3000);
                });

            } else {
            $('.form').removeClass('d-none'); 
            }
        });

        

    $(document).on('submit', '#MASU', function (e) {
        e.preventDefault();

        var MAformDataCA = new FormData(this);
        MAformDataCA.append("signupbtn", true);

        $.ajax({
            type: "POST",
            url: "Function/MAFunctionsignup.php",
            data: MAformDataCA,
            processData: false,
            contentType: false,


            success: function(response) {

                var res = jQuery.parseJSON(response);
                
                    if(res.status == 'EMPTY') {
                        $('.Errorfield').removeClass('d-none');
                        $('.Errorpass').addClass('d-none');
                        $('.ErrorCpass').addClass('d-none');
                        $('.Errorfield').text('ATTENTION: Please Fill Up All Fields.');
                    }else if(res.status == 'WEAK'){
                        $('.Errorfield').addClass('d-none');
                        $('.Errorpass').removeClass('d-none');
                        $('.Errorpass').text('Weak Password.');
                    }else if(res.status == 'NOTMATCH'){
                        $('.Errorfield').addClass('d-none');
                        $('.Errorpass').addClass('d-none');
                        $('.ErrorCpass').removeClass('d-none');
                        $('.ErrorCpass').text('Password Not Match.');
                    }else if(res.status == 'SUCCESS'){
                        $('.Errorfield').addClass('d-none');
                        $('.Errorpass').addClass('d-none');
                        $('.ErrorCpass').addClass('d-none');
                        $('.form').addClass('d-none');
                        $('.successSU').removeClass('d-none');

                        setTimeout(function() {
                          $('.successSU').addClass('d-none');
                          window.location.href = "Function/deletetoken.php?token=<?php echo $encoded_token?>";
                        }, 3000);


                   }else{
                        $('.Errorfield').removeClass('d-none');
                        $('.Errorfield').text('ERROR: CHECK MAFunction.php');
                    }

                
            },
            error: function() {
                $('.InputError').removeClass('d-none');
                $('.InputError').text('ERROR AJAX!');
                }
        
        });

    });
    

    </script>

</body>
</html>