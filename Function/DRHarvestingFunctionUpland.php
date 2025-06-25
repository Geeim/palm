<?php

    include '../connection.php'; 

    session_start();

    $IDemployee = $_SESSION['IDemployee'];

    $PreparedBySQL = "SELECT `Efname`, `Elname`, `Emname` FROM `employee` WHERE `IDemployee` = $IDemployee";
    $PreparedResult = mysqli_query($con, $PreparedBySQL);
    $EmployeeData = mysqli_fetch_assoc($PreparedResult);

    $PreparedBy = trim($EmployeeData['Efname'] . ' ' . ($EmployeeData['Emname'] ?? '') . '. ' . $EmployeeData['Elname']);
    
    
    //INSERT HARESTING DATA

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //DEFAULT VALUE FORAML AND INFORMAL & MODAL SETUP VALUE - SUNOD SUNOD
        $landtype = 3; //UPLAND
        $selectedseason = $_POST['selectedseason'] ?? null;
        $monthpick = $_POST['monthpick'] ?? null;
        $rangeDate = $_POST['rangeDate'] ?? null;
        $yearInsert = $_POST['yearInsert'] ?? null;

        //SEED NAME
        $Hybrid = 1;
        $Registered = 2;
        $Certified = 3;
        $Starter = 4;
        $Tagged = 5; 
        $Traditional = 6;

        $Formal = 1;
        $Informal = 2;
        $FSS = 3;
        
        //NPR FORMAL - INSERT DATA ? Check if may laman yung Barangay sa NPR
        if (isset($_POST['FORMAL_NPR_barangayName']) && is_array($_POST['FORMAL_NPR_barangayName']) && !empty($_POST['FORMAL_NPR_barangayName'])) {
            foreach ($_POST['FORMAL_NPR_barangayName'] as $index => $FORMAL_NPR_barangayName) {
                $FORMAL_AH_NPR_H = $_POST['FORMAL_AH_NPR_H'][$index] ?? '0';
                $FORMAL_AY_NPR_H = $_POST['FORMAL_AY_NPR_H'][$index] ?? '0';
                $FORMAL_P_NPR_H = $_POST['FORMAL_P_NPR_H'][$index] ?? '0';

                $FORMAL_AH_NPR_R = $_POST['FORMAL_AH_NPR_R'][$index] ?? '0';
                $FORMAL_AY_NPR_R = $_POST['FORMAL_AY_NPR_R'][$index] ?? '0';
                $FORMAL_P_NPR_R = $_POST['FORMAL_P_NPR_R'][$index] ?? '0';

                $FORMAL_AH_NPR_C = $_POST['FORMAL_AH_NPR_C'][$index] ?? '0';
                $FORMAL_AY_NPR_C = $_POST['FORMAL_AY_NPR_C'][$index] ?? '0';
                $FORMAL_P_NPR_C = $_POST['FORMAL_P_NPR_C'][$index] ?? '0';

                //HYBRID REGISTERED CERTIFIED - INSERT
                $NPR  = 1;

                // HYBRID INSERT - NPR FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$NPR','$Hybrid', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_NPR_barangayName, $FORMAL_AH_NPR_H, $FORMAL_P_NPR_H, $FORMAL_AY_NPR_H);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'NPR Hybrid Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // REGISTERED INSERT - NPR FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$NPR','$Registered', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_NPR_barangayName, $FORMAL_AH_NPR_R, $FORMAL_P_NPR_R, $FORMAL_AY_NPR_R);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'NPR Registered Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // CERTIFIED INSERT - NPR FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$NPR','$Certified', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_NPR_barangayName, $FORMAL_AH_NPR_C, $FORMAL_P_NPR_C, $FORMAL_AY_NPR_C);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'NPR Certified Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                



            }
        }


        //RCEF FORMAL - INSERT DATA ? Check if may laman yung Barangay sa RCEF
        if (isset($_POST['FORMAL_RCEF_barangayName']) && is_array($_POST['FORMAL_RCEF_barangayName']) && !empty($_POST['FORMAL_RCEF_barangayName'])) {
            foreach ($_POST['FORMAL_RCEF_barangayName'] as $index => $FORMAL_RCEF_barangayName) {
                $FORMAL_AH_RCEF_H = $_POST['FORMAL_AH_RCEF_H'][$index] ?? '0';
                $FORMAL_AY_RCEF_H = $_POST['FORMAL_AY_RCEF_H'][$index] ?? '0';
                $FORMAL_P_RCEF_H = $_POST['FORMAL_P_RCEF_H'][$index] ?? '0';

                $FORMAL_AH_RCEF_R = $_POST['FORMAL_AH_RCEF_R'][$index] ?? '0';
                $FORMAL_AY_RCEF_R = $_POST['FORMAL_AY_RCEF_R'][$index] ?? '0';
                $FORMAL_P_RCEF_R = $_POST['FORMAL_P_RCEF_R'][$index] ?? '0';

                $FORMAL_AH_RCEF_C = $_POST['FORMAL_AH_RCEF_C'][$index] ?? '0';
                $FORMAL_AY_RCEF_C = $_POST['FORMAL_AY_RCEF_C'][$index] ?? '0';
                $FORMAL_P_RCEF_C = $_POST['FORMAL_P_RCEF_C'][$index] ?? '0';

                //HYBRID REGISTERED CERTIFIED - INSERT
                $RCEF  = 2;


                // HYBRID INSERT - RCEF FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$RCEF','$Hybrid', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_RCEF_barangayName, $FORMAL_AH_RCEF_H, $FORMAL_P_RCEF_H, $FORMAL_AY_RCEF_H);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'RCEF Hybrid Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // REGISTERED INSERT - RCEF FORMAL
                
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$RCEF','$Registered', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_RCEF_barangayName, $FORMAL_AH_RCEF_R, $FORMAL_P_RCEF_R, $FORMAL_AY_RCEF_R);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'RCEF Registered Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // CERTIFIED INSERT - RCEF FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$RCEF','$Certified', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_RCEF_barangayName, $FORMAL_AH_RCEF_C, $FORMAL_P_RCEF_C, $FORMAL_AY_RCEF_C);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'RCEF Certified Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                
            }
        }


        //OWNOTHERS FORMAL - INSERT DATA ? Check if may laman yung Barangay sa OWNOTHERS
        if (isset($_POST['FORMAL_OWNOTHERS_barangayName']) && is_array($_POST['FORMAL_OWNOTHERS_barangayName']) && !empty($_POST['FORMAL_OWNOTHERS_barangayName'])) {
            foreach ($_POST['FORMAL_OWNOTHERS_barangayName'] as $index => $FORMAL_OWNOTHERS_barangayName) {
                $FORMAL_AH_OWNOTHERS_H = $_POST['FORMAL_AH_OWNOTHERS_H'][$index] ?? '0';
                $FORMAL_AY_OWNOTHERS_H = $_POST['FORMAL_AY_OWNOTHERS_H'][$index] ?? '0';
                $FORMAL_P_OWNOTHERS_H = $_POST['FORMAL_P_OWNOTHERS_H'][$index] ?? '0';

                $FORMAL_AH_OWNOTHERS_R = $_POST['FORMAL_AH_OWNOTHERS_R'][$index] ?? '0';
                $FORMAL_AY_OWNOTHERS_R = $_POST['FORMAL_AY_OWNOTHERS_R'][$index] ?? '0';
                $FORMAL_P_OWNOTHERS_R = $_POST['FORMAL_P_OWNOTHERS_R'][$index] ?? '0';

                $FORMAL_AH_OWNOTHERS_C = $_POST['FORMAL_AH_OWNOTHERS_C'][$index] ?? '0';
                $FORMAL_AY_OWNOTHERS_C = $_POST['FORMAL_AY_OWNOTHERS_C'][$index] ?? '0';
                $FORMAL_P_OWNOTHERS_C = $_POST['FORMAL_P_OWNOTHERS_C'][$index] ?? '0';

                //HYBRID REGISTERED CERTIFIED - INSERT
                $OWNOTHERS  = 3;

                // HYBRID INSERT - OWNOTHERS FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$OWNOTHERS','$Hybrid', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_OWNOTHERS_barangayName, $FORMAL_AH_OWNOTHERS_H, $FORMAL_P_OWNOTHERS_H, $FORMAL_AY_OWNOTHERS_H);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'OWNOTHERS Hybrid Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // REGISTERED INSERT - OWNOTHERS FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$OWNOTHERS','$Registered', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_OWNOTHERS_barangayName, $FORMAL_AH_OWNOTHERS_R, $FORMAL_P_OWNOTHERS_R, $FORMAL_AY_OWNOTHERS_R);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'OWNOTHERS Registered Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // CERTIFIED INSERT - OWNOTHERS FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$OWNOTHERS','$Certified', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FORMAL_OWNOTHERS_barangayName, $FORMAL_AH_OWNOTHERS_C, $FORMAL_P_OWNOTHERS_C, $FORMAL_AY_OWNOTHERS_C);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'OWNOTHERS Certified Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                
                
            }
        }


        //INFORMAL - INSERT DATA ? Check if may laman yung Barangay sa INFORMAL
        if (isset($_POST['INFORMAL_barangayName']) && is_array($_POST['INFORMAL_barangayName']) && !empty($_POST['INFORMAL_barangayName'])) {
            foreach ($_POST['INFORMAL_barangayName'] as $index => $INFORMAL_barangayName) {
                $INFORMAL_AH_Starter = $_POST['INFORMAL_AH_Starter'][$index] ?? '0';
                $INFORMAL_AY_Starter = $_POST['INFORMAL_AY_Starter'][$index] ?? '0';
                $INFORMAL_P_Starter = $_POST['INFORMAL_P_Starter'][$index] ?? '0';

                $INFORMAL_AH_Tagged = $_POST['INFORMAL_AH_Tagged'][$index] ?? '0';
                $INFORMAL_AY_Tagged = $_POST['INFORMAL_AY_Tagged'][$index] ?? '0';
                $INFORMAL_P_Tagged = $_POST['INFORMAL_P_Tagged'][$index] ?? '0';

                $INFORMAL_AH_Traditional = $_POST['INFORMAL_AH_Traditional'][$index] ?? '0';
                $INFORMAL_AY_Traditional = $_POST['INFORMAL_AY_Traditional'][$index] ?? '0';
                $INFORMAL_P_Traditional = $_POST['INFORMAL_P_Traditional'][$index] ?? '0';

                // STARTER INSERT - INFORMAL
                
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Informal',NULL,'$Starter', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $INFORMAL_barangayName, $INFORMAL_AH_Starter, $INFORMAL_P_Starter, $INFORMAL_AY_Starter);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'Informal Starter Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // TAGGED INSERT - INFORMAL
              
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Informal',NULL,'$Tagged', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $INFORMAL_barangayName, $INFORMAL_AH_Tagged, $INFORMAL_P_Tagged, $INFORMAL_AY_Tagged);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'Informal Tagged Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // TRADITIONAL INSERT - INFORMAL
                
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Informal',NULL,'$Traditional', ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $INFORMAL_barangayName, $INFORMAL_AH_Traditional, $INFORMAL_P_Traditional, $INFORMAL_AY_Traditional);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'Informal Traditional Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

            }
        }


        //FSS - INSERT DATA ? Check if may laman yung Barangay sa FSS
        if (isset($_POST['FSS_barangayName']) && is_array($_POST['FSS_barangayName']) && !empty($_POST['FSS_barangayName'])) {
            foreach ($_POST['FSS_barangayName'] as $index => $FSS_barangayName) {
                $FSS_AH = $_POST['FSS_AH'][$index] ?? '0';
                $FSS_AY = $_POST['FSS_AY'][$index] ?? '0';
                $FSS_P = $_POST['FSS_P'][$index] ?? '0';

                // INSERT - FSS
               
                    $stmt = $con->prepare("INSERT INTO `harvesting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_harvested`, `production`, `average_yield`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$FSS',NULL,NULL, ?, ?, ?, ?, '$monthpick', '$rangeDate', '$yearInsert', '$PreparedBy')");
                    $stmt->bind_param('iddd', $FSS_barangayName, $FSS_AH, $FSS_P, $FSS_AY);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'FSS Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                
            
            }
        }


        $res = [
            'status' => 'SUCCESS',
            'message' => 'Data inserted successfully.'
        ];
        echo json_encode($res);
        return;
        
    }

?>


  