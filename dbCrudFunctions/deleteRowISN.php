<?php
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$isn = $_POST['isn'];
$delete_status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $delete_status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../users/session.php';
    if ($utype != 1) {
        $delete_status = 'unauthorized';
    } else {
        $query9 = "UPDATE tisn SET tstatus = 0, lup = '$uid', lud = CURRENT_TIMESTAMP WHERE que = '$isn'";
        $result9 = mysqli_query($conn, $query9);

        if ($result9) {
            $delete_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('DELETE ISN', '$isn', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);
        } else {
            $delete_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $delete_status;

mysqli_close($conn);
?>