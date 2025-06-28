<?php
include "connection.php";

// Condition for Session for DASHBOARD VIEW ONLY
if(empty($_GET['validation']) || $_GET['validation'] != true){
    if(empty($_SESSION['MAstatus']) || $_SESSION['MAstatus'] == 'MAinvalid'){
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>window.location.href = 'MADashboard.php';</script>";
    }
}

$year = date('Y');

$querylowestyear = "SELECT MIN(year) AS year FROM planting;";
$result_lowestyear = mysqli_query($con, $querylowestyear);

if ($result_lowestyear) {
    $row = mysqli_fetch_assoc($result_lowestyear);
    $lowestyear = $row['year'];
}else{
    $lowestyear = '2000';
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Visualization - Planting</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/DVplanting.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <style>

    </style>
</head>
<body>
    <div class="content p-4 bg-image">
        
        <div class="row mb-3">
            <div class="col-12">
                <div class="bg-custom">
                    <div class="row">
                        <div class="col-3 text-center p-3">
                            <span class="text-white">Data Visualization</span>
                            <p class="text-white fw-custom">PLANTING</p>
                        </div>
                        <div class="col-3 text-center p-3">
                            <span class="text-white">Year Total Planted</span>
                            <h5 id="total-planted-2" class="text-white"></h5>
                        </div>
                        <div class="col-3 text-center p-3">
                            <span class="text-white">Highest Planted Month</span>
                            <h5 id="total-planted-3" class="text-white"></h5>
                        </div>
                        <div class="col-3 text-center p-3">
                            <label for="year-input" class="text-white">Data Visualization Year</label>
                            <div class="p-1 inputfield">
                                <input type="number" id="year-input" value="<?php echo $year; ?>" min="<?php echo $lowestyear?>" max="<?php echo date('Y'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 linegraph">
                <div class="bg-custom p-3 text-center">
                    <span class="text-white" id="year-label">Area Planted Year (<?php echo $year?>)</span>
                    <canvas class="pt-2" id="APYID" height="100" class="maingraph"></canvas>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-6 mt-3">
                <div class="p-3 bg-custom text-white text-center">
                    <span class="text-white" id="LandType-label">Land Type Chart (<?php echo $year?>)</span>
                    <div class="chart-container">
                        <canvas class="pt-2" id="LandTypeID"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-6 mt-3">
                <div class="p-3 bg-custom text-white text-center">
                    <span class="text-white" id="SeedType-label">Seed Type Chart (<?php echo $year?>)</span>
                    <div class="chart-container">
                        <canvas class="pt-3 small-pie-chart" id="SeedID"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Script files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

    <script>

    $(document).ready(function() {
            
        const APYLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const LandTypeDataLabels = ['Irrigated', 'Rainfed', 'Upland'];
        const SeedDataLabels= ['Hybrid Seeds','Registered Seeds','Certified Seeds','Starter RS and CS by CSB','Tagged FS/RS by Accr. Seed Grower','Traditional Varieties', ];

        let APYChart; // Year Ave Yield Chart 
        let LandTypeChart; // Land Type Chart 
        let SeedChart; // Seed Chart 

        function fetchHarvestData(year) {
            $.ajax({
                url: 'Function/DVplantingFunction.php',
                method: 'GET',
                dataType: 'json',
                data: { year: year },
                success: function(response) {
                    var PlantedLandType = response.PlantedLandType;
                    var Monthly_Planted = response.Monthly_Planted;
                    var PlantedSeed = response.PlantedSeed;
                    var Highest_Planted  = response.Highest_Planted; 
                    var Total_Planted = response.Total_Planted;

                    document.getElementById('total-planted-2').textContent = Total_Planted + ' HA';
                    document.getElementById('total-planted-3').textContent = Highest_Planted + ' HA';
                   
                    // Start Year Average Yield Chart
                    const APYID = document.getElementById('APYID').getContext('2d');
                    const APYBGcolor = APYID.createLinearGradient(0, 0, 0, 400);
                    APYBGcolor.addColorStop(0, 'rgba(69, 99, 77, 1)');
                    APYBGcolor.addColorStop(1, 'rgba(69, 99, 77, .30)');

                    // Destroy the previous chart instance if it exists
                    if (APYChart) {
                        APYChart.destroy(); // Properly destroy the existing chart instance
                    }

                    const APYData = {
                        labels: APYLabels,
                        datasets: [{
                            label: 'Total Area Planted per Month (HA)', // You can leave this or set it to an empty string
                            data: Monthly_Planted,
                            backgroundColor: APYBGcolor,
                            borderColor: 'white',
                            borderWidth: 1.5,
                            pointStyle: 'circle',
                            pointRadius: 7,
                            pointHoverRadius: 15,
                            pointBackgroundColor: '#45634d',
                            fill: true,
                            tension: 0.4
                        }]
                    };

                    // Create a new chart instance and assign it to the variable
                    APYChart = new Chart(APYID, {
                        type: 'line',
                        data: APYData,
                        options: {
                            responsive: true,
                            animation: {
                                duration: 1500, // Set duration of the animation
                                easing: 'easeOutQuad' // Choose easing function
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Total Area Planted (HA)',
                                        color: 'white',
                                        font: { size: 14 },
                                        padding: { bottom: 20 }
                                    },
                                    ticks: { color: 'white' }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Months',
                                        color: 'white',
                                        font: { size: 14 },
                                        padding: { top: 20 }
                                    },
                                    ticks: { color: 'white' }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false, // Hide the legend
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `Total Area Planted in ${APYLabels[tooltipItem.dataIndex]}: ${tooltipItem.raw} HA`;
                                        }
                                    },
                                    titleFont: { color: 'white' },
                                    bodyFont: { color: 'white' }
                                }
                            },
                        }
                    });


            
                    // Start Year LandTypeChart
                    const LandTypeID = document.getElementById('LandTypeID').getContext('2d');

                    if (LandTypeChart) {
                        LandTypeChart.destroy(); // Properly destroy the existing chart instance
                    }

                    // Create the Land Type Chart
                    LandTypeChart = new Chart(LandTypeID, {
                        type: 'bar',
                        data: {
                            labels: LandTypeDataLabels, // Labels for each land type
                            datasets: [{
                                label: '', // Set to empty string to remove the label from the dataset
                                data: PlantedLandType, // Data for each land type
                                backgroundColor: [
                                    'rgba(251, 251, 23, 0.6)', // Color for Irrigated (Yellow) - more transparent
                                    'rgba(135, 206, 250, 0.4)', // Color for Rainfed (Sky Blue) - more transparent
                                    'rgba(43, 136, 73, 0.4)'    // Color for Upland (Brown) - more transparent
                                ],
                                borderColor: 'white',
                                borderWidth: 1.5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 1500, // Set duration of the animation
                                easing: 'easeOutQuad' // Choose easing function
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: 'white' }
                                },
                                x: {
                                    ticks: { color: 'white' }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false, // Set to false to hide the legend entirely
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.label}: ${tooltipItem.raw} HA`; // Tooltip format
                                        }
                                    }
                                }
                            }
                        }
                    });


                    //Start Year SeedTypeChart
                    const SeedID = document.getElementById('SeedID').getContext('2d');

                    if (SeedChart) {
                        SeedChart.destroy(); // Properly destroy the existing chart instance
                    }

                    // Create the Land Type Chart
                    SeedChart = new Chart(SeedID, {
                        type: 'pie',
                        data: {
                            labels: ['Hybrid Seed','Registered Seed','Certified Seed','Starter Seed','Tagged Seed','Traditional Seed', ], // Labels for each land type
                            datasets: [{
                                label: '', // Set to empty string to remove the label from the dataset
                                data: PlantedSeed, // Data for each land type
                                backgroundColor: [
                                    'rgba(143, 49, 49, 1)',  // Dark Goldenrod (Brownish yellow)
                                    'rgba(171, 82, 163, 1)',   // Sienna (Earthy brown)
                                    'rgba(25, 117, 69, 1)',   // Forest Green
                                    'rgba(203, 219, 29, 1)', // Light Green
                                    'rgba(101, 207, 191, 1)',  // Peru (Yellowish brown)
                                    'rgba(222, 184, 135, 1)'  // Burly Wood (Light brown)
                                ],
                                borderColor: 'white',
                                borderWidth: 1.5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 1500, // Set duration of the animation
                                easing: 'easeOutQuad' // Choose easing function
                            },
                            scales: {
                                y: {
                                    display: false, // Hide x-axis
                                },
                                x: {
                                    display: false, // Hide x-axis
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true, // Ipakita ang legend
                                    position: 'right', // Posibleng values: 'top', 'left', 'bottom', 'right'
                                    labels: {
                                        color:'white',
                                    boxWidth: 20, // Lapad ng kulay ng box
                                        boxHeight: 15, // Taas ng box (optional)
                                        padding: 15, // Space sa pagitan ng mga label
                                    },
                                },  
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return `${tooltipItem.label}: ${tooltipItem.raw} HA`; // Tooltip format
                                        }
                                    }
                                }
                            }
                        }
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            
            });

            }

            // Fetch data on input change
            $("#year-input").on('input', function() {
                const year = $(this).val(); // Get the value from the input field
                $("#year-label").text('Area Planted Year (' + year + ')');
                $("#LandType-label").text('Land Type Chart (' + year + ')');
                $("#SeedType-label").text("Seed Type Chart (" + year + ")");
                fetchHarvestData(year); // Pass the value to the fetch function
            });

            // Initial fetch on page load
            fetchHarvestData($("#year-input").val());
            });

    </script>

</body>
</html>