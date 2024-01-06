<?php
include '../../../database/connect.php';
include '../../../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$tag = $_POST['tag'];
$delete_status = '';

$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $delete_status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';
    if ($utype != 1) {
        $delete_status = 'unauthorized';
    } else {
        $query15 = "SELECT partno FROM tinventorytag WHERE que = '$tag'";
        $result15 = mysqli_query($conn, $query15);
        $partno = mysqli_fetch_array($result15)[0];

        $query9 = "DELETE FROM tinventorytag WHERE que = '$tag'";
        $result9 = mysqli_query($conn, $query9);

        if ($result9) {
            $delete_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, var1, cd, cp) VALUES('DELETE TAG', '$tag', '$partno', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);
        } else {
            $delete_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $delete_status;

mysqli_close($conn);
?>