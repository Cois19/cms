<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$tdono = $_POST['tdono'];
$pid = $_POST['pid'];
$pno = $_POST['pno'];
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
        $query9 = "UPDATE tdoc SET tstatus = 0, lup = '$uid', lud = CURRENT_TIMESTAMP WHERE tdono = '$tdono'";
        $result9 = mysqli_query($conn, $query9);

        $query12 = "UPDATE tisn SET tisn.tstatus = 0 WHERE tisn.tdono = '$tdono'";
        $result12 = mysqli_query($conn, $query12);

        // Insert into tisn_sum
        $query10 = "INSERT INTO tisn_sum (tdono, tisn, tpn, tmodel, tvendor, tcost, tstatus, tdoc_que, cp, cd) SELECT tdono, tisn, tpn, tmodel, tvendor, tcost, tstatus, tdoc_que, cp, cd FROM tisn WHERE tisn.tdono = '$tdono'";
        $result10 = mysqli_query($conn, $query10);

        // Delete from tisn
        $query11 = "DELETE FROM tisn WHERE tisn.tdono = '$tdono'";
        $result11 = mysqli_query($conn, $query11);

        // Insert into tshipping_sum
        $query14 = "INSERT INTO tshipping_sum (isl, messageDetailSN, partNumber, CustomerProject, palletId, cp) SELECT isl, messageDetailSN, partNumber, CustomerProject, palletId, cp FROM tshipping WHERE palletId = '$pid' AND partNumber = '$pno'";
        $result14 = mysqli_query($conn, $query14);

        // Delete from tshipping
        $query13 = "DELETE FROM tshipping WHERE palletId = '$pid' AND partNumber = '$pno'";
        $result13 = mysqli_query($conn, $query13);

        if ($result9) {
            $reset_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('DELETE DO', '$tdono', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);

        } else {
            $reset_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $reset_status;

mysqli_close($conn);
?>