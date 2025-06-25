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

$Monthly_Harvest = [];
$PopularLandType = []; 
$PopularSeed = []; // Initialize color array
$highest_production = 0; // To store the highest Production
$highest_month = 0; // To store the month corresponding to the highest Production



// MONTHLY HARVEST - Loop through each month (1 to 12)
for ($month = 1; $month <= 12; $month++) {
    $stmt = $con->prepare("SELECT SUM(production) AS production FROM harvesting WHERE `month` = ? AND `year` = ?");
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result_harvested = $stmt->get_result();

    $total_production = 0; // Default to 0
    if ($result_harvested->num_rows > 0) {
        $row = $result_harvested->fetch_assoc();
        $total_production = $row['production'] !== null ? (float)$row['production'] : 0; // Ensure it's a float
    }

    $formatted_production = number_format($total_production, 2, '.', '');
    $Monthly_Harvest[] = $formatted_production;

    // Check if the current Production is higher than the highest found so far
    if ($total_production > $highest_production) {
        $highest_production = $total_production; // Update highest Production
        $highest_month = $month; // Update month for the highest Production
    }
}

// Format Highest Production to 2 decimal places
$highest_production = number_format($highest_production, 2, '.', '');

// Calculate the total harvest and format to 2 decimal places
$Total_Harvest = array_sum($Monthly_Harvest);
$Total_Harvest = number_format($Total_Harvest, 2, '.', '');

// POPULAR LAND TYPE - Loop through land types
for ($landtype = 1; $landtype <= 3; $landtype++) {
    $stmt = $con->prepare("SELECT SUM(production) AS total_production FROM harvesting WHERE `landtype` = ? AND `year` = ?");
    $stmt->bind_param("ii", $landtype, $year);
    $stmt->execute();
    $result_landtype = $stmt->get_result();

    // Check if the result exists and store it in the array
    if ($result_landtype->num_rows > 0) {
        $row = $result_landtype->fetch_assoc();
        $PopularLandType[$landtype - 1] = $row['total_production'] !== null ? number_format((float)$row['total_production'], 2, '.', '') : 0; // Format
    } else {
        // Set to 0 if no data was found for that land type
        $PopularLandType[$landtype - 1] = 0;
    }
}

// POPULAR SEED TYPE - Loop through seed types
for ($seed = 1; $seed <= 6; $seed++) {
    $stmt = $con->prepare("SELECT SUM(production) AS total_production FROM harvesting WHERE `seed_name` = ? AND `year` = ?");
    $stmt->bind_param("ii", $seed, $year);
    $stmt->execute();
    $result_seed = $stmt->get_result();

    // Check if the result exists and store it in the array
    if ($result_seed->num_rows > 0) {
        $row = $result_seed->fetch_assoc();
        $PopularSeed[$seed - 1] = $row['total_production'] !== null ? number_format((float)$row['total_production'], 2, '.', '') : 0; // Format
    } else {
        // Set to 0 if no data was found for that land type
        $PopularSeed[$seed - 1] = 0;
    }
}

// Create an associative array to return both variables
$response = [
    'PopularLandType' => $PopularLandType,
    'Monthly_Harvest' => $Monthly_Harvest,
    'PopularSeed' => $PopularSeed,
    'Highest_Production' => $highest_production,
    'Total_Harvest' => $Total_Harvest
];

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
