<?php

//NOT FINISH 

require('Function/MAsession.php');
include "connection.php";

$MAstatus = $_SESSION['MAstatus'];
$IDadmin = $_SESSION['IDadmin'];

// Condition for Session for DASHBOARD VIEW ONLY
if(empty($_GET['validation']) || $_GET['validation'] != true){
    if(empty($_SESSION['MAstatus']) || $_SESSION['MAstatus'] == 'MAinvalid'){
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>window.location.href = 'MADashboard.php';</script>";
    }
}

$adminsql = "SELECT * FROM `admin` WHERE `IDadmin` = '$IDadmin'";
$adminresult = mysqli_query($con, $adminsql); 

if($adminresult->num_rows > 0){
    $row = $adminresult->fetch_assoc();
    
    $Afname = $row['Afname'];
    $Alname = $row['Alname'];
    $Amname = $row['Amname'];
    $Aemail = $row['Aemail'];
    $Ausername = $row['Ausername'];
    $Apassword = $row['Apassword'];
}

//Get Employee List
$queryEmp = "SELECT * FROM `employee`";
$queryEmpRes = mysqli_query($con, $queryEmp);


$queryDeletedEmp = "SELECT * FROM `deleted_employee`";
$queryDeletedEmpRes = mysqli_query($con, $queryDeletedEmp);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/MAmyaccount.css">
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
                        <h4><?php echo $Alname, ' ', $Afname, ' ', $Amname, '.'; ?></h4>
                        <span>Email : <?php echo $Aemail; ?></span>
                        <p>Username : <?php echo $Ausername; ?></p>

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
                    <button class="btn btn-link" onclick="EditMAAccount(<?php echo $IDadmin?>)">
                        <i class="bi-pencil-square" style="margin-right:5px;"></i> Edit My Account
                    </button>
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#changepassadminaccount">
                        <i class="bi bi-shield-lock" style="margin-right:5px;"></i> Change My Password
                    </button>
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#removedemployee">
                        <i class="bi bi-person-x-fill" style="margin-right:5px;"></i> Removed Employee List
                    </button>
                    <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addemployee">
                        <i class="bi bi-person-plus-fill" style="margin-right:5px;"></i> Add Employee
                    </button>
                </div>
            </div>

        </div>

        <!--TABLE-->
        <div class="row">
            <div class="col-12">
                <main class="table mt-4"  id="customers_table">
                    <section class="table__header p-3 text-white d-flex">
                        <div class="employeelist">
                            <i class="bi bi-table"></i>
                            <span>Agricultural Technicians List</span>
                        </div>
                        <div class="input-group employeelist">
                            <input type="search" name="SearchInput" id="SearchInput" placeholder="Search Agriculturist">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </section>
                    <section class="table__body">
                        <table>
                            <thead>
                                <tr style="background:transparent;">
                                    <th> Fullname <span class="icon-arrow">&UpArrow;</span></th>
                                    <th> Username <span class="icon-arrow">&UpArrow;</span></th>
                                    <th> Email <span class="icon-arrow">&UpArrow;</span></th>
                                    <th> Action <span class="icon-arrow">&UpArrow;</span></th>
                                </tr>
                            </thead>
                            <tbody id="TBODYEMPLOYEE">
                                <?php
                                if(mysqli_num_rows($queryEmpRes) > 0){
                                    foreach($queryEmpRes as $employeerow){
                                    ?>
                                        <tr>
                                            <td><?= $employeerow['Elname'];?> <?= $employeerow['Efname'];?> <?= $employeerow['Emname'];?>.</td>
                                            <td><?= $employeerow['Eusername'];?></td>
                                            <td><?= $employeerow['Eemail'];?></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-m" onclick="deleteAccountEmployee(<?= $employeerow['IDemployee'];?>)"> <i class="bi bi-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="4">No Agricultural Technicians </td>
                                    </tr>
                                <?php
                                } 
                                ?>

                            </tbody>
                        </table>
                    </section>
                </main>
            </div>
        </div>

    </div>
















 <!--MODALS  ADD EMPLOYEE-->
    <div class="modal modaltransparent fade" id="addemployee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
            
            <div class="modal-header justify-content-between bg-header">
                <span class="modal-title" id="exampleModalLabel"><i class="bi bi-person-plus" style="margin-right:10px;"></i> Add Agricultural Technicians</span>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>


            <form id="addATform">
                <div class="modal-body p-5">
                    <div id="EmplyeeAddInput">
                        <div class="Text-Error d-none text-danger text-center">
                            <span></span>
                        </div>
                        <input type="text" name="IDadmin" class="d-none" value="<?php echo $IDadmin;?>">
                        <div class="form-group">
                            <label class="inputLogLabels text-secondary" for="Elname">Last Name : </label>
                            <input  type="text" class="form-control" name="Elname" id="Elname" placeholder="Enter Last Name" oninput="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="Efname">First Name : </label>
                            <input  type="text" class="form-control" name="Efname" id="Efname" placeholder="Enter First Name" oninput="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="Emname">Middle Initial : </label>
                            <input  type="text" class="form-control" name="Emname" id="Emname" maxlength="1" placeholder="M.I" oninput="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="Eemail">Email : </label>
                            <input  type="email" class="form-control" name="Eemail" id="Eemail" placeholder="Enter Email Address">
                        </div>
                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="Eusername">Username : </label>
                            <input  type="text" class="form-control" name="Eusername" id="Eusername" placeholder="Enter Userame" oninput="this.value = this.value.replace(/\s+/g, '')">
                        </div>
                    </div>
                    

                </div>
                
                <div class="modal-footer justify-content-center bg-light">
                    <button type="submit" name="addAT"  class="shadow btn btn-success">Save Employee</button>
                </div>
            </form>

            </div>
        </div>
    </div>




    



    <!-- MODAL FOR DELETED EMPLOYEE TABLE -->
<div class="modal modaltransparent fade" id="removedemployee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-light">
            <div class="modal-header bg-header justify-content-between">
                <span class="modal-title" id="exampleModalLabel">
                    <i class="bi bi-person-x-fill" style="margin-right: 10px;"></i> 
                    Removed Agricultural Technicians
                </span>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <!-- VIEW NPR TABLE -->
                <table id="DELETE_EMPLOYEE_TABLE" class="table table-bordered table-striped" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr class="Head1">
                            <th colspan="4" class="text-center p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-size: 16px;">REMOVED AGRICULTURAL TECHNICIANS</span>
                                    <input type="search" name="SearchInputDelete" id="SearchInputDelete" class="form-control w-25" placeholder="Search Agricultural Technicians">
                                </div>
                            </th>
                        </tr>
                        <tr class="Head2">
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($queryDeletedEmpRes) > 0): ?>
                            <?php foreach($queryDeletedEmpRes as $employeerow): ?>
                                <tr>
                                    <td><?= $employeerow['Elname'];?> <?= $employeerow['Efname'];?> <?= $employeerow['Emname'];?>.</td>
                                    <td><?= $employeerow['Eusername'];?></td>
                                    <td><?= $employeerow['Eemail'];?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-m" onclick="recoverAccountEmployee(<?= $employeerow['IDdeleted'];?>)"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        <button type="button" class="btn btn-danger btn-m" onclick="PermanentDeleteAccountEmployee(<?= $employeerow['IDdeleted'];?>)"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">No Deleted Employee</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>











<!--MODALS EDIT ACCOUNT-->

    <div class="modal modaltransparent fade" id="editadminaccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-light">

                <div class="modal-header  justify-content-between">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-pencil" style="margin-right:15px;"></i>Edit Account</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                            
                <form id="EditAccountMAform">
                    <div class="modal-body bg-light p-5">

                        <div class="form-group">
                            <label class="inputLogLabels text-secondary" for="modalFirstName">First Name :</label>
                            <input type="text" name="Afname" id="modalFirstName" class="form-control" oninput="this.value = this.value.toUpperCase();" />
                        </div>

                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="modalLastName">Last Name:</label>
                            <input type="text" name="Alname" id="modalLastName" class="form-control" oninput="this.value = this.value.toUpperCase();" />
                        </div>

                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="modalMiddleName">Middle Initial:</label>
                            <input type="text" name="Amname" id="modalMiddleName" class="form-control" maxlength="1" oninput="this.value = this.value.toUpperCase();" />
                        </div>

                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="modalEmail">Email:</label>
                            <input type="email" name="Aemail" id="modalEmail" class="form-control" />
                        </div>

                        <div class="form-group mt-2">
                            <label class="inputLogLabels text-secondary" for="modalUsername">Username:</label>
                            <input type="text" name="Ausername" id="modalUsername" class="form-control"/>
                        </div>
                
                    </div>

                    <div class="modal-footer justify-content-center bg-light">
                        <button type="submit" name="editmyaccount"  class="shadow btn btn-success">Save Change</button>
                    </div>

                </form>

            </div>
        </div>
    </div>






<!--MODALS CHANGEPASS ACCOUNT-->

    <div class="modal modaltransparent fade" id="changepassadminaccount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-light">

            <div class="modal-header justify-content-between">
                <span class="modal-title" id="exampleModalLabel"><i class="bi bi-pencil" style="margin-right:10px;"></i>Change Password</span>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
                
                <form id="changepassMAForm">
                    <div class="modal-body p-5 bg-light">

                        <div class="form-group">
                            <label class="inputLogLabels text-secondary" for="oldpass">Current Password : </label>
                            <input  type="password" class="form-control" name="oldpass" id="oldpass" placeholder="Enter Current Password">
                        </div>
                        <div class="form-group mt-3">
                            <label class="inputLogLabels text-secondary" for="newpass">New Password : </label>
                            <input  type="password" class="form-control" name="newpass" id="newpass" placeholder="Enter New Password">
                        </div>
                        <div class="form-group mt-3">
                            <label class="inputLogLabels text-secondary" for="newpass">Confirm New Password : </label>
                            <input  type="password" class="form-control" name="connewpass" id="connewpass" placeholder="Confirm New Password">
                        </div>

                    </div>

                    <div class="modal-footer justify-content-center bg-light">
                        <button type="submit" name="chnagepassmyaccount"  class="shadow btn btn-success">Change Password</button>
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

    <script src="systemjs/MAmyaccount.js" defer></script>

    <script>

        const searchInput = document.getElementById('SearchInputDelete');
        const tableRows = document.querySelectorAll('#DELETE_EMPLOYEE_TABLE tbody tr');

        // Add an event listener to the search input
        searchInput.addEventListener('input', searchTable);

        function searchTable() {
            const searchTerm = searchInput.value.toLowerCase(); // Get the input value
            
            tableRows.forEach((row, index) => {
                const rowText = row.textContent.toLowerCase();
                const isVisible = rowText.indexOf(searchTerm) > -1;
                
                // Toggle the visibility and remove border for hidden rows
                row.classList.toggle('hide', !isVisible);
                row.style.setProperty('--delay', index / 25 + 's');

                // Optional: Remove border for hidden rows
                if (!isVisible) {
                    row.style.border = 'none'; // Remove border for hidden rows
                } else {
                    row.style.border = ''; // Reset border for visible rows
                }
            });
            
            // Highlight visible rows with alternating background colors
            document.querySelectorAll('#DELETE_EMPLOYEE_TABLE tbody tr:not(.hide)').forEach((visibleRow, index) => {
                visibleRow.style.backgroundColor = (index % 2 === 0) ? 'transparent' : '#f1f1f1'; // Alternate row colors
            });
        }


    </script>

</body>
</html>