<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo_2";

$conn = mysqli_connect($servername,$username,$password,$dbname);


if (!$conn) {
    die("Connectiob failed". mysqli_connect_error());
    
}
?>

