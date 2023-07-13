<?php
// define("DB_HOST", "localhost");
// define("DB_USER", "root");
// define("DB_PASS", "");
// define("DB_NAME", "vm");

$conn = mysqli_connect('localhost', 'root', '', 'vcms');
// $conn = new mysqli('sncto01.satnusa.com', 'superadmin', 'Superman@2021!', 'vm');

if (!$conn) {
    die("Error connecting to the database: " . mysqli_connect_error());
  }
?>