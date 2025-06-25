<?php

include('../connection.php');

if (isset($_POST['year'], $_POST['month'], $_POST['range_date'])) {

    $year = $_POST['year'];
    $month = $_POST['month'];
    $range_date = $_POST['range_date'];

     // NPR FORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
     $NPRquery = "
     WITH GroupedData AS (
        SELECT 
            h.landtype, 
            h.season_type, 
            h.seed_system_type, 
            h.project_type, 
            h.seed_name, 
            h.barangay, 
            h.area_harvested, 
     		h.average_yield,
            h.production, 
            ROW_NUMBER() OVER (PARTITION BY h.barangay ORDER BY h.seed_name) AS row_num
        FROM harvesting h
        WHERE 
            h.landtype = 3 
            AND h.project_type = 1 
            AND h.seed_system_type = 1 
            AND h.`month` =  ?
            AND h.`year` = ?
            AND h.`range_date` = ? 
    )
    SELECT 
        b.BarangayName AS FROMAL_NPR_Barangay, 

        SUM(CASE WHEN gd.seed_name = 1 THEN gd.area_harvested ELSE 0 END) AS HYBRID_Area_Harvested,
        SUM(CASE WHEN gd.seed_name = 1 THEN gd.average_yield ELSE 0 END) AS HYBRID_Average_Yield,
        SUM(CASE WHEN gd.seed_name = 1 THEN gd.production ELSE 0 END) AS HYBRID_Production,

        SUM(CASE WHEN gd.seed_name = 2 THEN gd.area_harvested ELSE 0 END) AS REGISTERED_Area_Harvested,
        SUM(CASE WHEN gd.seed_name = 2 THEN gd.average_yield ELSE 0 END) AS REGISTERED_Average_Yield,
         SUM(CASE WHEN gd.seed_name = 2 THEN gd.production ELSE 0 END) AS REGISTERED_Production,

        SUM(CASE WHEN gd.seed_name = 3 THEN gd.area_harvested ELSE 0 END) AS CERTIFIED_Area_Harvested,
        SUM(CASE WHEN gd.seed_name = 3 THEN gd.average_yield ELSE 0 END) AS CERTIFIED_Average_Yield,
        SUM(CASE WHEN gd.seed_name = 3 THEN gd.production ELSE 0 END) AS CERTIFIED_Production 

    FROM GroupedData gd
    JOIN barangay b ON gd.barangay = b.IDbarangay  
    GROUP BY b.BarangayName 
    ORDER BY b.BarangayName;
    ";


    // RCEF FORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
    $RCEFquery = "
    WITH GroupedData AS (
       SELECT 
           h.landtype, 
           h.season_type, 
           h.seed_system_type, 
           h.project_type, 
           h.seed_name, 
           h.barangay, 
           h.area_harvested, 
            h.average_yield,
           h.production, 
           ROW_NUMBER() OVER (PARTITION BY h.barangay ORDER BY h.seed_name) AS row_num
       FROM harvesting h
       WHERE 
           h.landtype = 3 
           AND h.project_type = 2 
           AND h.seed_system_type = 1 
           AND h.`month` =  ?
           AND h.`year` = ?
           AND h.`range_date` = ? 
   )
   SELECT 
       b.BarangayName AS FROMAL_RCEF_Barangay, 

       SUM(CASE WHEN gd.seed_name = 1 THEN gd.area_harvested ELSE 0 END) AS HYBRID_Area_Harvested,
       SUM(CASE WHEN gd.seed_name = 1 THEN gd.average_yield ELSE 0 END) AS HYBRID_Average_Yield,
       SUM(CASE WHEN gd.seed_name = 1 THEN gd.production ELSE 0 END) AS HYBRID_Production,

       SUM(CASE WHEN gd.seed_name = 2 THEN gd.area_harvested ELSE 0 END) AS REGISTERED_Area_Harvested,
       SUM(CASE WHEN gd.seed_name = 2 THEN gd.average_yield ELSE 0 END) AS REGISTERED_Average_Yield,
        SUM(CASE WHEN gd.seed_name = 2 THEN gd.production ELSE 0 END) AS REGISTERED_Production,

       SUM(CASE WHEN gd.seed_name = 3 THEN gd.area_harvested ELSE 0 END) AS CERTIFIED_Area_Harvested,
       SUM(CASE WHEN gd.seed_name = 3 THEN gd.average_yield ELSE 0 END) AS CERTIFIED_Average_Yield,
       SUM(CASE WHEN gd.seed_name = 3 THEN gd.production ELSE 0 END) AS CERTIFIED_Production 

   FROM GroupedData gd
   JOIN barangay b ON gd.barangay = b.IDbarangay  
   GROUP BY b.BarangayName 
   ORDER BY b.BarangayName;
   ";


   // OWNOTHERS FORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
   $OWNOTHERSquery = "
   WITH GroupedData AS (
      SELECT 
          h.landtype, 
          h.season_type, 
          h.seed_system_type, 
          h.project_type, 
          h.seed_name, 
          h.barangay, 
          h.area_harvested, 
           h.average_yield,
          h.production, 
          ROW_NUMBER() OVER (PARTITION BY h.barangay ORDER BY h.seed_name) AS row_num
      FROM harvesting h
      WHERE 
          h.landtype = 3 
          AND h.project_type = 3 
          AND h.seed_system_type = 1 
          AND h.`month` =  ?
          AND h.`year` = ?
          AND h.`range_date` = ? 
  )
  SELECT 
      b.BarangayName AS FROMAL_OWNOTHERS_Barangay, 

      SUM(CASE WHEN gd.seed_name = 1 THEN gd.area_harvested ELSE 0 END) AS HYBRID_Area_Harvested,
      SUM(CASE WHEN gd.seed_name = 1 THEN gd.average_yield ELSE 0 END) AS HYBRID_Average_Yield,
      SUM(CASE WHEN gd.seed_name = 1 THEN gd.production ELSE 0 END) AS HYBRID_Production,

      SUM(CASE WHEN gd.seed_name = 2 THEN gd.area_harvested ELSE 0 END) AS REGISTERED_Area_Harvested,
      SUM(CASE WHEN gd.seed_name = 2 THEN gd.average_yield ELSE 0 END) AS REGISTERED_Average_Yield,
       SUM(CASE WHEN gd.seed_name = 2 THEN gd.production ELSE 0 END) AS REGISTERED_Production,

      SUM(CASE WHEN gd.seed_name = 3 THEN gd.area_harvested ELSE 0 END) AS CERTIFIED_Area_Harvested,
      SUM(CASE WHEN gd.seed_name = 3 THEN gd.average_yield ELSE 0 END) AS CERTIFIED_Average_Yield,
      SUM(CASE WHEN gd.seed_name = 3 THEN gd.production ELSE 0 END) AS CERTIFIED_Production 

  FROM GroupedData gd
  JOIN barangay b ON gd.barangay = b.IDbarangay  
  GROUP BY b.BarangayName 
  ORDER BY b.BarangayName;
  ";


  // INFORMAL IRRIGATED - First SQL Query (WITH GroupedData for planting)
  $INFORMALquery = "
  WITH GroupedData AS (
     SELECT 
         h.landtype, 
         h.season_type, 
         h.seed_system_type, 
         h.project_type, 
         h.seed_name, 
         h.barangay, 
         h.area_harvested, 
          h.average_yield,
         h.production, 
         ROW_NUMBER() OVER (PARTITION BY h.barangay ORDER BY h.seed_name) AS row_num
     FROM harvesting h
     WHERE 
         h.landtype = 3
         AND h.project_type IS NULL
         AND h.seed_system_type = 2 
         AND h.`month` =  ?
         AND h.`year` = ?
         AND h.`range_date` = ? 
 )
 SELECT 
     b.BarangayName AS INFROMAL_Barangay, 

     SUM(CASE WHEN gd.seed_name = 4 THEN gd.area_harvested ELSE 0 END) AS STARTER_Area_Harvested,
     SUM(CASE WHEN gd.seed_name = 4 THEN gd.average_yield ELSE 0 END) AS STARTER_Average_Yield,
     SUM(CASE WHEN gd.seed_name = 4 THEN gd.production ELSE 0 END) AS STARTER_Production,

     SUM(CASE WHEN gd.seed_name = 5 THEN gd.area_harvested ELSE 0 END) AS TAGGED_Area_Harvested,
     SUM(CASE WHEN gd.seed_name = 5 THEN gd.average_yield ELSE 0 END) AS TAGGED_Average_Yield,
      SUM(CASE WHEN gd.seed_name = 5 THEN gd.production ELSE 0 END) AS TAGGED_Production,

     SUM(CASE WHEN gd.seed_name = 6 THEN gd.area_harvested ELSE 0 END) AS TRADITIONAL_Area_Harvested,
     SUM(CASE WHEN gd.seed_name = 6 THEN gd.average_yield ELSE 0 END) AS TRADITIONAL_Average_Yield,
     SUM(CASE WHEN gd.seed_name = 6 THEN gd.production ELSE 0 END) AS TRADITIONAL_Production 

 FROM GroupedData gd
 JOIN barangay b ON gd.barangay = b.IDbarangay  
 GROUP BY b.BarangayName 
 ORDER BY b.BarangayName;
 ";


 // FSS IRRIGATED - First SQL Query (WITH GroupedData for planting)
 $FSSquery = "
 WITH GroupedData AS (
    SELECT 
        h.landtype, 
        h.season_type, 
        h.seed_system_type, 
        h.project_type, 
        h.seed_name, 
        h.barangay, 
        h.area_harvested, 
         h.average_yield,
        h.production, 
        ROW_NUMBER() OVER (PARTITION BY h.barangay ORDER BY h.seed_name) AS row_num
    FROM harvesting h
    WHERE 
        h.landtype = 3 
        AND h.project_type IS NULL
        AND h.seed_system_type = 3 
        AND h.`month` =  ?
        AND h.`year` = ?
        AND h.`range_date` = ? 
)
SELECT 
    b.BarangayName AS FSS_Barangay, 

    SUM(CASE WHEN gd.seed_name IS NULL THEN gd.area_harvested ELSE 0 END) AS FSS_Area_Harvested,
    SUM(CASE WHEN gd.seed_name IS NULL THEN gd.average_yield ELSE 0 END) AS FSS_Average_Yield,
    SUM(CASE WHEN gd.seed_name IS NULL THEN gd.production ELSE 0 END) AS FSS_Production

FROM GroupedData gd
JOIN barangay b ON gd.barangay = b.IDbarangay  
GROUP BY b.BarangayName 
ORDER BY b.BarangayName;
";



    //NPR
    $stmt1 = $con->prepare($NPRquery);
    $stmt1->bind_param('iis', $month, $year, $range_date);  
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    //RCEF
    $stmt2 = $con->prepare($RCEFquery);
    $stmt2->bind_param('iis', $month, $year, $range_date);  
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    //OWNOTHERS
    $stmt3 = $con->prepare($OWNOTHERSquery);
    $stmt3->bind_param('iis', $month, $year, $range_date);  
    $stmt3->execute();
    $result3 = $stmt3->get_result();

    //INFORMAL
    $stmt4 = $con->prepare($INFORMALquery);
    $stmt4->bind_param('iis', $month, $year, $range_date);  
    $stmt4->execute();
    $result4 = $stmt4->get_result();

    //FSS
    $stmt5 = $con->prepare($FSSquery);
    $stmt5->bind_param('iis', $month, $year, $range_date);  
    $stmt5->execute();
    $result5 = $stmt5->get_result();

    //INFO
    $INFOquery = "SELECT * FROM harvesting WHERE `landtype` = 3 AND `range_date` = ? AND year = ? AND month = ?";
    $stmt6 = $con->prepare($INFOquery);
    $stmt6->bind_param('sii', $range_date, $year, $month);
    $stmt6->execute();
    $result6 = $stmt6->get_result();


    // Prepare the array for results
    $data1 = []; // NPR Data
    $data2 = []; // RCEF Data
    $data3 = []; // OWNOTHERS Data
    $data4 = []; // INFORMAL Data
    $data5 = []; // FSS Data
    $info = []; // INFO Data

    // Fetch NPR Results
    if ($result1->num_rows > 0) {
        while ($row1 = $result1->fetch_assoc()) {
            $data1[] = [
                'FROMAL_NPR_Barangay' => $row1['FROMAL_NPR_Barangay'],
                'Hybrid_Area_Harvested' => $row1['HYBRID_Area_Harvested'],
                'Hybrid_Average_Yield' => $row1['HYBRID_Average_Yield'],
                'Hybrid_Production' => $row1['HYBRID_Production'],
                'Registered_Area_Harvested' => $row1['REGISTERED_Area_Harvested'],
                'Registered_Average_Yield' => $row1['REGISTERED_Average_Yield'],
                'Registered_Production' => $row1['REGISTERED_Production'],
                'Certified_Area_Harvested' => $row1['CERTIFIED_Area_Harvested'],
                'Certified_Average_Yield' => $row1['CERTIFIED_Average_Yield'],
                'Certified_Production' => $row1['CERTIFIED_Production'],
            ];
        }
    }

    // Fetch RCEF Results
    if ($result2->num_rows > 0) {
        while ($row2 = $result2->fetch_assoc()) {
            $data2[] = [
                'FROMAL_RCEF_Barangay' => $row2['FROMAL_RCEF_Barangay'],
                'Hybrid_Area_Harvested' => $row2['HYBRID_Area_Harvested'],
                'Hybrid_Average_Yield' => $row2['HYBRID_Average_Yield'],
                'Hybrid_Production' => $row2['HYBRID_Production'],
                'Registered_Area_Harvested' => $row2['REGISTERED_Area_Harvested'],
                'Registered_Average_Yield' => $row2['REGISTERED_Average_Yield'],
                'Registered_Production' => $row2['REGISTERED_Production'],
                'Certified_Area_Harvested' => $row2['CERTIFIED_Area_Harvested'],
                'Certified_Average_Yield' => $row2['CERTIFIED_Average_Yield'],
                'Certified_Production' => $row2['CERTIFIED_Production'],
            ];
        }
    }

    // Fetch OWNOTHERS Results
    if ($result3->num_rows > 0) {
        while ($row3 = $result3->fetch_assoc()) {
            $data3[] = [
                'FROMAL_OWNOTHERS_Barangay' => $row3['FROMAL_OWNOTHERS_Barangay'],
                'Hybrid_Area_Harvested' => $row3['HYBRID_Area_Harvested'],
                'Hybrid_Average_Yield' => $row3['HYBRID_Average_Yield'],
                'Hybrid_Production' => $row3['HYBRID_Production'],
                'Registered_Area_Harvested' => $row3['REGISTERED_Area_Harvested'],
                'Registered_Average_Yield' => $row3['REGISTERED_Average_Yield'],
                'Registered_Production' => $row3['REGISTERED_Production'],
                'Certified_Area_Harvested' => $row3['CERTIFIED_Area_Harvested'],
                'Certified_Average_Yield' => $row3['CERTIFIED_Average_Yield'],
                'Certified_Production' => $row3['CERTIFIED_Production'],
            ];
        }
    }

    // Fetch INFORMAL Results
    if ($result4->num_rows > 0) {
        while ($row4 = $result4->fetch_assoc()) {
            $data4[] = [
                'INFROMAL_Barangay' => $row4['INFROMAL_Barangay'],
                'Starter_Area_Harvested' => $row4['STARTER_Area_Harvested'],
                'Starter_Average_Yield' => $row4['STARTER_Average_Yield'],
                'Starter_Production' => $row4['STARTER_Production'],
                'Tagged_Area_Harvested' => $row4['TAGGED_Area_Harvested'],
                'Tagged_Average_Yield' => $row4['TAGGED_Average_Yield'],
                'Tagged_Production' => $row4['TAGGED_Production'],
                'Traditional_Area_Harvested' => $row4['TRADITIONAL_Area_Harvested'],
                'Traditional_Average_Yield' => $row4['TRADITIONAL_Average_Yield'],
                'Traditional_Production' => $row4['TRADITIONAL_Production'],
            ];
        }
    }

    // Fetch FSS Results
    if ($result5->num_rows > 0) {
        while ($row5 = $result5->fetch_assoc()) {
            $data5[] = [
                'FSS_Barangay' => $row5['FSS_Barangay'],
                'FSS_Area_Harvested' => $row5['FSS_Area_Harvested'],
                'FSS_Average_Yield' => $row5['FSS_Average_Yield'],
                'FSS_Production' => $row5['FSS_Production'],
            ];
        }
    }

    // Fetch INFO Results
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
        'INFO' => $info,
        'NPR' => $data1,
        'RCEF' => $data2,
        'OWNOTHERS' => $data3,
        'INFORMAL' => $data4,
        'FSS' => $data5
    ]);

    // Close prepared statements
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $stmt4->close();
    $stmt5->close();
    $stmt6->close();
}else {
    echo json_encode(['error' => 'Invalid parameters provided.']);
}
$con->close();

?>