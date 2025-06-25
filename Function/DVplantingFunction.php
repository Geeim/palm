<?php
include "../connection.php";

// Check if the year is set in the GET request and is a valid integer
if (isset($_GET['year'])) {
    $year = intval($_GET['year']);
} else {
    // Default to the current year if not set or invalid
    $year = date('Y');
}

// Ensure the year is within a valid range
if ($year < 2000 || $year > date('Y')) {
    $year = date('Y'); // Set to current year if out of range
}

$Monthly_Planted = [];
$PlantedLandType = []; 
$PlantedSeed = []; // Initialize color array
$highest_planted = 0; // To store the highest yield
$highest_month = 0; // To store the month corresponding to the highest yield

// Loop through each month (1 to 12)
for ($month = 1; $month <= 12; $month++) {
    $stmt = $con->prepare("SELECT SUM(area_planted) AS area_planted FROM planting WHERE `month` = ? AND `year` = ?");
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result_planted = $stmt->get_result();

    $area_planted = 0; // Default to 0
    if ($result_planted->num_rows > 0) {
        $row = $result_planted->fetch_assoc();
        $area_planted = $row['area_planted'] !== null ? (float)$row['area_planted'] : 0; // Ensure it's a float
    }

    // Store the monthly yield in the array formatted to 2 decimal places
    $Monthly_Planted[] = number_format($area_planted, 2, '.', '');

    // Check if the current average yield is higher than the highest found so far
    if ($area_planted > $highest_planted) {
        $highest_planted = $area_planted; // Update highest yield
        $highest_month = $month; // Update month for the highest yield
    }
} 

// Calculate total planted area and format to 2 decimal places
$Total_Planted = array_sum(array_map('floatval', $Monthly_Planted)); // Ensure it's float before formatting
$Total_Planted = number_format($Total_Planted, 2, '.', '');

// PLANTED LAND TYPE - Loop through land types
for ($landtype = 1; $landtype <= 3; $landtype++) {
    $stmt = $con->prepare("SELECT SUM(area_planted) AS total_planted FROM planting WHERE `landtype` = ? AND `year` = ?");
    $stmt->bind_param("ii", $landtype, $year);
    $stmt->execute();
    $result_landtype = $stmt->get_result();

    // Check if the result exists and store it in the array
    if ($result_landtype->num_rows > 0) {
        $row = $result_landtype->fetch_assoc();
        $PlantedLandType[$landtype - 1] = $row['total_planted'] !== null ? number_format((float)$row['total_planted'], 2, '.', '') : 0; // Format
    } else {
        // Set to 0 if no data was found for that land type
        $PlantedLandType[$landtype - 1] = 0;
    }
}

// PLANTED SEED TYPE - Loop through seed types
for ($seed = 1; $seed <= 6; $seed++) {
    $stmt = $con->prepare("SELECT SUM(area_planted) AS total_planted FROM planting WHERE `seed_name` = ? AND `year` = ?");
    $stmt->bind_param("ii", $seed, $year);
    $stmt->execute();
    $result_seed = $stmt->get_result();

    // Check if the result exists and store it in the array
    if ($result_seed->num_rows > 0) {
        $row = $result_seed->fetch_assoc();
        $PlantedSeed[$seed - 1] = $row['total_planted'] !== null ? number_format((float)$row['total_planted'], 2, '.', '') : 0; // Format
    } else {
        // Set to 0 if no data was found for that land type
        $PlantedSeed[$seed - 1] = 0;
    }
}

// Format the highest planted value to 2 decimal places
$highest_planted = number_format($highest_planted, 2, '.', '');

// Create an associative array to return both variables
$response = [
    'Monthly_Planted' => $Monthly_Planted,
    'Total_Planted' => $Total_Planted,
    'Highest_Planted' => $highest_planted,
    'PlantedSeed' => $PlantedSeed,
    'PlantedLandType' => $PlantedLandType,
];

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
