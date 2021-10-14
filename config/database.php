<?php
error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_log', './error.log');
error_reporting(E_ALL);

// $conn = new PDO("mysql:host=localhost;dbname=bincomphptest", "root", "");

$servername = "localhost";
$username = "root";
$password = "";
$db = "bincomphptest";

try {
  $conn = new PDO("mysql:host=$servername;dbname=".$db, $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
