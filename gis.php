<?php 
include 'connection.php';
include 'gisdata/Maragondon_0.php';

// Condition for Session for DASHBOARD VIEW ONLY
if(empty($_GET['validation']) || $_GET['validation'] != true){
    if(empty($_SESSION['MAstatus']) || $_SESSION['MAstatus'] == 'MAinvalid'){
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>window.location.href = 'MADashboard.php';</script>";
    }
}


?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        
        <link rel="stylesheet" href="giscss/leaflet.css">
        <link rel="stylesheet" href="giscss/qgis2web.css">
        <link rel="stylesheet" href="giscss/leaflet-search.css">

        <script src="gisjs/qgis2web_expressions.js"></script>
        <script src="gisjs/leaflet.js"></script>
       
        <script src="gisjs/leaflet.rotatedMarker.js"></script>
        <script src="gisjs/leaflet.pattern.js"></script>
        <script src="gisjs/leaflet-hash.js"></script>
        <script src="gisjs/Autolinker.min.js"></script>
        <script src="gisjs/rbush.min.js"></script>
        <script src="gisjs/labelgun.min.js"></script>
        <script src="gisjs/labels.js"></script>
        <script src="gisjs/leaflet-search.js"></script>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
        
        <!-- FONT -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        
        
        <style>
        
        /*Map View*/
        #map {
            width: 100%; /* Adjust as needed */
            height: 100%; /* Adjust as needed */
            z-index: 0; /* para umilalim yung mapa */
        }
        .col-12 {
            min-height: 100vh; /* to view the map*/
            padding: 0px;
            padding-left:12px;
        }
        .container-fluid{
            width:100%;
            padding:0;
        }

        .row {
            width: 100%;
            min-width: 900px;
        }

        /* POP UP */
        .leaflet-popup-content-wrapper, .leaflet-popup-tip{
            border-radius: 0;
            background-color: rgba(0, 0, 0, 0.9); 
            color: white;
        }
        .custom-popup {
            height: auto; /* Adjust height as needed */
            font-family: "Montserrat", sans-serif;
        }

        /* Legend Design */
        .color-ramp {
            width: 100%; /* Full width for the ramp */
            height: 15px; /* Adjust height as needed */
            border: 1px solid #ccc; /* Optional: border for better visibility */
            margin-bottom: 5px; /* Space between ramp and labels */
        }
        .bg-costum{
            background-color: rgba(0, 0, 0, 0.3); 
            border-radius: 0;
        }
        .legend-labels span{
            margin: 0 10px 0 10px;
            font-size: 12px;
        }
        .card {
            position: fixed;
            z-index: 1; /* Ensure card is on top of the map */
            margin-top: 20px;
            margin-right: 20px;
        }
        .legend-container {
            max-height: 250px; 
            overflow-y: auto; 
            padding: 10px;
        }

        /*Background Design*/
        .bg-image {
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url('systemimg/Farm1.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            background-repeat: no-repeat;
            background-attachment: fixed;
            width: 100%;
            min-width: 100%;
            height:100%;
            min-height:100% ;
        }
    
        /*Label Design*/
        .tooltip-box{
            background-color: rgba(0, 0, 0, 0.7); 
            padding: 5px; 
            border-radius: 5px; 
            color: white; 
            font-family: 'Arial', sans-serif;
            border-radius: 0;
        }
        .label-barangayname{
            font-size: 9pt; 
            font-weight: bold;
        }
        .label-content1{
            font-size: 9pt; 
        }


        /*Search and Min and Max View Padding*/
        .leaflet-top, .leaflet-left{
            padding: 20px !important;
        }


        /*SEARCH LAYER*/
        /*Icon Design*/
        .leaflet-control-search{
            border: none !important;
            padding-top: 10px;
        }

        /*Search Result*/
        /* Search Scroll Design*/
        .search-tooltip::-webkit-scrollbar {
            width: 8px; /* Width of the scrollbar */
        }
        .search-tooltip::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1); /* Track color */
        }
        .search-tooltip::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.5); /* Scrollbar color */
            border-radius: 4px; /* Round edges of the scrollbar */
        }
        .search-tooltip::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.7); /* Scrollbar color on hover */
        }

        /*Seach Input Design*/
        .search-input {
            border: none !important; /* Remove the border */
            outline: none; /* Remove the focus outline */
            background-color: rgba(0, 0, 0, 0.5) !important; /* Background color */
            color: white !important; /* Text color */
            box-sizing: border-box; /* Include padding in width calculation */
            width: 150px; /* Increase the width */
            padding: 22px !important;/* Add padding for more space inside */
            border-radius: 0 !important;
        }

        /*List Result Search*/
        .search-tooltip {
            margin-top: 10px;
            border-radius: 0px;
            background-color:rgba(0, 0, 0, .5)!important ;
            width: 190px;
            max-height: 500px !important;/* Set a maximum height for the tooltip */
            overflow-y: auto; /* Enable scrolling if content exceeds max height */
        }
        .search-tooltip li {
            color: white !important;
            cursor: pointer; /* Change cursor to indicate clickable items */
            background-color: rgba(0, 0, 0, 0.1) !important; 
            padding: 10px !important;
        }
        .search-tooltip li:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;/* Light hover effect */
        }

        .leaflet-control-zoom{
            background: transparent !important; /* Make background transparent */
            box-shadow: none !important; /* Remove shadow */
            border-radius: 0px;
            border-color: transparent !important;
        }

        .leaflet-control-zoom a {
            background: transparent !important; /* Make zoom buttons transparent */
            color: white !important; /* Change icon color to white for visibility */
            border: none !important; /* Remove border from buttons */
            border-radius: 0 !important;/* Remove rounded corners */
        }

        .leaflet-control-zoom a:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }

        .leaflet-control-zoom{
            background-color:rgba(0, 0, 0, .5)!important ;
            padding: 5px;
        }




        .leaflet-control.search-exp {
            display: flex; /* Use flexbox for layout */
            align-items: center; /* Align items vertically */
        }

        .search-input {
            max-width: 527px; /* Adjust width as needed */
            border: 1px solid #ccc; /* Optional: Add border */
            border-radius: 4px 0 0 4px; /* Rounded corners */
            outline: none; /* Remove default outline */
            flex: 1; /* Allow input to take available space */
            padding: 10px;
        }

        .search-buttoncustom-icon {
            color: rgb(105, 150, 117);
            text-decoration: none; /* Remove underline */
            display: flex; /* Center icon */
            align-items: center; /* Center icon vertically */
            justify-content: center; /* Center icon horizontally */
        }

        .leaflet-popup-close-button{
            color: white !important;
            font-size: 17px !important;
        }


        .modal-backdrop{
            display:none;
        }

        
        </style>
        <title></title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 justify-content-end align-items-start d-flex">
                
                    <div class="card bg-costum text-white">
                        <div class="legend-ramp">
                            <div id="legend" class="legend-container">
                                <div class="legend-item"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="map" class="bg-image"></div>

                </div>
            </div>
        </div>

        <!-- Script files at the end of the body -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
    </body>
       
   <!--LEGEND JS-->
   <script>
        function createLegend() {
            var legendContent = '<div class="legend-item">';

            // Define colors for the ramp
            var colors = [
                'rgba(144, 238, 150, 1.0)',
                'rgba(150, 255, 120, 1.0)', //Softer Light Green
                'rgba(60, 179, 113, 1.0)', // Medium Sea Green
                'rgba(34, 139, 34, 1.0)', // Forest Green
                'rgba(0, 100, 0, 1.0)' // Dark Green
            ];

            // Create a color ramp without gradient
            legendContent += '<div class="text-center mb-2"><span class="text-center fw-bold">METRIC TONS</span></div>';
            legendContent += '<div class="color-ramp" style="display: flex; height: 20px;">';
            for (var i = 0; i < colors.length; i++) {
                legendContent += '<div class="color-block" style="background: ' + colors[i] + '; flex: 1;"></div>';
            }
            legendContent += '</div>';
            legendContent += '<div class="legend-labels text-center" style="display: flex; justify-content: space-between;">';
            legendContent += '<span>0 - 1K</span>'; 
            legendContent += '<span>1K - 2K</span>'; 
            legendContent += '<span>2K - 3K</span>'; 
            legendContent += '<span>4K - 5K</span>'; 
            legendContent += '<span>5K + </span>'; 
            legendContent += '</div>';
            legendContent += '</div>';

            legendContent += '</div>';
            legendContent += '<div class="legend-labels text-center" style="display: flex; justify-content: space-between;">';
            
            legendContent += '<span></span>'; 
            legendContent += '<span>Low</span>'; 
            legendContent += '<span>Average</span>'; 
            legendContent += '<span>High</span>'; 
            legendContent += '<span></span>'; 
            legendContent += '</div>';
            legendContent += '</div>';

            // Add the legend to the HTML element
            document.getElementById('legend').innerHTML = legendContent;
        }

        // Call the createLegend function to generate the legend
        createLegend();

    </script>

        
    <!--FIXED DONE : WAG GAGALAWIN-->
    <script>

        var map = L.map('map', { zoomControl: true, maxZoom: 17, minZoom: 12});
        var autolinker = new Autolinker({truncate: {length: 30, location: 'smart'}});
        
         //IMPORTANT - Function to remove empty rows from popup content
        function removeEmptyRowsFromPopupContent(content, feature) {
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            var rows = tempDiv.querySelectorAll('tr');
            for (var i = 0; i < rows.length; i++) {
                var td = rows[i].querySelector('td.visible-with-data');
                var key = td ? td.id : '';
                if (td && td.classList.contains('visible-with-data') && feature.properties[key] == null) {
                    rows[i].parentNode.removeChild(rows[i]);
                }
            }
            return tempDiv.innerHTML;
        }



        //IMPORTANT - Set Maximum View of Map
        var bounds_group = new L.featureGroup([]);
        
        function setBounds() {
            if (bounds_group.getLayers().length) {
                // Getting the current bounds
                var bounds = bounds_group.getBounds();
                
                // Adjusting the extent by padding
                var padding = 0; // Example padding, adjust as needed
                bounds.pad(padding);
                
                // Fitting the map to the adjusted bounds
                map.fitBounds(bounds);
                
                // Setting the maximum bounds for the map
                var maxBoundsPadding = 0.6; // Example padding for max bounds, adjust as needed
                var maxBounds = bounds.pad(maxBoundsPadding); // Adjust the padding for max bounds
                map.setMaxBounds(maxBounds);
            }
        }



        //IMPORTANT - For Pop Up Content
        function pop_Maragondon_0(feature, layer) {
            // Customize the popup content
            var popupContent = 
            '<div class="custom-popup">\
                <p class="fw-bold text-center">' + (feature.properties['BarangayName'] !== null ? autolinker.link(feature.properties['BarangayName']) : 'N/A') + '</p>\
                <p> Irrigated: ' + (parseFloat(feature.properties['Irrigated']) || 0).toFixed(2) + ' MT</p>\
                <p> Rainfed: ' + (parseFloat(feature.properties['Rainfed']) || 0).toFixed(2) + ' MT</p>\
                <p> Upland: ' + (parseFloat(feature.properties['Upland']) || 0).toFixed(2) + ' MT</p>\
            </div>';

            var content = removeEmptyRowsFromPopupContent(popupContent, feature);
            
            layer.bindPopup(content, {
                maxHeight: 400,
                className: 'custom-popup' // Add custom class here
            });
        }

        //IMPORTANT - STYLE OF EVERY BARANGAY
        function style_Maragondon_0_0(feature) {
            const value = feature.properties['TotalHarvest']; // Accessing the Change Total

            let fillColor;
       
            if (value >= 0 && value < 1000) {
                fillColor = 'rgba(144, 238, 150, 1.0)';//Softer Light Green
            } else if (value >= 1000 && value < 2000) {
                fillColor =  'rgba(150, 255, 120, 1.0)'; // Light Green for 2.5K-5K
            } else if (value >= 2000 && value < 3000) {
                fillColor = 'rgba(60, 179, 113, 1.0)'; // Medium Sea Green for 5K-7.5K
            } else if (value >= 3000 && value < 4000) {
                fillColor = 'rgba(34, 139, 34, 1.0)'; // Forest Green for 7.5K-10K
            } else { 
                fillColor = 'rgba(0, 100, 0, 1.0)'; // Dark Green for values 10K+
            }


            return {
                pane: 'pane_Maragondon_0',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: fillColor,
                interactive: true,
            };
        }

        
        map.createPane('pane_Maragondon_0');
        map.getPane('pane_Maragondon_0').style.zIndex = 400;
        map.getPane('pane_Maragondon_0').style['mix-blend-mode'] = 'normal';
        var layer_Maragondon_0 = new L.geoJson(json_Maragondon_0, {
            attribution: '',
            interactive: true,
            dataVar: 'json_Maragondon_0',
            layerName: 'layer_Maragondon_0',
            pane: 'pane_Maragondon_0',
            onEachFeature: pop_Maragondon_0,
            style: style_Maragondon_0_0,
        });


        bounds_group.addLayer(layer_Maragondon_0);
        map.addLayer(layer_Maragondon_0);
        setBounds();
        var i = 0;


        //IMPORTANT - SHOWING TEXT IN POLYGON
        layer_Maragondon_0.eachLayer(function(layer) {
            var context = {
                feature: layer.feature,
                variables: {}
            };

            // Modify this part to include more text
            var tooltipContent = '';
            if (layer.feature.properties['BarangID'] !== null) {
                tooltipContent += '<div class="tooltip-box">';
                tooltipContent += '<div class="label-barangayname text-center">' + layer.feature.properties['BarangayName'] + '</div>';
                tooltipContent += '<div class="label-content1">Total Harvested: ' + (parseFloat(layer.feature.properties['TotalHarvest']) || 0).toFixed(2) + ' MT</div>';
                tooltipContent += '</div>'; 
            }


            layer.bindTooltip(tooltipContent, { 
                permanent: true, 
                offset: [-0, -16], 
                className: 'css_Maragondon_0' 
            });
            
            labels.push(layer);
            totalMarkers += 1;
            layer.added = true;
            addLabel(layer, i);
            i++;
        });


        map.addControl(new L.Control.Search({
            layer: layer_Maragondon_0,
            initial: false,
            hideMarkerOnCollapse: true,
            propertyName: 'BarangayName'}));

        map.on("zoomend", function(){
            resetLabels([layer_Maragondon_0]);
        });
        map.on("layeradd", function(){
            resetLabels([layer_Maragondon_0]);
        });
        map.on("layerremove", function(){
            resetLabels([layer_Maragondon_0]);
        });

        //FIXED PART
        document.getElementsByClassName('search-button')[0].className +=
         'custom-icon bi bi-search';
        resetLabels([layer_Maragondon_0]);

        document.getElementsByClassName('bi bi-search')[0].style.cssText = `
            font-size: 20px; 
            color:white;  
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.5);
            padding:12px;
            border-radius:none;
        `;
        resetLabels([layer_Maragondon_0]);

        document.getElementById('searchtext9').placeholder = 'Search Barangay';

</script>

</html>
