<?php
session_start();
include "connection.php";

// Check if token parameter exists and decode it URL CHECK! GOODSSS
if (!isset($_GET['token']) || !($decoded_token = base64_decode($_GET['token']))) {
    // Redirect to index.php if token parameter is not provided or if decoding fails
    echo "<script>window.location.href = 'index.php';</script>";
    exit; // Add exit to stop script execution
}

// Check if Get and token are valid base64 strings KUNG HINDI BINAGO ANG URL!
if (!base64_decode(($_GET['token']), true) || !base64_decode(($_GET['Get']), true)) {
    // Redirect to index.php if either Get or token is not a valid base64 string
    echo "<script>window.location.href = 'index.php';</script>";
    exit; // Add exit to stop script execution
}

$encoded_IDemployee = $_GET['Get'];
$decoded_IDemployee = base64_decode($encoded_IDemployee);
$encoded_token = $_GET['token'];
$decoded_token = base64_decode($encoded_token);

//Set Philippine Time Zone GOODSSSS
date_default_timezone_set('Asia/Manila');

//TOKEN CHECK IF MERON O WALA
$sqlchecktoken = "SELECT * FROM `one_time_token` WHERE `token` = '$decoded_token' AND expiration > NOW()";
$resultchecktoken = $con->query($sqlchecktoken);

if($resultchecktoken->num_rows > 0){
    $tokenvalidation = 1;
}else{
    $tokenvalidation = 0;
}

//Get employee table ID USE IS decoded_IDemployee
$sqlIDemployee = "SELECT * FROM `employee` WHERE `IDemployee` = '$decoded_IDemployee'";
$resultIDemployee = $con->query($sqlIDemployee);

if($resultIDemployee->num_rows > 0){
    while ($rowAT = $resultIDemployee->fetch_assoc()) {
        $IDemployee = $rowAT['IDemployee'];
        $Efname = $rowAT['Efname'];
        $Elname = $rowAT['Elname'];
        $Emname = $rowAT['Emname'];
        $Eusername = $rowAT['Eusername'];
        $Epassword = $rowAT['Epassword'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Reset Password</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/ATresetpass.css">

</head>

<body class="bg-image">
  
    <!--Container Invalid Token-->
    <div class="container invalidtoken d-none">
        <div class="row shadow d-flex justify-content-center align-items-center shadow bg-light">
            <img src="systemimg/Denied.png" alt="Logo image">
            <h4>Access Denied!</h4> 
            <p class="text-secondary">Oops! It seems there was an issue with your reset password request. Please try submitting a new request.</p>
        </div>
    </div>

    <!--SHOW FORM SUCCESS RESET-->
    <div class="container successfulreset d-none">
        <div class="row shadow d-flex justify-content-center align-items-center shadow bg-light">
            <img src="systemimg/Success.png" alt="Logo image">
            <h4>Successful!</h4> 
            <p class="text-secondary">Reset Password Successful! Try to Sign In now. Kindly wait a moment while we redirect you to the Sign In page.</p>
        </div>
    </div>

    <!--SHOW FORM RESET FORM-->
    <div class="resetpassform d-none container mt-5 mb-5 pt-5 d-flex justify-content-center align-items-center">
        <div class="row mt-3 rowone shadow bg-light">
            
            <div class="col-5 left">
                <!--Image Design-->
            </div>

            <div class="col-7 right">
                <h4 class="mb-2 header">Reset Password</h4>
                
                <form id="formreset">
                    <div class="emptyfields mb-2 text-center d-none" style="color: red; font-size:12px;"></div>
                    <p class="text-center text-secondary mt-3">Username: <?php echo $Eusername?></p>

                    <!--FOR PASSWORD-->
                    <div class="form-group mb-3">
                        <label class="inputLogLabels text-secondary mb-1" for="Apassword">New Password</label>
                            <div class="PasswordReset input-group">
                            <input type="password" name="Epassword" class="form-control" id="Epassword" placeholder="Enter New Password" oninput="this.value = this.value.replace(/\s+/g, '');">
                            <label class="bi bi-eye input-group-text" id="Epassword-toggle-icon" style="color:#827d7d;">
                            </div>
                        <div class="Errorpass mt-1 mb-2 d-none" style="color: red; font-size:12px;"></div> 
                    </div>
                    
                    
                    <!--For EYE See Pass-->
                    <script>
                        const EtogglePassword = document.querySelector('#Epassword-toggle-icon');
                        const Epassword = document.querySelector('#Epassword');

                        EtogglePassword.addEventListener('click', () => {
                        const type = Epassword.getAttribute('type') === 'password' ? 'text' : 'password';
                        Epassword.setAttribute('type', type);
                        EtogglePassword.classList.toggle('bi-eye');
                        EtogglePassword.classList.toggle('bi-eye-slash');
                        });
                    </script>

                    <!--FOR CONFIRM PASSWORD-->
                    <div class="form-group">
                        <label class="inputLogLabels text-secondary mb-1" for="Ecpassword">Confirm New Password</label>
                        <input type="password" class="form-control" name="Ecpassword" id="Ecpassword" placeholder="Enter New Password" oninput="this.value = this.value.replace(/\s+/g, '');">
                        <div class="ErrorCpass mt-1 d-none" style="color: red; font-size:12px;"></div>
                    </div>

                    <div class="Resetbtn text-center mt-4">
                        <input type="submit" name="btnreset" value="SUBMIT">
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

            }else {
            $('.resetpassform').removeClass('d-none'); 
            }
        });


        $(document).on('submit', '#formreset', function (e) {
            e.preventDefault();

            var ATformDataReset = new FormData(this);
            ATformDataReset.append("btnreset", true);
            ATformDataReset.append("IDemployee", "<?php echo $IDemployee; ?>");


            $.ajax({
                type: "POST",
                url: "Function/ATFunctionresetpass.php",
                data: ATformDataReset,
                processData: false,
                contentType: false,

                success: function(response) {

                var res = jQuery.parseJSON(response);

                if(res.status == 'EMPTY'){
                    $('.Errorpass').addClass('d-none');
                    $('.ErrorCpass').addClass('d-none');
                    $('.emptyfields').removeClass('d-none');
                    $('.emptyfields').text('All Field is Required!');
                }else if(res.status == 'WEAK'){
                    $('.Errorpass').removeClass('d-none');
                    $('.ErrorCpass').addClass('d-none');
                    $('.emptyfields').addClass('d-none');
                    $('.Errorpass').text('Weak Password.');
                }else if(res.status == 'NOTMATCH'){
                    $('.Errorpass').addClass('d-none');
                    $('.ErrorCpass').removeClass('d-none');
                    $('.emptyfields').addClass('d-none');
                    $('.ErrorCpass').text('Password Not Match.');
                }else if(res.status == 'ERROR'){
                    $('.Errorpass').addClass('d-none');
                    $('.ErrorCpass').addClass('d-none');
                    $('.emptyfields').removeClass('d-none');
                    $('.emptyfields').text('ERROR CODE !');
                }else if(res.status == 'SUCCESS'){
                    $('.emptyfields').addClass('d-none');
                    $('.Errorpass').addClass('d-none');
                    $('.ErrorCpass').addClass('d-none');
                    $('.resetpassform').addClass('d-none');
                    
                    $('.successfulreset').removeClass('d-none');

                    setTimeout(function() {
                        $('.successfulreset').addClass('d-none');
                            window.location.href = "Function/deletetoken.php?token=<?php echo $encoded_token?>";
                    }, 3000);
                }else{
                    $('.Errorpass').addClass('d-none');
                    $('.ErrorCpass').addClass('d-none');
                    $('.emptyfields').removeClass('d-none');
                    $('.emptyfields').text('CODE ERROR');
                }

                }, 
                error: function() {
                $('.emptyfields').removeClass('d-none');
                $('.emptyfields').text('ERROR: ERROR AJAX!');
                }

            });
        });
    </script>

</body>
</html>