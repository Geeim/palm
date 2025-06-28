<?php
include 'connection.php';

// Condition for Session for DASHBOARD VIEW ONLY
if(empty($_GET['validation']) || $_GET['validation'] != true){
    if(empty($_SESSION['ATstatus']) || $_SESSION['ATstatus'] == 'ATinvalid'){
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>window.location.href = 'ATDashboard.php';</script>";
    }
}


//Get Planting Data List
$queryDRPlanting = "SELECT DISTINCT `month`, `range_date`, `year` FROM `planting` WHERE `landtype` = '1' ORDER BY `year` DESC, `month` DESC, `range_date` ASC ";
$ResultDRPlanting = mysqli_query($con, $queryDRPlanting);

//Get Harvest Data List
$queryDRPharvest = "SELECT DISTINCT `month`, `range_date`, `year` FROM `harvesting` WHERE `landtype` = '1' ORDER BY `year` DESC, `month` DESC, `range_date` ASC;";
$ResultDRHarvest = mysqli_query($con, $queryDRPharvest);



// SQL FOR SELECTION
$barangaySQL = "SELECT * FROM barangay";
$barangayRes = $con->query($barangaySQL);

    // Fetch all rows in an array to reuse
    $barangayData = [];
    if ($barangayRes->num_rows > 0) {
        while ($row = $barangayRes->fetch_assoc()) {
            $barangayData[] = $row;
        }
    }

    // Function to generate options for any selector
    function generateBarangayOptions($barangayData) {
        $options = '';
        foreach ($barangayData as $row) {
            $options .= "<option value='" . $row['IDbarangay'] . "'>" . htmlspecialchars($row['BarangayName']) . "</option>";
        }
        return $options;
    }

    // All SELECTOR IN HARVESTING
    $HARVESTING_FORMAL_NPR_barangayOptions = generateBarangayOptions($barangayData);
    $HARVESTING_FORMAL_RCEF_barangayOptions = generateBarangayOptions($barangayData);
    $HARVESTING_FORMAL_OWNOTHERS_barangayOptions = generateBarangayOptions($barangayData);
    $HARVESTING_INFORMAL_barangayOptions = generateBarangayOptions($barangayData);
    $HARVESTING_FSS_barangayOptions = generateBarangayOptions($barangayData);

    // All SELECTOR IN PLANTING
    $PLANTING_FORMAL_NPR_barangayOptions = generateBarangayOptions($barangayData);
    $PLANTING_FORMAL_RCEF_barangayOptions = generateBarangayOptions($barangayData);
    $PLANTING_FORMAL_OWNOTHERS_barangayOptions = generateBarangayOptions($barangayData);
    $PLANTING_INFORMAL_barangayOptions = generateBarangayOptions($barangayData);
    $PLANTING_FSS_barangayOptions = generateBarangayOptions($barangayData);   


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Irrigated Data Records</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/ATDRirrigated.css">

    <style>    
    </style>
</head>
<body>

<div class="content p-4 bg-image">
    <div class="row mb-3">
        <div class="col-12">
            <div class="bg-custom">
                <div class="row">
                    
                    <div class="col-4 text-center p-3">
                        <span class="text-white">DATA RECORDS</span>
                        <div class="p-1 inputfield text-white">
                            <p class="fw-custom">IRRIGATED ECOSYSTEM</p>
                        </div>
                    </div>

                    <div class="col-4 text-center p-3">
                        <label for="insertdata" class="text-white">INSERT DATA</label>
                        <div class="p-1 inputfield">
                            <button class="shadow" id="insertdata" data-bs-toggle="modal" data-bs-target="#SetUpModalHP">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-4 text-center p-3">
                        <label for="exportdata" class="text-white">EXPORT DATA</label>
                        <div class="p-1 inputfield">
                            <button class="shadow" data-bs-toggle="modal" id="exportdata" data-bs-target="#exportmodalIrrigated">
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
                        <div class="irrigateddata">
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
                                                <button class="btn btn-danger btn-m" onclick="DeletePlanting(<?= $row['year'] ?>, <?= $row['month'] ?>, '<?= addslashes($row['range_date']) ?>')"><i class="bi bi-trash"></i></button>
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
                                                <button class="btn btn-danger btn-m" onclick="DeleteHarvesting(<?= $row['year'] ?>, <?= $row['month'] ?>, '<?= addslashes($row['range_date']) ?>')"><i class="bi bi-trash"></i></button>
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
            <div class="modal-content bg-light">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title text-white" id="viewModalLabel">IRRIGATED - View Data Harvesting</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body" id="modal-content">

                         
                    <strong>
                        <div class="mt-3" style="text-align: center;">
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
                
                <div class="modal-footer justify-content-center">
                    <button type="button" id="saveChangesHarvesting" class="shadow btn btn-success">Save Changes</button>
                </div>


            </div>
        </div>
    </div> 
        





   
<!--MODAL VIEW PLANTING NO UPDATE-->

<div class="modal fade" id="viewModalPlanting" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="viewModalLabel">IRRIGATED - View Data Planting</h5>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>


                <div class="modal-body" id="modal-content">
                
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
                       
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" id="saveChanges" class="shadow btn btn-success">Save Changes</button>
                </div>


            </div>
        </div>
    </div>


 



<!-- MODAL ASK INSERT - HARVESTING or PLANTING -->
<div class="modal fade" id="SetUpModalHP" tabindex="-1" aria-labelledby="SetUpModalHPLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-light">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="SetUpModalHPLabel"><i style="margin-right: 5px;" class="bi bi-file-earmark-plus"></i> IRRIGATED INSERT DATA</h5>
                <button type="button" class="btn" id="cancelInserting" aria-label="Close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="SetUpForm" novalidate>
                <div class="modal-body p-5">

                    <div class="form-group">
                        <label  class="InsertType text-secondary" for="InsertType">Select Insert Data : </label>
                        <select class="form-select" id="InsertType" required>
                            <option value="" disabled selected>Select a Type</option>
                            <option value="Planting">Planting</option>
                            <option value="Harvesting">Harvesting</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a type.
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label  class="SeasonType text-secondary" for="SeasonType">Select Season : </label>
                        <select class="form-select" id="SeasonType" required>
                            <option value="" disabled selected>Select Type of Season</option>
                            <option value="1">Wet Season</option>
                            <option value="2">Dry Season</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a season.
                        </div>
                    </div>
                

                    <div class="form-group mt-3">
                        <label  class="monthpick text-secondary" for="monthpick">Select Month : </label>
                        <select class="form-select" id="monthpick" required>
                            <option value="" disabled selected>Select a Month</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
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
                        <div class="invalid-feedback">
                            Please select Month.
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label  class="rangeDate text-secondary" for="rangeDate">Range Date : </label>
                        <select class="form-select" id="rangeDate" required>
                            <option value="" disabled selected>Range Date</option>
                            <option value="1-15">1-15</option>
                            <option value="1-30">1-30</option>
                            <option value="16-30">16-30</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a range date.
                        </div>
                    </div>

                   
                    <div class="form-group mt-3">
                        <label  class="yearInsert text-secondary" for="yearInsert">Year : </label>
                        <?php $currentYear = date("Y"); ?>
                        <input type="number" class="form-control" id="yearInsert" placeholder="Enter Year" min="2000" max="<?php echo $currentYear; ?>" required>
                        <div class="invalid-feedback">
                            Please enter a year between 2000 and the current year.
                        </div>
                    </div>
            


                
                </div>
                    
                <div class="modal-footer d-flex justify-content-center align-items-center">
                    <button type="submit" name="submitSetUp" id="submitSetUp" class="shadow btn btn-success">Next</button>
                </div>
            </form>



        </div>
    </div>
</div>






<!-- Modal Insert Data for Harvesting -->
<div class="modal fade" id="InsertModalHarvesting" tabindex="-1" aria-labelledby="InsertingModalHarvestingLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-full-height">
        <div class="modal-content bg-light">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="InsertingModalHarvestingLabel">IRRIGATED - Insert Data Harvesting</h5>
                <button type="button" class="btn" id="cancelHarvestingInsert" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">

            <strong>
                <div class="mt-3 mb-3" style="text-align: center;">
                    <p id="H-season" style="display:inline-block; text-transform: uppercase;"></p>
                    <p id="H-period" style="display:inline-block; text-transform: uppercase;"></p>
                </div>
            </strong>

        

                <form id="HARVESTING_INSERT_FORM">
                    <!-- FORMAL NPR TABLE -->
                    <table id="HARVESTING_FORMAL_NPR_TABLE" class="bg-white HARVESTING_TABLE">
                        <thead>
                            <tr>
                                <th colspan="10" class="NRPcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - NRP</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="HarvestingBackToSetUp">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="HarvestingNextTableRCEF">
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
                            <tr>
                                <td class="first_td">
                                    <select id="Harvesting_FORMAL_NPR_selection" onchange="Harvesting_AddBarangayFORMAL_NPR()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $HARVESTING_FORMAL_NPR_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- FORMAL RCEF TABLE -->
                    <table id="HARVESTING_FORMAL_RCEF_TABLE" class="bg-white HARVESTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="10" class="RCEFcolor text-center p-3">
                                    <span style="font-size:16px;"> FORMAL SEED'S SYSTEM - RCEF</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="HarvestingBackToNPR">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Naxtcolor btn btn-sm btn-secondary" id="HarvestingNextTableOWNOTHRES">
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
                            <tr>
                                <td class="first_td">
                                    <select id="HARVESTING_FORMAL_RCEF_selection" onchange="Harvesting_AddBarangayFORMAL_RCEF()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $HARVESTING_FORMAL_RCEF_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- FORMAL OWNOTHERS TABLE -->
                    <table id="HARVESTING_FORMAL_OWNOTHERS_TABLE" class="bg-white HARVESTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="10" class="OwnOtherscolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - OWN/OTHERS</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="HarvestingBackToRCEF">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="HarvestingNextTableINFORMAL">
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
                            <tr>
                                <td class="first_td">
                                    <select id="HARVESTING_FORMAL_OWNOTHERS_selection" onchange="Harvesting_AddBarangayFORMAL_OWNOTHERS()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $HARVESTING_FORMAL_OWNOTHERS_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- FORMAL INFORMAL TABLE -->
                    <table id="HARVESTING_INFORMAL_TABLE" class="bg-white HARVESTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="10" class="Informalcolor text-center p-3">
                                    <span style="font-size:16px;">INFORMAL SEED'S SYSTEM</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="HarvestingBackToOWNOTHERS">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="HarvestingNextTableFSS">
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
                            <tr>
                                <td class="first_td">
                                    <select id="HARVESTING_INFORMAL_selection" onchange="Harvesting_AddBarangayINFORMAL()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $HARVESTING_INFORMAL_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- FORMAL FARMER SAVE SEED TABLE -->
                    <table id="HARVESTING_FSS_TABLE" class="bg-white HARVESTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="4" class="FSScolor text-center p-3">
                                    <span style="font-size:16px;">FARMER SAVED SEED'S</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="HarvestingBackToINFORMAL">
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
                            <tr>
                                <td class="first_td">
                                    <select id="HARVESTING_FSS_selection" onchange="Harvesting_AddBarangayFSS()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $HARVESTING_FSS_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                    </table>
                </form>
                
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="shadow btn btn-success" id="SubmitDataHarvested">FINISH INSERTING</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal Insert Data for Planting -->
<div class="modal fade" id="InsertModalPlanting" tabindex="-1" aria-labelledby="InsertingModalPlantingLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-full-height">
        <div class="modal-content bg-light">
            <div class="modal-header justify-content-between">
                <h5 class="modal-title" id="InsertingModalPlantingLabel">IRRIGATED - Insert Data Planting</h5>
                <button type="button" class="btn" id="cancelPlantingInsert" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">

            <strong>
                <div class="mt-3 mb-3" style="text-align: center;">
                    <p id="P-season" style="display:inline-block; text-transform: uppercase;"></p>
                    <p id="P-period" style="display:inline-block; text-transform: uppercase;"></p>
                </div>
            </strong>

    
                <form id="PLANTING_INSERT_FORM">
                    <!-- FORMAL NPR TABLE -->
                    <table id="PLANTING_FORMAL_NPR_TABLE" class="bg-white PLANTING_TABLE">
                        <thead>
                            <tr>
                                <th colspan="7" class="NRPcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - NRP</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="PlantingBackToSetUp">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="PlantingNextTableRCEF">
                                            <i class="bi bi-arrow-right"></i> Next
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor" rowspan="2">Barangay</th>
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
                            <tr>
                                <td class="first_td">
                                    <select id="Planting_FORMAL_NPR_selection" onchange="Planting_AddBarangayFORMAL_NPR()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $PLANTING_FORMAL_NPR_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- FORMAL RCEF TABLE -->
                    <table id="PLANTING_FORMAL_RCEF_TABLE" class="bg-white PLANTING_TABLE d-none">
                    <thead>
                            <tr>
                                <th colspan="7" class="RCEFcolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - RCEF</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="PlantingBackToNPR">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="PlantingNextTableOWNOTHRES">
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
                            <tr>
                                <td class="first_td">
                                    <select id="PLANTING_FORMAL_RCEF_selection" onchange="Planting_AddBarangayFORMAL_RCEF()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $PLANTING_FORMAL_RCEF_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <!-- FORMAL OWNOTHERS TABLE -->
                    <table id="PLANTING_FORMAL_OWNOTHERS_TABLE" class="bg-white PLANTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="7" class="OwnOtherscolor text-center p-3">
                                    <span style="font-size:16px;">FORMAL SEED'S SYSTEM - OWN/OTHERS</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="PlantingBackToRCEF">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="PlantingNextTableINFORMAL">
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
                            <tr>
                                <td class="first_td">
                                    <select id="PLANTING_FORMAL_OWNOTHERS_selection" onchange="Planting_AddBarangayFORMAL_OWNOTHERS()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $PLANTING_FORMAL_OWNOTHERS_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <!-- FORMAL INFORMAL TABLE -->
                    <table id="PLANTING_INFORMAL_TABLE" class="bg-white PLANTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="7" class="Informalcolor text-center p-3">
                                    <span style="font-size:16px;">INFORMAL SEED'S SYSTEM</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="PlantingBackToOWNOTHERS">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                    <span style="float: right;">
                                        <button type="button" class="shadow Nextcolor btn btn-sm btn-secondary" id="PlantingNextTableFSS">
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
                            <tr>
                                <td class="first_td">
                                    <select id="PLANTING_INFORMAL_selection" onchange="Planting_AddBarangayINFORMAL()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $PLANTING_INFORMAL_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <!-- FORMAL FARMER SAVE SEED TABLE -->
                    <table id="PLANTING_FSS_TABLE" class="bg-white PLANTING_TABLE d-none">
                        <thead>
                            <tr>
                                <th colspan="3" class="FSScolor text-center p-3">
                                    <span style="font-size:16px;">FARMER SAVED SEED'S</span>
                                    <span style="float: left;">
                                        <button type="button" class="shadow Backcolor btn btn-sm btn-secondary me-2" id="PlantingBackToINFORMAL">
                                            <i class="bi bi-arrow-left"></i> Back
                                        </button>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <th class="Barangaycolor" rowspan="1">Barangay</th>
                                <td>Area Harvested (Ha)</td>
                                <td>Average Yield (MT/Ha)</td>
                            </tr>
                        </thead>
                            <tr>
                                <td class="first_td">
                                    <select id="PLANTING_FSS_selection" onchange="Planting_AddBarangayFSS()">
                                        <option value="" disabled selected>Select a Barangay</option>
                                        <?php echo $PLANTING_FSS_barangayOptions; ?>
                                    </select>
                                </td>
                            </tr>
                    </table>

                </form>


            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="shadow btn btn-success" id="SubmitDataPlanted">FINISH INSERTING</button>
            </div>
        </div>
    </div>
</div>





    
    <!-- Modal EXPORT-->
    <div class="modal fade" id="exportmodalIrrigated" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
                <div class="modal-header justify-content-between">
                    <span class="modal-title" id="exampleModalLabel"><i class="bi bi-filetype-csv"></i> Export Data Irrigated</span>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="exportirrigated">
                    <div class="modal-body p-5 bg-light">

                        <div class="form-group">
                            <label  class="monthexportIrrigated text-secondary" for="monthexportIrrigated">Month : </label>
                            <select class="form-select" name="monthexport" id="monthexportIrrigated" required>
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
                            <label class="rangeDateExportIrrigated text-secondary" for="rangeDateExportIrrigated">Range Date :</label>
                            <select class="form-select" name="rangeDateExport" id="rangeDateExportIrrigated" required>
                                <option value="" disabled selected>Select Range Date</option>
                                <option value="1-15">1-15</option>
                                <option value="1-30">1-30</option>
                                <option value="16-30">16-30</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label class="yearExportIrrigated text-secondary" for="yearExportIrrigated">Select Year :</label>
                            <?php $currentYear = date("Y"); ?>
                            <input type="number" class="form-control" name="yearExport" id="yearExportIrrigated" placeholder="Enter Year" min="2000" max="<?php echo $currentYear; ?>" required>
                        </div>

                        <div class="form-group mt-3">
                            <label class="mayorsnameexportIrrigated text-secondary" for="mayorsnameexportIrrigated">Mayor's Name : </label>
                            <input type="text" class="form-control" name="mayorsnameexport" id="mayorsnameexportIrrigated" placeholder="Enter the Mayor's Name" required  oninput="this.value = this.value.toUpperCase();">
                        </div>

                    </div>

                        <div class="modal-footer bg-light d-flex justify-content-center align-items-center">
                            <button type="submit" name="btnexport" id="btnexportIrrigated" class="shadow btn btn-success">Submit</button>
                        </div>
                </form>
                
            </div>
        </div>
    </div>










</div>

    <!-- Script files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script src="systemjs/ATDRirrigated.js"></script>



</body>
</html>