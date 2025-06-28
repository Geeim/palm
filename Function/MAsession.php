<?php
session_start();

if(empty($_SESSION['MAstatus']) || $_SESSION['MAstatus'] == 'MAinvalid'){

    $_SESSION['MAstatus'] = 'MAinvalid';
    unset($_SESSION['IDadmin']);

    echo "<script>window.location.href = '../index.php';</script>";
}

?>