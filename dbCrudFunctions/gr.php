<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$tdono = $_POST['tdono'];
$reset_status = '';

// Get qty count
$query2 = "SELECT (tqty - COUNT(*)) AS qtyCount FROM tdoc JOIN tisn ON tdoc.tdono = tisn.tdono WHERE tdoc.tdono = '$tdono' AND tisn.tstatus = 1 GROUP BY tisn.tdono";
$result2 = mysqli_query($conn, $query2);

$qtyCount = '';

if (mysqli_num_rows($result2) > 0) {
    $row2 = mysqli_fetch_assoc($result2);
    $qtyCount = $row2['qtyCount'];
} else {
    // No rows found in tisn table, return the value of tqty instead
    $query3 = "SELECT tqty FROM tdoc WHERE tdono = '$tdono'";
    $result3 = mysqli_query($conn, $query3);
    $row3 = mysqli_fetch_assoc($result3);
    $qtyCount = $row3['tqty'];
}

if ($qtyCount != 0) {
    $reset_status = 'unauthorized';
} else {
    $query9 = "UPDATE tdoc SET tstatus = 2, lup = '$uid', lud = CURRENT_TIMESTAMP WHERE tdono = '$tdono'";
    $result9 = mysqli_query($conn, $query9);

    if ($result9) {
        $reset_status = 'success';

        // Insert into tlog
        $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('GR COMPLETE', '$tdono', CURRENT_TIMESTAMP, '$uid')";
        $result = mysqli_query($conn, $query);

    } else {
        $reset_status = 'fail'; // Default value when an error occurs
    }
}

echo $reset_status;

mysqli_close($conn);
?>