<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$insert_status = '';

$do = explode(',', $_POST['do']);

$palletid = $do[0];
$partno = $do[1];
$partname = $do[2];
$dnnumber = $do[3];
$qty = $do[4];
$boxcount = $do[5];
$date = $do[6];

// Check if the same data already exists in ecd and ecd_details tables
// $query = "SELECT *
//     FROM dotest
//     WHERE pallet = ? AND partno = ? AND partname = ?";
// $stmt = mysqli_prepare($conn, $query);
// mysqli_stmt_bind_param($stmt, 'sss', $palletid, $partno, $partname);
// mysqli_stmt_execute($stmt);
// mysqli_stmt_store_result($stmt);

// if (mysqli_stmt_num_rows($stmt) > 0) {
//     // Data already exists, show alert and do not execute INSERT query
//     $insert_result = "Data already exists!";
// } else {


if (strlen($palletid) == 16) {
    $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
                VALUES('$palletid', '$partno', '$partname', '$dnnumber', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$uid')";
    $result1 = mysqli_query($conn, $query1);
} else if (strlen($palletid) == 8) {
    $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
                VALUES('$palletid', '$partno', '$partname', '$palletid', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$uid')";
    $result1 = mysqli_query($conn, $query1);
}

if ($result1 != 1) {
    error_log("Error in insert query: " . mysqli_error($conn));
    $insert_status = 'fail';
} else {
    $insert_status = 'success';
}

// }
echo $insert_status;
mysqli_close($conn);
?>