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


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="systemcss/PA.css">

    <style>
        
        .modal-backdrop{
            display:none;
        }

    </style>
</head>


<body>

    <div class="content p-5 bg-image">
        
        <div class="row mb-3">
            <div class="col-12">
                <div class="bg-custom">
                    <div class="row">
                        <div class="col-12 text-center p-3 text-white ">
                            <span class="text-white">Production Predictor</span>
                            <div class="inputfield text-white">
                                <p class="fw-custom">Estimate Based on Harvest Area</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">


            <div class="col-6 d-flex"> 
                <!-- Predictor -->
                <div class="bg-custom p-5 text-center text-white flex-grow-1">
                    <span>Harvested Area (in Hectares) :</span>
                    
                    <form id="PredictForm">
                        <div class="text-center text-white d-flex justify-content-center align-items-center">
                            <!-- Input for the area (allow decimal) -->
                            <input type="number" min="1" class="text-center form-control mb-2 mt-2" name="AreaHarvest" id="AreaHarvest" style="width: 70%;" required placeholder="Enter Hectares" step="any">
                        </div>
                        <!-- Button to trigger form submission or action -->
                        <button type="submit" class="btn btn-success mt-2" id="AreaHarvestBtn" style="width: 30%;">Submit</button>
                    </form>

                    <!-- Jumping dots animation (hidden initially) -->
                    <div class="mt-5 jumping-dots d-none" id="jumping-dots">
                        <div class="jumping_dots_1"></div>
                        <div class="jumping_dots_2"></div>
                        <div class="jumping_dots_3"></div>
                        <div class="jumping_dots_4"></div>
                    </div>

                    <!-- Static dots (always visible) -->
                    <div class="mt-5 static-dots">
                        <div class="static_dots_1"></div>
                        <div class="static_dots_2"></div>
                        <div class="static_dots_3"></div>
                        <div class="static_dots_4"></div>
                    </div>

                </div>
            </div>

            <!-- Result Area -->
            <div class="col-6 d-flex justify-content-center align-items-center">
                <div class="bg-custom p-5 text-center text-white flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                    <span>Estimated production from harvested area :</span>
                    <div class="circle_result">
                        <p>MT</p>
                    </div>
                    <div class="circle d-none" id="circle">
                        <p>Analyzing ..</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="bg-custom p-3 text-center text-white border border-info">
                    <strong>Reminder:</strong> The production estimates provided by this tool are based on historical data from past harvest seasons. These predictions are intended to give you a general idea of expected production, but actual results may vary depending on factors like weather and farming conditions.
                </div>
            </div>
        </div>





    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>


    <script>
        
        $(document).on('submit', '#PredictForm', function (e) {
            e.preventDefault();

            
            $('#jumping-dots').removeClass('d-none');
            $('#circle').removeClass('d-none');
            $('.static-dots').addClass('d-none');
            $('.circle_result').addClass('d-none');

            var areaHarvestValue = $('#AreaHarvest').val();
            var production = 4.35 * areaHarvestValue - 4.18;

            setTimeout(function() {
                var production = 4.35 * areaHarvestValue - 4.18;
                $('.circle_result p').text(production.toFixed(2) + " MT");
                
                $('#jumping-dots').addClass('d-none');
                $('#circle').addClass('d-none');
                $('.static-dots').removeClass('d-none');
                $('.circle_result').removeClass('d-none');
            }, 3000);
        });
       
    </script>

</body>
</html>
