<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';

    $id = $_POST['id'];
    $areacode = $_POST['areacode'];
    $subloc = $_POST['subloc'];
    $qty = $_POST['qty'];

    $query = "UPDATE tinventorytag SET areacode = '$areacode', subloc = '$subloc', qty = $qty, cd = CURRENT_TIMESTAMP(), cp = '$uid' WHERE que = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $status = 'success';

        // Insert into tlog
        $query1 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('UPDATE INVENTORY TAG', '$id', CURRENT_TIMESTAMP, '$uid')";
        $result1 = mysqli_query($conn, $query1);
    } else {
        $status = 'fail'; // Default value when an error occurs
    }
}

echo $status;

mysqli_close($conn);
?>