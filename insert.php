<?php

include 'dbconnect.php';

$Temp = $_GET['Temp'];
$Humid = $_GET['Humid'];
$O2 = $_GET['O2'];
$CO2 = $_GET['CO2'];

date_default_timezone_set('Asia/Bangkok');
$time  = date("Y-m-d H:i:s");

$result = mysqli_query($dbh,
    "INSERT INTO `ORdata`(`Temp`, `Humid`, `O2`, `CO2`, `day`) 
    VALUES ($Temp,$Humid,$O2,$CO2,'$time')");

   echo $result
    
?>