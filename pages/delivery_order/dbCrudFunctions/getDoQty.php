<?php
include '../../../database/connect.php';
include '../../../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$isl = $_POST['isl'];
$qty = '';

$query = "SELECT tqty FROM tdoc WHERE tpid LIKE '%$isl%'";
// echo $query;
$result = mysqli_query($conn, $query);
$qty = mysqli_fetch_array($result)[0];

mysqli_close($conn);

echo $qty;
?>