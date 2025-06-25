<?php
//CODE DONE
session_start();

if(empty($_SESSION['ATstatus']) || $_SESSION['ATstatus'] == 'ATinvalid'){

    $_SESSION['ATstatus'] = 'ATinvalid';
    unset($_SESSION['IDemployee']);

    echo "<script>window.location.href = '../index.php';</script>";
}

?>