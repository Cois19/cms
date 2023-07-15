<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$doId = $_POST['doId'];
var_dump($doId);
$reset_status = '';
// Get qty count
$query9 = "UPDATE tdoc SET tstatus = 2 WHERE que = $doId";
var_dump($query9);
$result9 = mysqli_query($conn, $query9);

if ($result9) {
    $reset_status = 'success';
} else {
    $reset_status = 'fail'; // Default value when an error occurs
}

echo $reset_status;

mysqli_close($conn);
?>