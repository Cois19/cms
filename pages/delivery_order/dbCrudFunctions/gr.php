<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$doId = $_POST['doId'];
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
    // Get qty count
    $query2 = "SELECT (tqty - COUNT(*)) AS qtyCount FROM tdoc JOIN tisn ON tdoc.que = tisn.tdoc_que WHERE tdoc.que = $doId AND tisn.tstatus = 1 GROUP BY tisn.tdoc_que";
    $result2 = mysqli_query($conn, $query2);

    $qtyCount = '';

    if (mysqli_num_rows($result2) > 0) {
        $row2 = mysqli_fetch_assoc($result2);
        $qtyCount = $row2['qtyCount'];
    } else {
        // No rows found in tisn table, return the value of tqty instead
        $query3 = "SELECT tqty FROM tdoc WHERE que = $doId";
        $result3 = mysqli_query($conn, $query3);
        $row3 = mysqli_fetch_assoc($result3);
        $qtyCount = $row3['tqty'];
    }

    if ($qtyCount != 0) {
        $reset_status = 'unauthorized';
    } else {
        // Update DO status to complete
        $query9 = "UPDATE tdoc SET tstatus = 2, lup = '$uid', lud = CURRENT_TIMESTAMP WHERE que = $doId";
        $result9 = mysqli_query($conn, $query9);

        // Insert into tisn_sum
        $query10 = "INSERT INTO tisn_sum (tdono, tisn, tpn, tmodel, tvendor, tcost, tstatus, tdoc_que, cp, cd) SELECT tdono, tisn, tpn, tmodel, tvendor, tcost, tstatus, tdoc_que, cp, cd FROM tisn WHERE tisn.tdoc_que = $doId";
        $result10 = mysqli_query($conn, $query10);

        // Delete from tisn
        $query11 = "DELETE FROM tisn WHERE tisn.tdoc_que = $doId";
        $result11 = mysqli_query($conn, $query11);

        // Insert into tshipping_sum
        $query12 = "INSERT INTO tshipping_sum (isl, messageDetailSN, partNumber, CustomerProject, palletId, cp) SELECT isl, messageDetailSN, partNumber, CustomerProject, palletId, cp FROM tshipping WHERE palletId = '$pid' AND partNumber = '$pno'";
        $result12 = mysqli_query($conn, $query12);

        // Delete from tshipping
        $query13 = "DELETE FROM tshipping WHERE palletId = '$pid' AND partNumber = '$pno'";
        $result13 = mysqli_query($conn, $query13);

        if ($result9) {
            $reset_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('GR COMPLETE', '$doId', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);

        } else {
            $reset_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $reset_status;

mysqli_close($conn);
?>