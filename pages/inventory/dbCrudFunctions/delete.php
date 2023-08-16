<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$que = $_POST['que'];
$reset_status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $reset_status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';

    if ($utype != 1) {
        $reset_status = 'unauthorized';
    } else {
        $query9 = "UPDATE tperiod SET status = 0, lup = '$uid', lud = CURRENT_TIMESTAMP WHERE que = '$que'";
        $result9 = mysqli_query($conn, $query9);

        if ($result9) {
            $reset_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('DEACTIVATE PERIOD', '$que', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);

        } else {
            $reset_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $reset_status;

mysqli_close($conn);
?>