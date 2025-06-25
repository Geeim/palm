<?php
    require('Function/ATsession.php');
    include "connection.php";
    $ATstatus = $_SESSION['ATstatus'];
    $IDemployee = $_SESSION['IDemployee'];


    include ('gisdata/Maragondon_0.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/ATDashboard.css">

</head>
<body>
    <div class="wrapper">
        <aside id="sidebar" class="expand">
            
            <!--TOP DISPLAY-->
            <div class="sidebar-logo text-white">
                <img src="systemimg/PALMWL.png" class="img-fluid" alt="PALM Logo">
            </div>

            <!--UL ALL FEATURES-->
            <ul class="sidebar-nav">

                <!--1ST NAV MENU-->
                <li class="sidebar-item">
                    <a data-url="ATmyaccount.php?validation=true" class="sidebar-link navlink">
                        <i class="bi bi-person"></i>
                        <span>My Account</span>
                    </a>
                </li>

                <!--2ND NAV MENU-->
                <li class="sidebar-item">
                    <a class="sidebar-link DRdrop">
                        <i class="bi bi-filetype-csv"></i>
                        <span>Data Records</span>
                        <span class="material-icons arrowdown">keyboard_arrow_down</span>
                    </a>

                    <ul id="DR" class="sidebar-dropdown list-unstyled">
                        <li class="sidebar-item">
                            <a data-url="ATDRirrigated.php?validation=true" class="sidebar-link navlink">
                                <div class="submenu-text">Irrigated Ecosystem</div>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a data-url="ATDRrainfed.php?validation=true" class="sidebar-link navlink">
                                <div class="submenu-text">Rainfed Ecosystem</div>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a data-url="ATDRupland.php?validation=true" class="sidebar-link navlink">
                                <div class="submenu-text">Upland Ecosystem</div>
                            </a>
                        </li>
                    </ul>

                </li>

                <!--3RD NAV MENU-->
                <li class="sidebar-item">
                    <a class="sidebar-link DVdrop">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Data Visualization</span>
                        <span class="material-icons arrowdown">keyboard_arrow_down</span>
                    </a>

                    <ul id="DV" class="sidebar-dropdown list-unstyled">
                        <li class="sidebar-item">
                            <a data-url="DVplanting.php?validation=true" class="sidebar-link navlink">
                                <div class="submenu-text">Planting</div>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a data-url="DVharvesting.php?validation=true" class="sidebar-link navlink">
                                <div class="submenu-text">Harvesting</div>
                            </a>
                        </li>
                    </ul>

                </li>

                <!--4TH NAV MENU-->
                <li class="sidebar-item">
                    <a data-url="gis.php?validation=true" class="sidebar-link navlink">
                        <i class="bi bi-geo-alt"></i>
                        <span>Maragondon Map</span>
                    </a>
                </li>
                <!--5TH NAV MENU-->
                <li class="sidebar-item">
                    <a data-url="PA.php?validation=true" class="sidebar-link navlink">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Predictive Analytics</span>
                    </a>
                </li>
            
                <!--6TH NAV MENU : LOG OUT-->
                <li class="sidebar-item">
                    <a data-url="Function/ATlogout.php" id="logout" class="sidebar-link">
                        <i class="bi bi-door-open"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>

        </aside>

         <!--SPA : Display the page-->
         <div class="main" id="main-content">
            <!--Display Content-->
        </div>
    </div>

    <!-- Script files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

     <!--All Script in This Page-->
     <script src="systemjs/ATDashboard.js" defer></script>

</body>
</html>