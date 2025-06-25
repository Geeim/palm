<?php

include 'connection.php';

// Condition for Session for DASHBOARD VIEW ONLY
if(empty($_GET['validation']) || $_GET['validation'] != true){
    if(empty($_SESSION['MAstatus']) || $_SESSION['MAstatus'] == 'MAinvalid'){
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>window.location.href = 'MADashboard.php';</script>";
    }
}

//Get Planting Data List
$queryDRPlanting = "SELECT DISTINCT `month`, `range_date`, `year` FROM `planting` WHERE `landtype` = '3' ORDER BY `year` DESC, `month` DESC, `range_date` ASC ";
$ResultDRPlanting = mysqli_query($con, $queryDRPlanting);

//Get Harvest Data List
$queryDRPharvest = "SELECT DISTINCT `month`, `range_date`, `year` FROM `harvesting` WHERE `landtype` = '3' ORDER BY `year` DESC, `month` DESC, `range_date` ASC;";
$ResultDRHarvest = mysqli_query($con, $queryDRPharvest);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upland Data Records</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/MADRupland.css">

</head>

<body>

    <div class="content p-4 bg-image">
        <div class="row mb-3">
            <div class="col-12">
                <div class="bg-custom">
                    <div class="row">
                        
                        <div class="col-6 text-center p-3">
                            <span class="text-white">DATA RECORDS</span>
                            <div class="p-1 inputfield text-white">
                                <p class="fw-custom">UPLAND ECOSYSTEM</p>
                            </div>
                        </div>

                        <div class="col-6 text-center p-3">
                            <label for="exportdata" class="text-white">EXPORT DATA</label>
                            <div class="p-1 inputfield">
                                <button class="shadow" data-bs-toggle="modal" id="exportdata" data-bs-target="#exportmodalUpland">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </div>
                
                    </div>
                </div>
            </div>
        </div>



        <!--TABLE-->
        <div class="row">
            <div class="col-12">
                <main class="table"  id="customers_table">
                    <section class="table__header p-3 text-white d-flex">
                        <div class="uplanddata">
                            <i class="bi bi-table"></i>
                            <span>Data Record List</span>
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
                                <?php
                                if(mysqli_num_rows($ResultDRPlanting) > 0){
                                    foreach($ResultDRPlanting as $row){
                                        $monthNamesPlanting = [1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
                                        $monthNamePlanting = $monthNamesPlanting[$row['month']];
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $monthNamePlanting. ' ' . $row['range_date'] . ', ' . $row['year']; ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-m" onclick="ViewPlanting(<?= $row['year'] ?>, <?= $row['month'] ?>, '<?= addslashes($row['range_date']) ?>')"><i class="bi bi-eye"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="4">No Data Found</td>
                                    </tr>
                                <?php
                                } 
                                ?>

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
                                <?php
                                if(mysqli_num_rows($ResultDRHarvest) > 0){
                                    foreach($ResultDRHarvest as $row){
                                        $monthNamesHarvest = [1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
                                        $monthNameHarvest = $monthNamesHarvest[$row['month']];
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $monthNameHarvest . ' ' .$row['range_date'] . ', ' . $row['year'];?></td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-m" onclick="ViewHarvesting(<?= $row['year'] ?>, <?= $row['month'] ?>, '<?= addslashes($row['range_date']) ?>')"><i class="bi bi-eye"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                    }

                                }else{
                                ?>
                                    <tr>
                                        <td class="text-center" colspan="4">No Data Found</td>
                                    </tr>
                                <?php
                                } 
                                ?>

                            </tbody>
                        </table>
                        </div>
                    </section>

                    
                </main>
            </div>
        </div>




<!--MODAL VIEW HARVESTING NO UPDATE-->

<div class="modal fade" id="viewModalHarvesting" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title text-white" id="viewModalLabel">UPLAND - View Data Harvesting</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body bg-light" id="modal-content">

                    <strong>
                        <div class="mt-3 mb-3" style="text-align: center;">
                            <p id="season_type_harvesting" style="display:inline-block; text-transform: uppercase;"></p>
                            <p id="yearAndRangeDate_harvesting" style="display:inline-block; text-transform: uppercase;"></p>
                        </div>
                    </strong>


                     <!--VIEW NPR TABLE-->
                     <table id="VIEW_HARVESTING_FORMAL_NPR_TABLE" class="bg-white HARVESTING_TABLE" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="10" class="NRPcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - NRP</span>
        
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_HarvestingNextTableRCEF">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Hybridcolor" colspan="3">Hybrid Seed</th>
                                <th class="Registeredcolor" colspan="3">Registered Seed</th>
                                <th class="Certifiedcolor" colspan="3">Certified Seed</th>
                            </tr>
                            <tr>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>


                    <!--VIEW RCEF TABLE-->
                    <table id="VIEW_HARVESTING_FORMAL_RCEF_TABLE" class="HARVESTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="10" class="RCEFcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - RCEF</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_HarvestingBackToNPR">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_HarvestingNextTableOWNOTHERS">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Hybridcolor" colspan="3">Hybrid Seed</th>
                                <th class="Registeredcolor" colspan="3">Registered Seed</th>
                                <th class="Certifiedcolor" colspan="3">Certified Seed</th>
                            </tr>
                            <tr>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>

                    <!--VIEW OWNOTHERS TABLE-->
                    <table id="VIEW_HARVESTING_FORMAL_OWNOTHERS_TABLE" class="HARVESTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="10" class="OwnOtherscolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - OWN/OTHERS</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_HarvestingBackToRCEF">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_HarvestingNextTableINFORMAL">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Hybridcolor" colspan="3">Hybrid Seed</th>
                                <th class="Registeredcolor" colspan="3">Registered Seed</th>
                                <th class="Certifiedcolor" colspan="3">Certified Seed</th>
                            </tr>
                            <tr>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>


                    <!--VIEW INFORMAL TABLE-->
                    <table id="VIEW_HARVESTING_INFORMAL_TABLE" class="HARVESTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="10" class="Informalcolor text-center p-3">
                                    <span style="font-size:16px;">INFORMAL SEED'S SYSTEM</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_HarvestingBackToOWNOTHERS">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_HarvestingNextTableFSS">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Startercolor" colspan="3">Good Seeds from Starter RS and CS by CSB</th>
                                <th class="Taggedcolor" colspan="3">Good Seeds from Tagged FS/RS by ACC.Seed Grower</th>
                                <th class="Traditioncolor" colspan="3">Good Seeds from Traditional Varieties</th>
                            </tr>
                            <tr>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>


                    <!--VIEW FSS TABLE-->
                    <table id="VIEW_HARVESTING_FSS_TABLE" class="HARVESTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="4" class="FSScolor text-center p-3">
                                    <span style="font-size:16px;">FARMER SAVE SEED'S</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_HarvestingBackToINFORMAL">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="1">Barangay</th>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                                <td>Production (MT)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>


                </div>

            </div>
        </div>
    </div>




<!--MODAL VIEW PLANTING NO UPDATE-->

    <div class="modal fade" id="viewModalPlanting" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="viewModalLabel">UPLAND - View Data Planting</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>


                <div class="modal-body bg-light" id="modal-content">

                    <strong>
                        <div class="mt-3 mb-3" style="text-align: center;">
                            <p id="season_type" style="display:inline-block; text-transform: uppercase;"></p>
                            <p id="yearAndRangeDate" style="display:inline-block; text-transform: uppercase;"></p>
                        </div>
                    </strong>

                    
                    <!--VIEW NPR TABLE-->
                    <table id="VIEW_PLANTING_FORMAL_NPR_TABLE" class="PLANTING_TABLE" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="7" class="NRPcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - NRP</span>
        
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_PlantingNextTableRCEF">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Hybridcolor" colspan="2">Hybrid Seed</th>
                                <th class="Registeredcolor" colspan="2">Registered Seed</th>
                                <th class="Certifiedcolor" colspan="2">Certified Seed</th>
                            </tr>
                            <tr>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>
        
                    <!--VIEW RCEF TABLE-->
                    <table id="VIEW_PLANTING_FORMAL_RCEF_TABLE" class="PLANTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="7" class="RCEFcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - RCEF</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_PlantingBackToNPR">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_PlantingNextTableOWNOTHERS">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Hybridcolor" colspan="2">Hybrid Seed</th>
                                <th class="Registeredcolor" colspan="2">Registered Seed</th>
                                <th class="Certifiedcolor" colspan="2">Certified Seed</th>
                            </tr>
                            <tr>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>

                    <!--VIEW OWNOTHERS TABLE-->
                    <table id="VIEW_PLANTING_FORMAL_OWNOTHERS_TABLE" class="PLANTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="7" class="OwnOtherscolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - OWNOTHERS</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_PlantingBackToRCEF">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_PlantingNextTableINFORMAL">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Hybridcolor" colspan="2">Hybrid Seed</th>
                                <th class="Registeredcolor" colspan="2">Registered Seed</th>
                                <th class="Certifiedcolor" colspan="2">Certified Seed</th>
                            </tr>
                            <tr>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>


                    <!--VIEW INFORMAL TABLE-->
                    <table id="VIEW_PLANTING_INFORMAL_TABLE" class="PLANTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="7" class="Informalcolor text-center p-3">
                                    <span style="font-size:16px;">INFORMAL SEED'S SYSTEM</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_PlantingBackToOWNOTHERS">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="View_PlantingNextTableFSS">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td" rowspan="2">Barangay</th>
                                <th class="Startercolor" colspan="2">Good Seeds from Starter RS and CS by CSB</th>
                                <th class="Taggedcolor" colspan="2">Good Seeds from Tagged FS/RS by ACC.Seed Grower</th>
                                <th class="Traditioncolor" colspan="2">Good Seeds from Traditional Varieties</th>
                            </tr>
                            <tr>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>


                    <!--VIEW FSS TABLE-->
                    <table id="VIEW_PLANTING_FSS_TABLE" class="PLANTING_TABLE d-none" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="3" class="FSScolor text-center p-3">
                                    <span style="font-size:16px;">FARMER SAVE SEED'S</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="View_PlantingBackToINFORMAL">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor first_td">Barangay</th>
                                <td>Area Planted (Ha)</td>
                                <td>No. of Farmers</td>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data rows will be inserted here -->
                        </tbody>
                    </table>

                </div>
    
            </div>
        </div>
    </div>





    <!-- Modal EXPORT-->
    <div class="modal fade" id="exportmodalUpland" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
        
                <div class="modal-header justify-content-between">
                    <span class="modal-title" id="exampleModalLabel"><i class="bi bi-filetype-csv"></i> Export Data Upland</span>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="exportupland">
                    <div class="modal-body p-5 bg-light">

                        <div class="form-group">
                            <label class="monthexportUpland text-secondary" for="monthexportUpland">Month : </label>
                            <select class="form-select" name="monthexport" id="monthexportUpland" required>
                                <option value="" disabled selected>Select Month</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>|
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div> 

                        <div class="form-group mt-3">
                            <label class="rangeDateExportUpland text-secondary" for="rangeDateExportUpland">Range Date :</label>
                            <select class="form-select" name="rangeDateExport" id="rangeDateExportUpland" required>
                                <option value="" disabled selected>Select Range Date</option>
                                <option value="1-15">1-15</option>
                                <option value="1-30">1-30</option>
                                <option value="16-30">16-30</option>
                            </select>
                        </div>


                        <div class="form-group mt-3">
                            <label class="yearExportUpland text-secondary" for="yearExportUpland">Select Year :</label>
                            <?php $currentYear = date("Y"); ?>
                            <input type="number" class="form-control" name="yearExport" id="yearExportUpland" placeholder="Enter Year" min="2000" max="<?php echo $currentYear; ?>" required>
                        </div>

                        <div class="form-group mt-3">
                            <label class="mayorsnameexportUpland text-secondary" for="mayorsnameexportUpland">Mayor's Name :</label>
                            <input type="text" class="form-control" name="mayorsnameexport" id="mayorsnameexportUpland" placeholder="Enter the Mayor's Name" required  oninput="this.value = this.value.toUpperCase();">
                        </div>

                    </div>

                    <div class="modal-footer d-flex justify-content-center align-items-center">
                        <button type="submit" name="btnexport" id="btnexportUpland" class="shadow btn btn-success">Submit</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>



    </div>

    <!-- Script files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="systemjs/MADRupland.js"></script>


</body>

</html>