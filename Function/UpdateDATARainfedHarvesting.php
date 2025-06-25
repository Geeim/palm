<?php

include '../connection.php';

$response = []; 

function calculate_average_yield($area_harvested, $production) {
    // If area_harvested is zero (to avoid division by zero), return '0.00'
    if ($area_harvested == 0 || $production == 0) {
        return '0.00'; // Or 'N/A', depending on how you want to handle it
    }

    // Otherwise, calculate the yield
    return number_format(round($production / $area_harvested, 2), 2, '.', '');
}

if (isset($_POST['updatedDataNPRHarvesting'])) {
    $updatedDataNPRHarvesting = json_decode($_POST['updatedDataNPRHarvesting'], true);

    if ($updatedDataNPRHarvesting === null) {
        $response[] = "Error: Failed to decode NPR data.";
    }else {
        foreach ($updatedDataNPRHarvesting as $row) {
            $barangay = $row['FROMAL_NPR_Barangay'];  
 
            $hybrid_area_harvested = max(0, (float)$row['Hybrid_Area_Harvested']);
            $hybrid_production = max(0, (float)$row['Hybrid_Production']);
            $hybrid_average_yield = calculate_average_yield($hybrid_area_harvested, $hybrid_production);

            $registered_area_harvested = max(0, (float)$row['Registered_Area_Harvested']);
            $registered_production = max(0, (float)$row['Registered_Production']);
            $registered_average_yield = calculate_average_yield($registered_area_harvested, $registered_production);

            $certified_area_harvested = max(0, (float)$row['Certified_Area_Harvested']);
            $certified_production = max(0, (float)$row['Certified_Production']);
            $certified_average_yield = calculate_average_yield($certified_area_harvested, $certified_production);
            
            $year = $row['year'];  
            $month = $row['month']; 
            $range_date = $row['range_date']; 

                // Update for Hybrid Seed
                $query_hybrid = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 1 AND`project_type` = 1 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Registered Seed
                $query_registered = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 2 AND`project_type` = 1 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Certified Seed
                $query_certified = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 3 AND`project_type` = 1 AND h.year = ? AND h.month = ? AND h.range_date = ?;";

            $stmt_hybrid = $con->prepare($query_hybrid);
            $stmt_registered = $con->prepare($query_registered);
            $stmt_certified = $con->prepare($query_certified);

            // Bind parameters
            $stmt_hybrid->bind_param('sssssss',$hybrid_area_harvested ,$hybrid_average_yield ,$hybrid_production ,$barangay ,$year ,$month ,$range_date);
            $stmt_registered->bind_param('sssssss',$registered_area_harvested ,$registered_average_yield ,$registered_production ,$barangay ,$year ,$month ,$range_date);
            $stmt_certified->bind_param('sssssss',$certified_area_harvested ,$certified_average_yield ,$certified_production ,$barangay ,$year ,$month ,$range_date);

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




if (isset($_POST['updatedDataRCEFHarvesting'])) {
    $updatedDataRCEFHarvesting = json_decode($_POST['updatedDataRCEFHarvesting'], true);

    if ($updatedDataRCEFHarvesting === null) {
        $response[] = "Error: Failed to decode RCEF data.";
    }else {
        foreach ($updatedDataRCEFHarvesting as $row) {
            $barangay = $row['FROMAL_RCEF_Barangay'];  
 
            $hybrid_area_harvested = max(0, (float)$row['Hybrid_Area_Harvested']);
            $hybrid_production = max(0, (float)$row['Hybrid_Production']);
            $hybrid_average_yield = calculate_average_yield($hybrid_area_harvested, $hybrid_production);

            $registered_area_harvested = max(0, (float)$row['Registered_Area_Harvested']);
            $registered_production = max(0, (float)$row['Registered_Production']);
            $registered_average_yield = calculate_average_yield($registered_area_harvested, $registered_production);

            $certified_area_harvested = max(0, (float)$row['Certified_Area_Harvested']);
            $certified_production = max(0, (float)$row['Certified_Production']);
            $certified_average_yield = calculate_average_yield($certified_area_harvested, $certified_production);

            $year = $row['year'];  
            $month = $row['month']; 
            $range_date = $row['range_date']; 

                // Update for Hybrid Seed
                $query_hybrid = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 1 AND`project_type` = 2 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Registered Seed
                $query_registered = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 2 AND`project_type` = 2 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Certified Seed
                $query_certified = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 3 AND`project_type` = 2 AND h.year = ? AND h.month = ? AND h.range_date = ?;";

            $stmt_hybrid = $con->prepare($query_hybrid);
            $stmt_registered = $con->prepare($query_registered);
            $stmt_certified = $con->prepare($query_certified);

            // Bind parameters
            $stmt_hybrid->bind_param('sssssss',$hybrid_area_harvested ,$hybrid_average_yield ,$hybrid_production ,$barangay ,$year ,$month ,$range_date );
            $stmt_registered->bind_param('sssssss',$registered_area_harvested ,$registered_average_yield ,$registered_production ,$barangay ,$year ,$month ,$range_date);
            $stmt_certified->bind_param('sssssss',$certified_area_harvested ,$certified_average_yield ,$certified_production ,$barangay ,$year ,$month ,$range_date );

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

            // Close statements
            $stmt_hybrid->close();
            $stmt_registered->close();
            $stmt_certified->close();
        }
    }
}



if (isset($_POST['updatedDataOWNOTHERSHarvesting'])) {
    $updatedDataOWNOTHERSHarvesting = json_decode($_POST['updatedDataOWNOTHERSHarvesting'], true);

    if ($updatedDataOWNOTHERSHarvesting === null) {
        $response[] = "Error: Failed to decode OWNOTHERS data.";
    }else {
        foreach ($updatedDataOWNOTHERSHarvesting as $row) {
            $barangay = $row['FROMAL_OWNOTHERS_Barangay'];  
            
            $hybrid_area_harvested = max(0, (float)$row['Hybrid_Area_Harvested']);
            $hybrid_production = max(0, (float)$row['Hybrid_Production']);
            $hybrid_average_yield = calculate_average_yield($hybrid_area_harvested, $hybrid_production);

            $registered_area_harvested = max(0, (float)$row['Registered_Area_Harvested']);
            $registered_production = max(0, (float)$row['Registered_Production']);
            $registered_average_yield = calculate_average_yield($registered_area_harvested, $registered_production);

            $certified_area_harvested = max(0, (float)$row['Certified_Area_Harvested']);
            $certified_production = max(0, (float)$row['Certified_Production']);
            $certified_average_yield = calculate_average_yield($certified_area_harvested, $certified_production);
            
            $year = $row['year'];  
            $month = $row['month']; 
            $range_date = $row['range_date']; 

                // Update for Hybrid Seed
                $query_hybrid = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 1 AND`project_type` = 3 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Registered Seed
                $query_registered = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 2 AND`project_type` = 3 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Certified Seed
                $query_certified = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 3 AND`project_type` = 3 AND h.year = ? AND h.month = ? AND h.range_date = ?;";
    
            $stmt_hybrid = $con->prepare($query_hybrid);
            $stmt_registered = $con->prepare($query_registered);
            $stmt_certified = $con->prepare($query_certified);

            // Bind parameters
            $stmt_hybrid->bind_param('sssssss',$hybrid_area_harvested ,$hybrid_average_yield ,$hybrid_production ,$barangay ,$year ,$month ,$range_date );
            $stmt_registered->bind_param('sssssss',$registered_area_harvested ,$registered_average_yield ,$registered_production ,$barangay ,$year ,$month ,$range_date);
            $stmt_certified->bind_param('sssssss',$certified_area_harvested ,$certified_average_yield ,$certified_production ,$barangay ,$year ,$month ,$range_date );

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

            // Close statements
            $stmt_hybrid->close();
            $stmt_registered->close();
            $stmt_certified->close();

        }
    }
}



if (isset($_POST['updatedDataINFORMALHarvesting'])) {
    $updatedDataINFORMALHarvesting = json_decode($_POST['updatedDataINFORMALHarvesting'], true);

    if ($updatedDataINFORMALHarvesting === null) {
        $response[] = "Error: Failed to decode INFORMAL data.";
    }else {
        foreach ($updatedDataINFORMALHarvesting as $row) {
            $barangay = $row['INFROMAL_Barangay'];  
            
            $starter_area_harvested = max(0, (float)$row['Starter_Area_Harvested']);
            $starter_production = max(0, (float)$row['Starter_Production']); 
            $starter_average_yield = calculate_average_yield($starter_area_harvested, $starter_production); 
            
            $tagged_area_harvested = max(0, (float)$row['Tagged_Area_Harvested']);  
            $tagged_production = max(0, (float)$row['Tagged_Production']);
            $tagged_average_yield = calculate_average_yield($tagged_area_harvested, $tagged_production);   

            $traditional_area_harvested = max(0, (float)$row['Traditional_Area_Harvested']);   
            $traditional_production = max(0, (float)$row['Traditional_Production']); 
            $traditional_average_yield = calculate_average_yield($traditional_area_harvested, $traditional_production);   

            $year = $row['year'];  
            $month = $row['month']; 
            $range_date = $row['range_date']; 

                // Update for Starter Seed
                $query_starter = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 4 AND`project_type` IS NULL AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for TAGGED Seed
                $query_tagged = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 5 AND`project_type` IS NULL AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
                // Update for Traditional Seed
                $query_traditional = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name = 6 AND`project_type` IS NULL AND h.year = ? AND h.month = ? AND h.range_date = ?;";
    
            $stmt_starter = $con->prepare($query_starter);
            $stmt_tagged = $con->prepare($query_tagged);
            $stmt_traditional = $con->prepare($query_traditional);

            // Bind parameters
            $stmt_starter->bind_param('sssssss',$starter_area_harvested ,$starter_average_yield,$starter_production ,$barangay ,$year ,$month ,$range_date );
            $stmt_tagged->bind_param('sssssss',$tagged_area_harvested ,$tagged_average_yield ,$tagged_production ,$barangay ,$year ,$month ,$range_date);
            $stmt_traditional->bind_param('sssssss',$traditional_area_harvested ,$traditional_average_yield ,$traditional_production ,$barangay ,$year ,$month ,$range_date );

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

            // Close statements
            $stmt_starter->close();
            $stmt_tagged->close();
            $stmt_traditional->close();
        }
    }
}



if (isset($_POST['updatedDataFSSHarvesting'])) {
    $updatedDataFSSHarvesting = json_decode($_POST['updatedDataFSSHarvesting'], true);

    if ($updatedDataFSSHarvesting === null) {
        $response[] = "Error: Failed to decode FSS data.";
    }else {
        foreach ($updatedDataFSSHarvesting as $row) {
            $barangay = $row['FSS_Barangay'];  

            $fss_area_harvested = max(0, (float)$row['FSS_Area_Harvested']);
            $fss_production = max(0, (float)$row['FSS_Production']);
            $fss_average_yield = calculate_average_yield($fss_area_harvested, $fss_production);   
            
            $year = $row['year'];  
            $month = $row['month']; 
            $range_date = $row['range_date']; 

                // Update for FSS
                $query_fss = "UPDATE harvesting h JOIN barangay b ON h.barangay = b.IDbarangay 
                SET h.area_harvested = ?,  h.average_yield = ?, h.production = ? WHERE 
                b.BarangayName = ? AND landtype = 2 AND h.seed_name IS NULL AND`project_type` IS NULL AND h.year = ? AND h.month = ? AND h.range_date = ?;";
                
            $stmt_fss = $con->prepare($query_fss);
            $stmt_fss->bind_param('sssssss',$fss_area_harvested ,$fss_average_yield ,$fss_production ,$barangay ,$year ,$month ,$range_date );
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