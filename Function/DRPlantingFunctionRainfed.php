<?php

    include '../connection.php'; 

    session_start();

    $IDemployee = $_SESSION['IDemployee'];

    $PreparedBySQL = "SELECT `Efname`, `Elname`, `Emname` FROM `employee` WHERE `IDemployee` = $IDemployee";
    $PreparedResult = mysqli_query($con, $PreparedBySQL);
    $EmployeeData = mysqli_fetch_assoc($PreparedResult);

    $PreparedBy = trim($EmployeeData['Efname'] . ' ' . ($EmployeeData['Emname'] ?? '') . '. ' . $EmployeeData['Elname']);
    
    
    //INSERT PLANTING DATA

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //DEFAULT VALUE FORAML AND INFORMAL & MODAL SETUP VALUE - SUNOD SUNOD
        $landtype = 2; //RAINFED
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
                $FORMAL_AP_NPR_H = $_POST['FORMAL_AP_NPR_H'][$index] ?? '0';
                $FORMAL_NF_NPR_H = $_POST['FORMAL_NF_NPR_H'][$index] ?? '0';
                
                $FORMAL_AP_NPR_R = $_POST['FORMAL_AP_NPR_R'][$index] ?? '0';
                $FORMAL_NF_NPR_R = $_POST['FORMAL_NF_NPR_R'][$index] ?? '0';

                $FORMAL_AP_NPR_C = $_POST['FORMAL_AP_NPR_C'][$index] ?? '0';
                $FORMAL_NF_NPR_C = $_POST['FORMAL_NF_NPR_C'][$index] ?? '0';

                //HYBRID REGISTERED CERTIFIED - INSERT
                $NPR  = 1;

                // HYBRID INSERT - NPR FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$NPR','$Hybrid',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_NPR_barangayName, $FORMAL_AP_NPR_H,  $FORMAL_NF_NPR_H);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'NPR Hybrid Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // REGISTERED INSERT - NPR FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$NPR','$Registered',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_NPR_barangayName, $FORMAL_AP_NPR_R,  $FORMAL_NF_NPR_R);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'NPR Registered Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // CERTIFIED INSERT - NPR FORMAL
                
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$NPR','$Certified',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_NPR_barangayName, $FORMAL_AP_NPR_C,  $FORMAL_NF_NPR_C);
                    
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
                $FORMAL_AP_RCEF_H = $_POST['FORMAL_AP_RCEF_H'][$index] ?? '0';
                $FORMAL_NF_RCEF_H = $_POST['FORMAL_NF_RCEF_H'][$index] ?? '0';

                $FORMAL_AP_RCEF_R = $_POST['FORMAL_AP_RCEF_R'][$index] ?? '0';
                $FORMAL_NF_RCEF_R = $_POST['FORMAL_NF_RCEF_R'][$index] ?? '0';

                $FORMAL_AP_RCEF_C = $_POST['FORMAL_AP_RCEF_C'][$index] ?? '0';
                $FORMAL_NF_RCEF_C = $_POST['FORMAL_NF_RCEF_C'][$index] ?? '0';

                //HYBRID REGISTERED CERTIFIED - INSERT
                $RCEF  = 2;


                // HYBRID INSERT - RCEF FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$RCEF','$Hybrid',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_RCEF_barangayName, $FORMAL_AP_RCEF_H, $FORMAL_NF_RCEF_H);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'RCEF Hybrid Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // REGISTERED INSERT - RCEF FORMAL
            
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$RCEF','$Registered',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_RCEF_barangayName, $FORMAL_AP_RCEF_R, $FORMAL_NF_RCEF_R);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'RCEF Registered Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // CERTIFIED INSERT - RCEF FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$RCEF','$Certified',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_RCEF_barangayName, $FORMAL_AP_RCEF_C, $FORMAL_NF_RCEF_C);
                    
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
                $FORMAL_AP_OWNOTHERS_H = $_POST['FORMAL_AP_OWNOTHERS_H'][$index] ?? '0';
                $FORMAL_NF_OWNOTHERS_H = $_POST['FORMAL_NF_OWNOTHERS_H'][$index] ?? '0';

                $FORMAL_AP_OWNOTHERS_R = $_POST['FORMAL_AP_OWNOTHERS_R'][$index] ?? '0';
                $FORMAL_NF_OWNOTHERS_R = $_POST['FORMAL_NF_OWNOTHERS_R'][$index] ?? '0';

                $FORMAL_AP_OWNOTHERS_C = $_POST['FORMAL_AP_OWNOTHERS_C'][$index] ?? '0';
                $FORMAL_NF_OWNOTHERS_C = $_POST['FORMAL_NF_OWNOTHERS_C'][$index] ?? '0';

                //HYBRID REGISTERED CERTIFIED - INSERT
                $OWNOTHERS  = 3;

                // HYBRID INSERT - OWNOTHERS FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$OWNOTHERS','$Hybrid',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_OWNOTHERS_barangayName, $FORMAL_AP_OWNOTHERS_H, $FORMAL_NF_OWNOTHERS_H);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'OWNOTHERS Hybrid Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // REGISTERED INSERT - OWNOTHERS FORMAL
              
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$OWNOTHERS','$Registered',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_OWNOTHERS_barangayName, $FORMAL_AP_OWNOTHERS_R, $FORMAL_NF_OWNOTHERS_R);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'OWNOTHERS Registered Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // CERTIFIED INSERT - OWNOTHERS FORMAL
               
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Formal','$OWNOTHERS','$Certified',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FORMAL_OWNOTHERS_barangayName, $FORMAL_AP_OWNOTHERS_C, $FORMAL_NF_OWNOTHERS_C);
                    
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
                $INFORMAL_AP_Starter = $_POST['INFORMAL_AP_Starter'][$index] ?? '0';
                $INFORMAL_NF_Starter = $_POST['INFORMAL_NF_Starter'][$index] ?? '0';

                $INFORMAL_AP_Tagged = $_POST['INFORMAL_AP_Tagged'][$index] ?? '0';
                $INFORMAL_NF_Tagged = $_POST['INFORMAL_NF_Tagged'][$index] ?? '0';

                $INFORMAL_AP_Traditional = $_POST['INFORMAL_AP_Traditional'][$index] ?? '0';
                $INFORMAL_NF_Traditional = $_POST['INFORMAL_NF_Traditional'][$index] ?? '0';

                // STARTER INSERT - INFORMAL
              
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Informal',NULL,'$Starter',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $INFORMAL_barangayName, $INFORMAL_AP_Starter, $INFORMAL_NF_Starter);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'INFORMAL Starter Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // TAGGED INSERT - INFORMAL
               
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Informal',NULL,'$Tagged',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $INFORMAL_barangayName, $INFORMAL_AP_Tagged, $INFORMAL_NF_Tagged);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'INFORMAL Tagged Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

                // TRADITIONAL INSERT - INFORMAL
               
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$Informal',NULL,'$Traditional',?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $INFORMAL_barangayName, $INFORMAL_AP_Traditional, $INFORMAL_NF_Traditional);
                    
                    if (!$stmt->execute()) {
                        $res = ['status' => 'ERROR', 'message' => 'INFORMAL Traditional Inserting Error!'];
                        echo json_encode($res);
                        return;} 
                    $stmt->close();
                

            }
        }


        //FSS - INSERT DATA ? Check if may laman yung Barangay sa FSS
        if (isset($_POST['FSS_barangayName']) && is_array($_POST['FSS_barangayName']) && !empty($_POST['FSS_barangayName'])) {
            foreach ($_POST['FSS_barangayName'] as $index => $FSS_barangayName) {
                $FSS_AP = $_POST['FSS_AP'][$index] ?? '0';
                $FSS_NF = $_POST['FSS_NF'][$index] ?? '0';

                // INSERT - FSS
               
                    $stmt = $con->prepare("INSERT INTO `planting`(`landtype`, `season_type`, `seed_system_type`, `project_type`, `seed_name`, `barangay`, `area_planted`, `no_farmers`, `month`, `range_date`, `year`, `prepared_by`) VALUES ('$landtype','$selectedseason','$FSS',NULL,NULL,?,?,?,'$monthpick','$rangeDate','$yearInsert','$PreparedBy')");
                    $stmt->bind_param('idd', $FSS_barangayName, $FSS_AP, $FSS_NF);
                    
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


  