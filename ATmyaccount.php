<?php
include 'connection.php';

require('Function/ATsession.php');
include "connection.php";

$ATstatus = $_SESSION['ATstatus'];
$IDemployee = $_SESSION['IDemployee'];




// Condition for Session for DASHBOARD VIEW ONLY
if(empty($_GET['validation']) || $_GET['validation'] != true){
    if(empty($_SESSION['ATstatus']) || $_SESSION['ATstatus'] == 'ATinvalid'){
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>window.location.href = 'ATDashboard.php';</script>";
    }
}

$employeesql = "SELECT * FROM `employee` WHERE `IDemployee` = '$IDemployee'";
$employeeresult = mysqli_query($con, $employeesql); 

if($employeeresult->num_rows > 0){
    $row = $employeeresult->fetch_assoc();
    
    $Efname = $row['Efname'];
    $Elname = $row['Elname'];
    $Emname = $row['Emname'];
    $Eemail = $row['Eemail'];
    $Eusername = $row['Eusername'];
    $Epassword = $row['Epassword'];
}




    $months = [
        1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June",
        7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December"
    ];


// HARVESTING
    $HILSQL = "SELECT * FROM `harvesting` WHERE landtype = 1 ORDER BY `year` DESC, `month` DESC LIMIT 1";
    $HILRES = mysqli_query($con, $HILSQL);
    $HRLSQL = "SELECT * FROM `harvesting` WHERE landtype = 2 ORDER BY `year` DESC, `month` DESC LIMIT 1";
    $HRLRES = mysqli_query($con, $HRLSQL);
    $HULSQL = "SELECT * FROM `harvesting` WHERE landtype = 3 ORDER BY `year` DESC, `month` DESC LIMIT 1";
    $HULRES = mysqli_query($con, $HULSQL);

// PLANTING
    $PILSQL = "SELECT * FROM `planting` WHERE landtype = 1 ORDER BY `year` DESC, `month` DESC LIMIT 1";
    $PILRES = mysqli_query($con, $PILSQL);
    $PRLSQL = "SELECT * FROM `planting` WHERE landtype = 2 ORDER BY `year` DESC, `month` DESC LIMIT 1";
    $PRLRES = mysqli_query($con, $PRLSQL);
    $PULSQL = "SELECT * FROM `planting` WHERE landtype = 3 ORDER BY `year` DESC, `month` DESC LIMIT 1";
    $PULRES = mysqli_query($con, $PULSQL);


$HIL = "No Data Found.";
$HRL = "No Data Found.";
$HUL = "No Data Found.";
$PIL = "No Data Found.";
$PRL = "No Data Found.";
$PUL = "No Data Found.";

    if($HILRES->num_rows > 0){
        $row = $HILRES->fetch_assoc();
        $year = $row['year'];
        $month = $row['month'];
        $range_date = $row['range_date'];
        $monthName = isset($months[$month]) ? $months[$month] : "Invalid Month";
        $HIL = "$monthName $range_date $year"; 
    }

    if($HRLRES->num_rows > 0){
        $row = $HRLRES->fetch_assoc();
        $year = $row['year'];
        $month = $row['month'];
        $range_date = $row['range_date'];
        $monthName = isset($months[$month]) ? $months[$month] : "Invalid Month";
        $HRL = "$monthName $range_date $year"; 
    }

    if($HULRES->num_rows > 0){
        $row = $HULRES->fetch_assoc();
        $year = $row['year'];
        $month = $row['month'];
        $range_date = $row['range_date'];
        $monthName = isset($months[$month]) ? $months[$month] : "Invalid Month";
        $HUL = "$monthName $range_date $year"; 
    }

    if($PILRES->num_rows > 0){
        $row = $PILRES->fetch_assoc();
        $year = $row['year'];
        $month = $row['month'];
        $range_date = $row['range_date'];
        $monthName = isset($months[$month]) ? $months[$month] : "Invalid Month";
        $PIL = "$monthName $range_date $year"; 
    }
    
    if($PRLRES->num_rows > 0){
        $row = $PRLRES->fetch_assoc();
        $year = $row['year'];
        $month = $row['month'];
        $range_date = $row['range_date'];
        $monthName = isset($months[$month]) ? $months[$month] : "Invalid Month";
        $PRL = "$monthName $range_date $year"; 
    }
    
    if($PULRES->num_rows > 0){
        $row = $PULRES->fetch_assoc();
        $year = $row['year'];
        $month = $row['month'];
        $range_date = $row['range_date'];
        $monthName = isset($months[$month]) ? $months[$month] : "Invalid Month";
        $PUL = "$monthName $range_date $year"; 
    }
    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/ATmyaccount.css">

</head>
<body>

    <div class="content p-4 bg-image">
        <div class="row d-flex">
            
            <!--MY ACCOUNT-->
            <div class="col-8 d-flex flex-column">
                <div class="card-header p-3 text-white">
                    <i class="bi bi-person-circle" style="margin-right:5px;"></i> 
                    <span>My Account</span>
                </div>

                <div class="bg-costum text-white d-flex flex-grow-1">
                    <div class="col-7 p-5">
                        <h4><?php echo $Elname, ' ', $Efname, ' ', $Emname, '.'; ?></h4>
                        <span>Email : <?php echo $Eemail; ?></span>
                        <p>Username : <?php echo $Eusername; ?></p>

                        <div class="clock d-flex align-items-center">
                            <div class="clock__circle bg me-3">
                                <!-- Clock Arrow -->
                                <div class="clock__hour" id="clock-hour"></div>
                                <div class="clock__minutes" id="clock-minutes"></div>
                            </div>

                            <div class="clock__date">
                                <!-- Date Day Year -->
                                <span class="clock__month" id="date-month"></span>
                                <span class="clock__day" id="date-day"></span>
                                <span class="clock__year" id="date-year"></span>
                                <div class="clock__text">
                                    <span id="time-ampm-week"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-4 text-white Weather">
                        <h4 id="cityName"></h4>
                        <div class="d-flex align-items-center">
                            <div class="side1">
                                <span id="weather-icon"></span>
                            </div>
                            <div class="side2">
                                <span id="description"></span>
                                <h2 id="temperature"></h2>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!--RELATED SETTINGS-->
            <div class="col-4 d-flex flex-column">
                <div class="card-header p-3 text-white">
                    <i class="bi bi-gear-fill" style="margin-right:5px;"></i> 
                    <span>Related Settings</span>
                </div>
                <div class="relatedsettings bg-costum p-4 flex-grow-1">
                    <button class="btn btn-link" onclick="editAccount(<?php echo $IDemployee; ?>)">
                        <i class="bi-pencil-square" style="margin-right:5px;"></i> Edit My Account
                    </button>
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#changepassaccount">
                        <i class="bi bi-shield-lock" style="margin-right:5px;"></i> Change My Password
                    </button>
                </div>
            </div>

        </div>


    <!--TABLE-->
    <div class="row pt-3">
            <div class="col-12">
                <main class="table"  id="customers_table">
                    <section class="table__header p-3 text-white d-flex">
                        <div class="data">
                            <i class="bi bi-table"></i>
                            <span>Latest Posted Data</span>
                        </div>
                        <div class="input-group">
                            <input type="search" name="SearchInput" id="SearchInput" placeholder="Search Data">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </section>
                    
                    <section class="table_body">
                        <div class="table_body_1">
                        <table id="table1">
                            <thead>
                                <tr style="background:transparent;">
                                    <th colspan="2" class="text-center">Planting Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>IRRIGATED</td>
                                    <td><?php echo $PIL?></td>
                                <tr>
                                <tr>
                                    <td>RAINFED</td>
                                    <td><?php echo $PRL?></td>
                                <tr>
                                <tr>
                                    <td>UPLAND</td>
                                    <td><?php echo $PUL?></td>
                                <tr>
                            </tbody>
                        </table>
                        </div>

                        <div class="table_body_2">
                        <table id="table2">
                            <thead>
                                <tr style="background:transparent;">
                                    <th colspan="2" class="text-center">Harvesting Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>IRRIGATED</td>
                                    <td><?php echo $HIL?></td>
                                <tr>
                                <tr>
                                    <td>RAINFED</td>
                                    <td><?php echo $HRL?></td>
                                <tr>
                                <tr>
                                    <td>UPLAND</td>
                                    <td><?php echo $HUL?></td>
                                <tr>
                            </tbody>
                        </table>
                        </div>
                    </section>

                    
                </main>
            </div>
        </div>



<!--MODAL EDIT MY ACCOUNT-->
    <div class="modal fade" id="accountModalEdit" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header  justify-content-between">
                <h5 class="modal-title" id="accountModalLabel"><i class="bi bi-pencil" style="margin-right:15px;"></i>Edit Account</h5>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="EditFormAT">
            <div class="modal-body p-5 bg-light">

                <div class="form-group">
                    <label class="inputLogLabels text-secondary" for="modalFirstName">First Name :</label>
                    <input type="text" name="Efname" id="modalFirstName" class="form-control" oninput="this.value = this.value.toUpperCase();" />
                </div>

                <div class="form-group mt-2">
                    <label class="modalLastName text-secondary" for="modalFirstName">Last Name :</label>
                    <input type="text" name="Elname" id="modalLastName" class="form-control" oninput="this.value = this.value.toUpperCase();" />
                </div>


                <div class="form-group mt-2">
                    <label class="modalMiddleName text-secondary" for="modalMiddleName">Middle Initial :</label>
                    <input type="text" name="Emname" id="modalMiddleName" class="form-control" maxlength="1" oninput="this.value = this.value.toUpperCase();" />
                </div>

                <div class="form-group mt-2">
                    <label class="modalEmail text-secondary" for="modalEmail">Email  :</label>
                    <input type="email" name="Eemail" id="modalEmail" class="form-control" />
                </div>

                <div class="form-group mt-2">
                    <label class="modalUsername text-secondary" for="modalUsername">Username :</label>
                    <input type="text" name="Eusername" id="modalUsername" class="form-control"/>
                </div>
            </div>


            <div class="modal-footer justify-content-center bg-light">
                <button type="submit" name="saveChangeEdit" id="saveChangeEdit" class="shadow btn btn-success">Save Change</button>
            </div>
            </form>

            </div>
        </div>
    </div>


   


        <!--MODAL CHANGE PASSWORD ACCOUNT-->
        <div class="modal fade" id="changepassaccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="accountModalLabel"><i class="bi bi-pencil" style="margin-right:15px;"></i>Change Password</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                    <form id="changepassATForm">
                        <div class="modal-body p-5 bg-light">

                            <div class="form-group">
                                <label class="inputLogLabels text-secondary" for="oldpass">Current Password : </label>
                                <input  type="password" class="form-control" name="oldpass" id="oldpass" placeholder="Enter Current Password">
                            </div>
                            <div class="form-group mt-2">
                                <label class="inputLogLabels text-secondary" for="newpass">New Password : </label>
                                <input  type="password" class="form-control" name="newpass" id="newpass" placeholder="Enter New Password">
                            </div>
                            <div class="form-group mt-2">
                                <label class="inputLogLabels text-secondary" for="newpass">Confirm New Password : </label>
                                <input  type="password" class="form-control" name="connewpass" id="connewpass" placeholder="Confirm New Password">
                            </div>

                        </div>

                        <div class="modal-footer justify-content-center  bg-light">
                            <button type="submit" name="changepassmyaccount"  class="shadow btn btn-success">Change Password</button>
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
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>\

    <script src="systemjs/ATmyaccount.js" defer></script>
</body>
</html>