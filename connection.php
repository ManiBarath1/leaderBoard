<?php

// Connect to your database (Replace with your actual database connection code)
$host = "localhost";
$username = "root";
$password = "";
$dbName = "leaderboard";
$conn = mysqli_connect($host, $username, $password, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


?>