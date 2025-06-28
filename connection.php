<?php
	$Hostname = "switchyard.proxy.rlwy.net";
	$Username = "root";
	$Password = "oZiOBuSiraHJubGOwIFcmRZOIpgkgAyA";
	$DBname   = "railway";
	$Port     = 44908; 

	$con = mysqli_connect($Hostname, $Username, $Password, $DBname, $Port);

	if (!$con){
		echo "Connection failed!";
		exit();
	}
?>