<?php
$conn = mysqli_connect('localhost', 'root', '', 'vcms');
// $conn = mysqli_connect('sncto01.satnusa.com', 'superadmin', 'Superman@2021!', 'vcms');

if (!$conn) {
    die("Error connecting to the database: " . mysqli_connect_error());
  }
?>