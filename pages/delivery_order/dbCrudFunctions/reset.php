<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$doId = $_POST['doId'];
$reset_status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $reset_status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';
    if ($utype == 3) {
        $reset_status = 'unauthorized';
    } else {
        $query9 = "UPDATE tisn SET tstatus = 0 WHERE tdoc_que = $doId";
        $result9 = mysqli_query($conn, $query9);

        // Insert into tisn_sum
        $query10 = "INSERT INTO tisn_sum (tdono, tisn, tpn, tmodel, tvendor, tcost, tstatus, tdoc_que, cp, cd) SELECT tdono, tisn, tpn, tmodel, tvendor, tcost, tstatus, tdoc_que, cp, cd FROM tisn WHERE tisn.tdoc_que = $doId";
        $result10 = mysqli_query($conn, $query10);

        // Delete from tisn
        $query11 = "DELETE FROM tisn WHERE tisn.tdoc_que = $doId";
        $result11 = mysqli_query($conn, $query11);

        if ($result9) {
            $reset_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('RESET ISN', '$doId', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);
        } else {
            $reset_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $reset_status;

mysqli_close($conn);
?>