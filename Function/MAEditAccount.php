<?php

include "../connection.php";

if (isset($_POST['IDadmin'])) {

    $IDadmin = trim($_POST['IDadmin']);

    $stmt = $con->prepare("SELECT * FROM `admin` WHERE `IDadmin` = ?");
    $stmt->bind_param("s", $IDadmin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // Store the employee data
        $Afname = $row['Afname'];
        $Alname = $row['Alname'];
        $Amname = $row['Amname'];
        $Aemail = $row['Aemail'];
        $Ausername = $row['Ausername'];

        // Return the data as a JSON response
        $res = [
            'status' => 'SUCCESS',
            'Afname' => $Afname,
            'Alname' => $Alname,
            'Amname' => $Amname,
            'Aemail' => $Aemail,
            'Ausername' => $Ausername,
        ];

        echo json_encode($res);
        return;

    }else {
        $res = [
            'status' => 'ERROR',
            'message' => 'Admin not found'
        ];
        echo json_encode($res);
        return;
    }



}else {
    
    $res = [
        'status' => 'ERROR',
        'message' => 'IDadmin is required'
    ];
    echo json_encode($res);
    return;
}

?>