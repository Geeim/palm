<?php
//FINISH CODE
session_start();

    if(empty($_SESSION['ATstatus']) || $_SESSION['ATstatus'] == 'ATinvalid'){
        $_SESSION['ATstatus'] = 'ATinvalid';
    }

    if(empty($_SESSION['MAstatus']) || $_SESSION['MAstatus'] == 'MAinvalid'){
        $_SESSION['MAstatus'] = 'MAinvalid';
    }

    if($_SESSION['ATstatus'] == 'ATvalid'){
        echo "<script>window.location.href = '/ATDashboard.php';</script>";
    }else if($_SESSION['MAstatus'] == 'MAvalid'){
        echo 
        "<script>window.location.href = '/MADashboard.php';</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PALM Sign-In</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/index.css">

</head>

<body class="bg-image">

    <div class="container mt-4 mb-5 pt-5 d-flex justify-content-center align-items-center main">
        <div class="row shadow bg-light">
            
            <div class="col-6 left">
                <div class="overlay-Image text-white text-center">
                    <img src="systemimg/PALMWL.png" class="img-fluid" alt="PALM Logo">
                </div>
                <div class="overlay-Imagetext text-white text-center">
                    <p class="mb-3">Produksyon at Ani sa Lungsod ng Maragondon</p>
                    <input type="submit" id="btnsignup" class="d-none btn btn-modify shadow" value="Sign Up" data-bs-toggle="modal" data-bs-target="#AdminCreateAccountEmail">
                </div>
            </div>
            
            <div class="col-6 right">   
                <div id="AT">
                    <div class="topheader text-center">
                        <img src="systemimg/RiceLogo.png" class="imglogo mb-4" alt="Logo image">    
                        <h5 class="mb-3">Agricultural Technician</h5>
                    </div>
                    
                    <div id="errorMessage" class="alert alert-warning d-none"></div>

                    <form id="FormAT">
                        <label for="Eusername" class="inputLogLabels text-secondary mb-1">Username</label>
                        <div class="Userlog input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" name="Eusername" id="Eusername" class="form-control" placeholder="Enter Username">
                        </div>

                        <label for="Epassword" class="inputLogLabels text-secondary mb-1">Password</label>
                        <div class="PassLog input-group mb-1">
                            <span class="input-group-text"><i class="bi bi-unlock-fill"></i></span>
                            <input type="password" name="Epassword" class="form-control" id="Epassword" placeholder="Enter Password">
                            <label for="Epassword" class="bi bi-eye input-group-text" id="password-toggle-icon" style="color:#827d7d;">

                            <script>
                                const togglePassword = document.querySelector('#password-toggle-icon');
                                const Epassword = document.querySelector('#Epassword');

                                togglePassword.addEventListener('click', () => {
                                    const type = Epassword.getAttribute('type') === 'password' ? 'text' : 'password';
                                    Epassword.setAttribute('type', type);
                                    togglePassword.classList.toggle('bi-eye');
                                    togglePassword.classList.toggle('bi-eye-slash');
                                });
                            </script>   

                        </div> 
                        <div class="forgotpass text-end mb-4">
                                <a class="link-offset-light" data-bs-toggle="modal" data-bs-target="#ATForgotPass">Forgot Password?</a>
                            </div>

                        <div class="login text-center mt-4">
                            <input type="submit" name="loginbtn" value="LOGIN ">
                        </div>
                    </form>

                    <div class="signupuser text-center mt-4">
                        <a class="btn btn-link" id="btnAT">
                            Municipal Agriculturist <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>


                <div id="MA" class="d-none">
                    <div class="topheader text-center">
                        <img src="systemimg/RiceLogo.png" class="imglogo mb-4" alt="Logo image">    
                        <h5 class="mb-3">Municipal Agriculturist</h5>
                    </div>

                    <div id="AerrorMessage" class="alert alert-warning d-none"></div>

                    <form id="FormMA">
                        <label for="Ausername" class="inputLogLabels text-secondary mb-1">Username</label>
                        <div class="Userlog input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                            <input type="text" name="Ausername" id="Ausername" class="form-control" placeholder="Enter Username">
                        </div>

                        <label for="Apassword" class="inputLogLabels text-secondary mb-1">Password</label>
                        <div class="PassLog input-group mb-1">
                            <span class="input-group-text"><i class="bi bi-unlock-fill"></i></span>
                            <input type="password" name="Apassword" class="form-control" id="Apassword" placeholder="Enter Password">
                            <label for="Apassword" class="bi bi-eye input-group-text" id="Apassword-toggle-icon" style="color:#827d7d;">

                            <script>
                                const AtogglePassword = document.querySelector('#Apassword-toggle-icon');
                                const Apassword = document.querySelector('#Apassword');

                                AtogglePassword.addEventListener('click', () => {
                                    const type = Apassword.getAttribute('type') === 'password' ? 'text' : 'password';
                                    Apassword.setAttribute('type', type);
                                    AtogglePassword.classList.toggle('bi-eye');
                                    AtogglePassword.classList.toggle('bi-eye-slash');
                                });
                            </script>

                        </div> 
                        <div class="forgotpass text-end mb-4">
                                <a class="link-offset-light" data-bs-toggle="modal" data-bs-target="#MAForgotPass">Forgot Password?</a>
                            </div>

                        <div class="login text-center mt-4">
                            <input type="submit" name="loginbtn" value="LOGIN ">
                        </div>
                    </form>

                    <div class="signupuser text-center mt-4">
                        <a class="btn btn-link" id="btnMA">
                           <span>Agricultural Technician </span><i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>
 
            </div>

        </div>
    </div>
  

<!--All Modal in This Page-->
    
    <!-- Modal Create Account-->
    <div class="modal fade shadow mt-4 p-5" id="AdminCreateAccountEmail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex justify-content-center align-items-center">
                        <img src="systemimg/PALMLogo.png" class="imglogo mb-4" alt="Logo Image">
                    </div>

                    <form id="MACA">
                        <div class="text-start">
                            <label for="emailca" class="inputLogLabels text-secondary mb-1">Email Address</label>
                        </div>

                        <div class="BG-ModalInput-CA input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-envelope-fill color-icon-ca"></i></span>
                            <input type="email" name="emailca" id="emailca" class="form-control" placeholder="Enter Your Email Address">
                        </div>

                        <div class="Text-Error-CA d-none text-danger text-center">
                            <p></p>
                        </div>

                        <div class="Text-Esent-CA d-none text-success text-center">
                            <p></p>
                        </div>

                        <div class="text-center text-secondary mb-4">
                            <p>Enter your email, then click the button below to send your account creation request to the system email.</p>
                        </div>

                        <div class="btn-CA text-center">
                            <input type="submit" id="CAbutton" name="btnCA" value="CLICK TO SEND">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!--Modal Forgot Password MA-->
    <div class="modal fade shadow p-5" id="MAForgotPass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="text-center">
                    <label for="emailfp" class="text-secondary mb-1">Municipal Agriculturist</label>
                </div>
                    
                <div class="modal-body">

                    <div class="imgFP d-flex justify-content-center align-items-center">
                        <img src="systemimg/FDLogo.png" alt="Lock Logo image">
                    </div>

                    <form id="MAFP">
                        <div class="text-start">
                            <label for="emailfp" class="inputLogLabels text-secondary mb-1">Email Address</label>
                        </div>

                        <div class="BG-ModalInput-FP input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-envelope-fill color-icon-fp"></i></span>
                            <input type="email" name="emailfp" id="emailfp" class="form-control" placeholder="Enter Email Address">
                        </div>

                        <div class="Text-Error-FP d-none text-center text-danger">
                            <p></p>
                        </div>

                        <div class="Text-Esent-FP d-none text-center text-success">
                            <p></p>
                        </div>

                        <div class="text-center text-secondary mb-4">
                            <p>Fill up and Click Button below to reset your password. This will email the reset link.</p>
                        </div>

                        <div class="btn-fp text-center">
                            <input type="submit" id="FPbutton" name="FPbutton" value="CLICK TO SEND">
                        </div>
                    </form>

                </div>
                      
            </div> 
        </div>
    </div>


    <!--Modal Forgot Password AT-->
    <div class="modal fade shadow p-5" id="ATForgotPass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="text-center">
                    <label for="emailfpAT" class="text-secondary mb-1">Agricultural Technician</label>
                </div>
                    
                <div class="modal-body">

                    <div class="imgFP d-flex justify-content-center align-items-center">
                        <img src="systemimg/FDLogo.png" alt="Lock Logo image">
                    </div>

                    <form id="ATFP">
                        <div class="text-start">
                            <label for="emailfp" class="inputLogLabels text-secondary mb-1">Email Address</label>
                        </div>

                        <div class="BG-ModalInput-FP input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-envelope-fill color-icon-fp"></i></span>
                            <input type="email" name="emailfpAT" id="emailfpAT" class="form-control" placeholder="Enter Email Address">
                        </div>

                        <div class="ATText-Error-FP d-none text-center text-danger">
                            <p></p>
                        </div>

                        <div class="ATText-Esent-FP d-none text-center text-success">
                            <p></p>
                        </div>

                        <div class="text-center text-secondary mb-4">
                            <p>Fill up and Click Button below to reset your password. This will email the reset link.</p>
                        </div>

                        <div class="btn-fp text-center">
                            <input type="submit" id="FPbuttonAT" name="FPbuttonAT" value="CLICK TO SEND">
                        </div>
                    </form>

                </div>
                      
            </div> 
        </div>
    </div>

    <!-- Script files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!--All Script in This Page-->
    <script src="systemjs/index.js" defer></script>

</body>
</html>