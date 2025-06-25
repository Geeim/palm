<?php
//CODE DONE
include "../connection.php";

if (isset($_GET['token'])) {
        
    $encoded_token = $_GET['token'];
    $decoded_token = base64_decode($encoded_token);

    $sql = "DELETE FROM `one_time_token` WHERE `token` = '$decoded_token'";
    $result = mysqli_query($con,$sql);


       if($result){
        echo "<script>window.location.href = '../index.php';</script>";
        exit; // Add exit to stop script execution
       }else {
            echo"Fail";
            echo "<script>window.location.href = '../index.php';</script>";
          }

}else{
  echo "<script>window.location.href = '../index.php';</script>";
}


?>


