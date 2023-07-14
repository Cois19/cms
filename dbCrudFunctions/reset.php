<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$tdono = $_POST['tdono'];
$reset_status = '';
// Get qty count
$query9 = "UPDATE tisn SET tstatus = 0 WHERE tdono = '$tdono'";
$result9 = mysqli_query($conn, $query9);

if ($result9) {
    $reset_status = 'success';
} else {
    $reset_status = 'fail'; // Default value when an error occurs
}

echo $reset_status;

mysqli_close($conn);
?>