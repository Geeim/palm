<?php

include '../connection.php';

$response = [];  // Array to store response messages

    if (isset($_POST['updatedDataNPR'])) {
        $updatedDataNPR = json_decode($_POST['updatedDataNPR'], true);

        if ($updatedDataNPR === null) {
            $response[] = "Error: Failed to decode NPR data.";
        } else {
            foreach ($updatedDataNPR as $row) {
                $barangay = $row['FROMAL_NPR_Barangay'];  
                $hybrid_area_planted = max(0, (float)$row['Hybrid_Area_Planted']);
                $hybrid_no_farmers = max(0, (int)$row['Hybrid_No_Farmers']);  
                $registered_area_planted = max(0, (float)$row['Registered_Area_Planted']);  
                $registered_no_farmers = max(0, (int)$row['Registered_No_Farmers']);  
                $certified_area_planted = max(0, (float)$row['Certified_Area_Planted']); 
                $certified_no_farmers = max(0, (int)$row['Certified_No_Farmers']);  
                $year = $row['year'];  
                $month = $row['month']; 
                $range_date = $row['range_date']; 

                // Update for Hybrid Seed
                $query_hybrid = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 1 AND`project_type` = 1 AND p.year = ? AND p.month = ? AND p.range_date = ?;";

                // Update for Registered Seed
                $query_registered = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 2 AND`project_type` = 1 AND p.year = ? AND p.month = ? AND p.range_date = ?;";

                // Update for Certified Seed
                $query_certified = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 3 AND`project_type` = 1 AND p.year = ? AND p.month = ? AND p.range_date = ?;";

                // Prepare the statements
                $stmt_hybrid = $con->prepare($query_hybrid);
                $stmt_registered = $con->prepare($query_registered);
                $stmt_certified = $con->prepare($query_certified);

                // Bind parameters
                $stmt_hybrid->bind_param('ssssss', $hybrid_area_planted, $hybrid_no_farmers, $barangay, $year, $month, $range_date);
                $stmt_registered->bind_param('ssssss', $registered_area_planted, $registered_no_farmers, $barangay, $year, $month, $range_date);
                $stmt_certified->bind_param('ssssss', $certified_area_planted, $certified_no_farmers, $barangay, $year, $month, $range_date);

                // Execute the statements
                $stmt_hybrid->execute();
                $stmt_registered->execute();
                $stmt_certified->execute();

                // Check if any rows were affected
                if ($stmt_hybrid->affected_rows > 0 || $stmt_registered->affected_rows > 0 || $stmt_certified->affected_rows > 0) {
                    $response[] = "NPR Successfully updated data for Barangay: $barangay, Year: $year, Month: $month.";
                } else {
                    $response[] = "NPR Failed to update data for Barangay: $barangay, Year: $year, Month: $month. Error: " . $con->error;
                }                

                // Close statements
                $stmt_hybrid->close();
                $stmt_registered->close();
                $stmt_certified->close();
            }
        }
    }


    if (isset($_POST['updatedDataRCEF'])) {
        $updatedDataRCEF = json_decode($_POST['updatedDataRCEF'], true);
    
        if ($updatedDataRCEF === null) {
            $response[] = "Error: Failed to decode RCEF data.";
        } else {
            foreach ($updatedDataRCEF as $row) {  // This line was missing a closing brace.
                $barangay = $row['FROMAL_RCEF_Barangay']; 
                $hybrid_area_planted = max(0,(float)$row['Hybrid_Area_Planted']);
                $hybrid_no_farmers = max(0,(int)$row['Hybrid_No_Farmers']); 
                $registered_area_planted = max(0,(float)$row['Registered_Area_Planted']);
                $registered_no_farmers = max(0,(int)$row['Registered_No_Farmers']); 
                $certified_area_planted = max(0,(float)$row['Certified_Area_Planted']); 
                $certified_no_farmers = max(0,(int)$row['Certified_No_Farmers']); 
                $year = $row['year']; 
                $month = $row['month']; 
                $range_date = $row['range_date']; 
    
                // Update for Hybrid Seed
                $query_hybrid = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 1 AND`project_type` = 2 AND p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                // Update for Registered Seed
                $query_registered = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 2 AND`project_type` = 2 AND p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                // Update for Certified Seed
                $query_certified = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 3 AND`project_type` = 2 AND p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                // Prepare the statements
                $stmt_hybrid = $con->prepare($query_hybrid);
                $stmt_registered = $con->prepare($query_registered);
                $stmt_certified = $con->prepare($query_certified);
    
                // Bind parameters
                $stmt_hybrid->bind_param('ssssss', $hybrid_area_planted, $hybrid_no_farmers, $barangay, $year, $month, $range_date);
                $stmt_registered->bind_param('ssssss', $registered_area_planted, $registered_no_farmers, $barangay, $year, $month, $range_date);
                $stmt_certified->bind_param('ssssss', $certified_area_planted, $certified_no_farmers, $barangay, $year, $month, $range_date);
    
                // Execute the statements
                $stmt_hybrid->execute();
                $stmt_registered->execute();
                $stmt_certified->execute();

    
                // Check if any rows were affected
                if ($stmt_hybrid->affected_rows > 0 || $stmt_registered->affected_rows > 0 || $stmt_certified->affected_rows > 0) {
                    $response[] = "RCEF Successfully updated data for Barangay: $barangay, Year: $year, Month: $month.";
                } else {
                    $response[] = "RCEF Failed to update data for Barangay: $barangay, Year: $year, Month: $month. Error: " . $con->error;
                }                 
    
                $stmt_hybrid->close();
                $stmt_registered->close();
                $stmt_certified->close();
            }  
        }
    }
    

    if (isset($_POST['updatedDataOWNOTHERS'])) {
        $updatedDataOWNOTHERS = json_decode($_POST['updatedDataOWNOTHERS'], true);
        
        if ($updatedDataOWNOTHERS === null) {
            $response[] = "Error: Failed to decode OWNOTHERS data.";
        } else {
            foreach ($updatedDataOWNOTHERS as $row) {
                $barangay = $row['FROMAL_OWNOTHERS_Barangay']; 
                $hybrid_area_planted = max(0,(float)$row['Hybrid_Area_Planted']);
                $hybrid_no_farmers = max(0,(int)$row['Hybrid_No_Farmers']); 
                $registered_area_planted = max(0,(float)$row['Registered_Area_Planted']);
                $registered_no_farmers = max(0,(int)$row['Registered_No_Farmers']); 
                $certified_area_planted = max(0,(float)$row['Certified_Area_Planted']); 
                $certified_no_farmers = max(0,(int)$row['Certified_No_Farmers']); 
                $year = $row['year']; 
                $month = $row['month']; 
                $range_date = $row['range_date']; 

                 // Update for Hybrid Seed
                 $query_hybrid = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                 SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                 b.BarangayName = ? AND landtype = 2 AND p.seed_name = 1 AND`project_type` = 3 AND p.year = ? AND p.month = ? AND p.range_date = ?;";
     
                 // Update for Registered Seed
                 $query_registered = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                 SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                 b.BarangayName = ? AND landtype = 2 AND p.seed_name = 2 AND`project_type` = 3 AND p.year = ? AND p.month = ? AND p.range_date = ?;";
     
                 // Update for Certified Seed
                 $query_certified = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                 SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                 b.BarangayName = ? AND landtype = 2 AND p.seed_name = 3 AND`project_type` = 3 AND p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                 // Prepare the statements
                 $stmt_hybrid = $con->prepare($query_hybrid);
                 $stmt_registered = $con->prepare($query_registered);
                 $stmt_certified = $con->prepare($query_certified);
     
                 // Bind parameters
                 $stmt_hybrid->bind_param('ssssss', $hybrid_area_planted, $hybrid_no_farmers, $barangay, $year, $month, $range_date);
                 $stmt_registered->bind_param('ssssss', $registered_area_planted, $registered_no_farmers, $barangay, $year, $month, $range_date);
                 $stmt_certified->bind_param('ssssss', $certified_area_planted, $certified_no_farmers, $barangay, $year, $month, $range_date);
     
                 // Execute the statements
                 $stmt_hybrid->execute();
                 $stmt_registered->execute();
                 $stmt_certified->execute();
     
                 // Check if any rows were affected
                 if ($stmt_hybrid->affected_rows > 0 || $stmt_registered->affected_rows > 0 || $stmt_certified->affected_rows > 0) {
                     $response[] = "OWNOTHERS Successfully updated data for Barangay: $barangay, Year: $year, Month: $month.";
                 } else {
                     $response[] = "OWNOTHERS Failed to update data for Barangay: $barangay, Year: $year, Month: $month. Error: " . $con->error;
                 }                 
     
                 $stmt_hybrid->close();
                 $stmt_registered->close();
                 $stmt_certified->close();
            }
        }
    }

    if (isset($_POST['updatedDataINFORMAL'])) {
        $updatedDataINFORMAL = json_decode($_POST['updatedDataINFORMAL'], true);
        
        
        if ($updatedDataINFORMAL === null) {
            $response[] = "Error: Failed to decode INFORMAL data.";
        } else {
            foreach ($updatedDataINFORMAL as $row) {
                $barangay = $row['INFROMAL_Barangay']; 
                $starter_area_planted = max(0,(float)$row['Starter_Area_Planted']);
                $starter_no_farmers = max(0,(int)$row['Starter_No_Farmers']); 
                $tagged_area_planted = max(0,(float)$row['Tagged_Area_Planted']);
                $tagged_no_farmers = max(0,(int)$row['Tagged_No_Farmers']); 
                $traditional_area_planted = max(0,(float)$row['Traditional_Area_Planted']); 
                $traditional_no_farmers = max(0,(int)$row['Traditional_No_Farmers']); 
                $year = $row['year']; 
                $month = $row['month']; 
                $range_date = $row['range_date']; 

                // Update for STARTER Seed
                $query_starter = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 4 AND `project_type` is NULL AND p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                // Update for TAGGED Seed
                $query_tagged = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2  AND p.seed_name = 5 AND `project_type` is NULL AND p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                // Update for TRADITONAL Seed
                $query_traditional = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
                SET p.area_planted = ?,  p.no_farmers = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND p.seed_name = 6 AND `project_type` is NULL AND  p.year = ? AND p.month = ? AND p.range_date = ?;";
    
                // Prepare the statements
                $stmt_starter = $con->prepare($query_starter);
                $stmt_tagged = $con->prepare($query_tagged);
                $stmt_traditional = $con->prepare($query_traditional);

                // Bind parameters
                $stmt_starter->bind_param('ssssss', $starter_area_planted, $starter_no_farmers, $barangay, $year, $month, $range_date);
                $stmt_tagged->bind_param('ssssss', $tagged_area_planted, $tagged_no_farmers, $barangay, $year, $month, $range_date);
                $stmt_traditional->bind_param('ssssss', $traditional_area_planted, $traditional_no_farmers, $barangay, $year, $month, $range_date);
    
                // Execute the statements
                $stmt_starter->execute();
                $stmt_tagged->execute();
                $stmt_traditional->execute();

                

                // Check if any rows were affected
                if ($stmt_starter->affected_rows > 0 || $stmt_tagged->affected_rows > 0 || $stmt_traditional->affected_rows > 0) {
                    $response[] = "INFORMAL Successfully updated data for Barangay: $barangay, Year: $year, Month: $month.";
                } else {
                    $response[] = "INFORMAL Failed to update data for Barangay: $barangay, Year: $year, Month: $month. Error: " . $con->error;
                }                 
    
                $stmt_starter->close();
                $stmt_tagged->close();
                $stmt_traditional->close();

            }
        }
    }

    if (isset($_POST['updatedDataFSS'])) {
        $updatedDataFSS = json_decode($_POST['updatedDataFSS'], true);
        
        if ($updatedDataFSS === null) {
            $response[] = "Error: Failed to decode FSS data.";
        } else {
            foreach ($updatedDataFSS as $row) {
            $barangay = $row['FSS_Barangay']; 
            $fss_area_planted = max(0,(float)$row['FSS_Area_Planted']);
            $fss_no_farmers = max(0,(int)$row['FSS_No_Farmers']); 
            $year = $row['year']; 
            $month = $row['month']; 
            $range_date = $row['range_date']; 

            // Update for FSS 
            $query_fss = " UPDATE planting p JOIN barangay b ON p.barangay = b.IDbarangay 
            SET p.area_planted = ?,  p.no_farmers = ? WHERE 
            b.BarangayName = ? AND landtype = 2 AND p.seed_name IS NULL AND `project_type` is NULL AND p.year = ? AND p.month = ? AND p.range_date = ?;";
            
            $stmt_fss = $con->prepare($query_fss);
            $stmt_fss->bind_param('ssssss', $fss_area_planted, $fss_no_farmers, $barangay, $year, $month, $range_date);
            $stmt_fss->execute();

            if ($stmt_fss->affected_rows > 0 ){
                $response[] = "FSS Successfully updated data for Barangay: $barangay, Year: $year, Month: $month.";
            } else {
                $response[] = "FSS Failed to update data for Barangay: $barangay, Year: $year, Month: $month. Error: " . $con->error;
            }       
            

            }
        }
    }


echo json_encode(['status' => 'success', 'message' => $response]);
$con->close();

?>