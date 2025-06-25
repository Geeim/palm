<?php

include "../connection.php";

if (isset($_POST['IDemployee'])) {
    $IDemployee = trim($_POST['IDemployee']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM `employee` WHERE `IDemployee` = ?");
    $stmt->bind_param("s", $IDemployee);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if an employee was found
    if ($result->num_rows > 0) {
        // Fetch the employee data
        $row = $result->fetch_assoc();

        // Store the employee data
        $Efname = $row['Efname'];
        $Elname = $row['Elname'];
        $Emname = $row['Emname'];
        $Eemail = $row['Eemail'];
        $Eusername = $row['Eusername'];

        // Return the data as a JSON response
        $res = [
            'status' => 'SUCCESS',
            'Efname' => $Efname,
            'Elname' => $Elname,
            'Emname' => $Emname,
            'Eemail' => $Eemail,
            'Eusername' => $Eusername,
        ];

        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 'ERROR',
            'message' => 'Employee not found'
        ];
        echo json_encode($res);
        return;
    }
} else {
    // If IDemployee is not set, return an error
    $res = [
        'status' => 'ERROR',
        'message' => 'IDemployee is required'
    ];
    echo json_encode($res);
    return;
}

?>
