<?php
// Include the database connection
include('../connection.php');

// Check if the necessary POST parameters are set
if (isset($_POST['year'], $_POST['month'], $_POST['range_date'])) {
    // Get the data from the AJAX request
    $year = $_POST['year'];
    $month = $_POST['month'];
    $range_date = $_POST['range_date'];


    // NPR FORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
    $NPRquery = "
    WITH GroupedData AS (
        SELECT 
            p.landtype, 
            p.season_type, 
            p.seed_system_type, 
            p.project_type, 
            p.seed_name, 
            p.barangay, 
            p.area_planted, 
            p.no_farmers, 
            ROW_NUMBER() OVER (PARTITION BY p.barangay ORDER BY p.seed_name) AS row_num
        FROM planting p
        WHERE 
            p.landtype = 2 
            AND p.project_type = 1 
            AND p.seed_system_type = 1 
            AND p.`month` = ? 
            AND p.`year` = ?
            AND p.`range_date` = ?
    )
    SELECT 
        b.BarangayName AS FROMAL_NPR_Barangay, 

        SUM(CASE WHEN gd.seed_name = 1 THEN gd.area_planted ELSE 0 END) AS Hybrid_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 1 THEN gd.no_farmers ELSE 0 END) AS Hybrid_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 2 THEN gd.area_planted ELSE 0 END) AS Registered_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 2 THEN gd.no_farmers ELSE 0 END) AS Registered_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 3 THEN gd.area_planted ELSE 0 END) AS Certified_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 3 THEN gd.no_farmers ELSE 0 END) AS Certified_No_Farmers

    FROM GroupedData gd
    JOIN barangay b ON gd.barangay = b.IDbarangay  
    GROUP BY b.BarangayName 
    ORDER BY b.BarangayName;
    ";



    // RCEF FORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
    $RCEFquery = "
    WITH GroupedData AS (
        SELECT 
            p.landtype, 
            p.season_type, 
            p.seed_system_type, 
            p.project_type, 
            p.seed_name, 
            p.barangay, 
            p.area_planted, 
            p.no_farmers, 
            ROW_NUMBER() OVER (PARTITION BY p.barangay ORDER BY p.seed_name) AS row_num
        FROM planting p
        WHERE 
            p.landtype = 2 
            AND p.project_type = 2 
            AND p.seed_system_type = 1 
            AND p.`month` = ?
            AND p.`year` = ? 
            AND p.`range_date` = ?
    )
    SELECT 
        b.BarangayName AS FROMAL_RCEF_Barangay, 

        SUM(CASE WHEN gd.seed_name = 1 THEN gd.area_planted ELSE 0 END) AS Hybrid_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 1 THEN gd.no_farmers ELSE 0 END) AS Hybrid_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 2 THEN gd.area_planted ELSE 0 END) AS Registered_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 2 THEN gd.no_farmers ELSE 0 END) AS Registered_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 3 THEN gd.area_planted ELSE 0 END) AS Certified_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 3 THEN gd.no_farmers ELSE 0 END) AS Certified_No_Farmers

    FROM GroupedData gd
    JOIN barangay b ON gd.barangay = b.IDbarangay  
    GROUP BY b.BarangayName 
    ORDER BY b.BarangayName;
    ";

    // OWNOTHERS FORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
    $OWNOTHERSquery = "
    WITH GroupedData AS (
        SELECT 
            p.landtype, 
            p.season_type, 
            p.seed_system_type, 
            p.project_type, 
            p.seed_name, 
            p.barangay, 
            p.area_planted, 
            p.no_farmers, 
            ROW_NUMBER() OVER (PARTITION BY p.barangay ORDER BY p.seed_name) AS row_num
        FROM planting p
        WHERE 
            p.landtype = 2 
            AND p.project_type = 3 
            AND p.seed_system_type = 1 
            AND p.`month` = ? 
            AND p.`year` = ? 
            AND p.`range_date` = ?
    )
    SELECT 
        b.BarangayName AS FROMAL_OWNOTHERS_Barangay, 

        SUM(CASE WHEN gd.seed_name = 1 THEN gd.area_planted ELSE 0 END) AS Hybrid_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 1 THEN gd.no_farmers ELSE 0 END) AS Hybrid_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 2 THEN gd.area_planted ELSE 0 END) AS Registered_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 2 THEN gd.no_farmers ELSE 0 END) AS Registered_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 3 THEN gd.area_planted ELSE 0 END) AS Certified_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 3 THEN gd.no_farmers ELSE 0 END) AS Certified_No_Farmers

    FROM GroupedData gd
    JOIN barangay b ON gd.barangay = b.IDbarangay  
    GROUP BY b.BarangayName 
    ORDER BY b.BarangayName;
    ";

    // INFORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
    $INFORMALSquery = "
    WITH GroupedData AS (
        SELECT 
            p.landtype, 
            p.season_type, 
            p.seed_system_type, 
            p.project_type, 
            p.seed_name, 
            p.barangay, 
            p.area_planted, 
            p.no_farmers, 
            ROW_NUMBER() OVER (PARTITION BY p.barangay ORDER BY p.seed_name) AS row_num
        FROM planting p
        WHERE 
            p.landtype = 2
            AND p.project_type IS NULL 
            AND p.seed_system_type = 2 
            AND p.`month` = ? 
            AND p.`year` = ?
            AND p.`range_date` = ?
    )
    SELECT 
        b.BarangayName AS INFROMAL_Barangay, 

        SUM(CASE WHEN gd.seed_name = 4 THEN gd.area_planted ELSE 0 END) AS Starter_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 4 THEN gd.no_farmers ELSE 0 END) AS Starter_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 5 THEN gd.area_planted ELSE 0 END) AS Tagged_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 5 THEN gd.no_farmers ELSE 0 END) AS Tagged_No_Farmers,

        SUM(CASE WHEN gd.seed_name = 6 THEN gd.area_planted ELSE 0 END) AS Traditional_Area_Planted,
        SUM(CASE WHEN gd.seed_name = 6 THEN gd.no_farmers ELSE 0 END) AS Traditional_No_Farmers

    FROM GroupedData gd
    JOIN barangay b ON gd.barangay = b.IDbarangay  
    GROUP BY b.BarangayName 
    ORDER BY b.BarangayName;
    ";


    // FSS IRRIGATED - First SQL Query (WITH GroupedData for planting)
    $FSSquery = "
    WITH GroupedData AS (
        SELECT 
            p.landtype, 
            p.season_type, 
            p.seed_system_type, 
            p.project_type, 
            p.seed_name, 
            p.barangay, 
            p.area_planted, 
            p.no_farmers, 
            ROW_NUMBER() OVER (PARTITION BY p.barangay) AS row_num
        FROM planting p
        WHERE 
            p.landtype = 2 
            AND p.project_type IS NULL 
            AND p.seed_system_type = 3 
            AND p.`month` = ? 
            AND p.`year` = ? 
            AND p.`range_date` = ? 
    )
    SELECT 
        b.BarangayName AS FSS_Barangay, 

        -- Check for NULL seed_name and sum accordingly
        SUM(CASE WHEN gd.seed_name IS NULL THEN gd.area_planted ELSE 0 END) AS FSS_Area_Planted,
        SUM(CASE WHEN gd.seed_name IS NULL THEN gd.no_farmers ELSE 0 END) AS FSS_No_Farmers

    FROM GroupedData gd
    JOIN barangay b ON gd.barangay = b.IDbarangay  
    GROUP BY b.BarangayName 
    ORDER BY b.BarangayName;
    ";



     // Prepare and execute the first query for NPR
     $stmt1 = $con->prepare($NPRquery);
     $stmt1->bind_param('iis', $month, $year, $range_date);  
     $stmt1->execute();
     $result1 = $stmt1->get_result();
 
     // Prepare and execute the second query for RCEF
     $stmt2 = $con->prepare($RCEFquery);
     $stmt2->bind_param('iis', $month, $year, $range_date);  
     $stmt2->execute();
     $result2 = $stmt2->get_result();
 
     // Prepare and execute the third query for OWNOTHERS
     $stmt3 = $con->prepare($OWNOTHERSquery);
     $stmt3->bind_param('iis', $month, $year, $range_date);  
     $stmt3->execute();
     $result3 = $stmt3->get_result();
 
     // Prepare and execute the fourth query for INFORMALS
     $stmt4 = $con->prepare($INFORMALSquery);
     $stmt4->bind_param('iis', $month, $year, $range_date);  
     $stmt4->execute();
     $result4 = $stmt4->get_result();
 
     // Prepare and execute the fifth query for FSS
     $stmt5 = $con->prepare($FSSquery);
     $stmt5->bind_param('iis', $month, $year, $range_date);  
     $stmt5->execute();
     $result5 = $stmt5->get_result();
 
     // Second SQL Query (INFO Data)
     $INFOquery = "SELECT * FROM planting WHERE `landtype` = 2 AND `range_date` = ? AND year = ? AND month = ?";
     $stmt6 = $con->prepare($INFOquery);
     $stmt6->bind_param('sii', $range_date, $year, $month);
     $stmt6->execute();
     $result6 = $stmt6->get_result();
 
     // Prepare the array for results
     $data1 = []; // NPR Data
     $data2 = []; // RCEF Data
     $data3 = []; // OWNOTHERS Data
     $data4 = []; // INFORMALS Data
     $data5 = []; // FSS Data
     $info = [];   // INFO Data
 
     // Fetch NPR Results
     if ($result1->num_rows > 0) {
         while ($row1 = $result1->fetch_assoc()) {
             $data1[] = [
                 'FROMAL_NPR_Barangay' => $row1['FROMAL_NPR_Barangay'],
                 'Hybrid_Area_Planted' => $row1['Hybrid_Area_Planted'],
                 'Hybrid_No_Farmers' => $row1['Hybrid_No_Farmers'],
                 'Registered_Area_Planted' => $row1['Registered_Area_Planted'],
                 'Registered_No_Farmers' => $row1['Registered_No_Farmers'],
                 'Certified_Area_Planted' => $row1['Certified_Area_Planted'],
                 'Certified_No_Farmers' => $row1['Certified_No_Farmers']
             ];
         }
     }
 
     // Fetch RCEF Results
     if ($result2->num_rows > 0) {
         while ($row2 = $result2->fetch_assoc()) {
             $data2[] = [
                 'FROMAL_RCEF_Barangay' => $row2['FROMAL_RCEF_Barangay'],
                 'Hybrid_Area_Planted' => $row2['Hybrid_Area_Planted'],
                 'Hybrid_No_Farmers' => $row2['Hybrid_No_Farmers'],
                 'Registered_Area_Planted' => $row2['Registered_Area_Planted'],
                 'Registered_No_Farmers' => $row2['Registered_No_Farmers'],
                 'Certified_Area_Planted' => $row2['Certified_Area_Planted'],
                 'Certified_No_Farmers' => $row2['Certified_No_Farmers']
             ];
         }
     }
 
     // Fetch OWNOTHERS Results
     if ($result3->num_rows > 0) {
         while ($row3 = $result3->fetch_assoc()) {
             $data3[] = [
                 'FROMAL_OWNOTHERS_Barangay' => $row3['FROMAL_OWNOTHERS_Barangay'],
                 'Hybrid_Area_Planted' => $row3['Hybrid_Area_Planted'],
                 'Hybrid_No_Farmers' => $row3['Hybrid_No_Farmers'],
                 'Registered_Area_Planted' => $row3['Registered_Area_Planted'],
                 'Registered_No_Farmers' => $row3['Registered_No_Farmers'],
                 'Certified_Area_Planted' => $row3['Certified_Area_Planted'],
                 'Certified_No_Farmers' => $row3['Certified_No_Farmers']
             ];
         }
     }
 
     // Fetch INFORMALS Results
     if ($result4->num_rows > 0) {
         while ($row4 = $result4->fetch_assoc()) {
             $data4[] = [
                 'INFROMAL_Barangay' => $row4['INFROMAL_Barangay'],
                 'Starter_Area_Planted' => $row4['Starter_Area_Planted'],
                 'Starter_No_Farmers' => $row4['Starter_No_Farmers'],
                 'Tagged_Area_Planted' => $row4['Tagged_Area_Planted'],
                 'Tagged_No_Farmers' => $row4['Tagged_No_Farmers'],
                 'Traditional_Area_Planted' => $row4['Traditional_Area_Planted'],
                 'Traditional_No_Farmers' => $row4['Traditional_No_Farmers']
             ];
         }
     }
 
     // Fetch FSS Results
     if ($result5->num_rows > 0) {
         while ($row5 = $result5->fetch_assoc()) {
             $data5[] = [
                 'FSS_Barangay' => $row5['FSS_Barangay'],
                 'FSS_Area_Planted' => $row5['FSS_Area_Planted'],
                 'FSS_No_Farmers' => $row5['FSS_No_Farmers'],
             ];
         }
     }
 
     // Fetch INFO Data
     if ($result6->num_rows > 0) {
         $row6 = $result6->fetch_assoc();
         $info = [
             'landtype' => $row6['landtype'],
             'seed_system_type' => $row6['seed_system_type'],
             'season_type' => $row6['season_type'],
             'barangay' => $row6['barangay'],
             'range_date' => $row6['range_date'],
             'year' => $row6['year'],
             'month' => $row6['month']
         ];
     } else {
         $info = ['error' => 'No data found'];
     }
 
     // Return all results as JSON
     echo json_encode([
         'NPR' => $data1,
         'RCEF' => $data2,
         'OWNOTHERS' => $data3,
         'INFORMAL' => $data4,
         'FSS' => $data5,
         'INFO' => $info
     ]);
 
     // Close prepared statements
     $stmt1->close();
     $stmt2->close();
     $stmt3->close();
     $stmt4->close();
     $stmt5->close();
     $stmt6->close();
 } else {
     echo json_encode(['error' => 'Invalid parameters provided.']);
 }
 
 // Close the database connection
 $con->close();
 ?>
 