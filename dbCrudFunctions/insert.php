<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$insert_result = "";

if (
    isset($_POST['palletid']) && isset($_POST['partno']) && isset($_POST['partname']) &&
    isset($_POST['qty'])
) {
    $palletid = $_POST['palletid'];
    $partno = $_POST['partno'];
    $partname = $_POST['partname'];
    $dnnumber = $_POST['dnnumber'];
    $qty = $_POST['qty'];
    $boxcount = $_POST['boxcount'];

    // Check if the same data already exists in ecd and ecd_details tables
    $query = "SELECT *
    FROM dotest
    WHERE pallet = ? AND partno = ? AND partname = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $palletid, $partno, $partname);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Data already exists, show alert and do not execute INSERT query
        $insert_result = "Data already exists!";
    } else {

        $query1 = "INSERT INTO dotest(pallet, partno, partname, dnnumber, quantity, boxcount, `date`) 
            VALUES(?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = mysqli_prepare($conn, $query1);
        mysqli_stmt_bind_param($stmt, 'ssssii', $palletid, $partno, $partname, $dnnumber, $qty, $boxcount);
        if (!mysqli_stmt_execute($stmt)) {
            error_log("Error in insert query: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);

        
    }
}

echo json_encode(array('result' => $insert_result));
mysqli_close($conn);
?>