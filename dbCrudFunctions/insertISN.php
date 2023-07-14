<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$response = '';
$que = '';

$doId = $_POST['doId'];
$tisn = $_POST['isn'];

// get tdono and tpn from tdoc table
$query6 = "SELECT tdono, tpno FROM tdoc WHERE que = $doId";
$result6 = mysqli_query($conn, $query6);
$row6 = mysqli_fetch_assoc($result6);

$tdono = $row6['tdono'];
$tpno = $row6['tpno'];

// Check if the same data already exists in tdoc table
$query = "SELECT que FROM tisn WHERE tdono = '$tdono' AND tisn = '$tisn' AND tpn = '$tpno' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Data already exists, set response as "fail" and retrieve que
    $response = "fail";
    $row = mysqli_fetch_assoc($result);
    $que = $row['que'];
} else {
    // if (strlen($palletid) == 16) {
    $query1 = "INSERT INTO tisn(tdono, tisn, tpn, cd, cp) 
                VALUES('$tdono', '$tisn', '$tpno', CURRENT_TIMESTAMP, '$uid')";
    // } else if (strlen($palletid) == 8) {
    //     $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
    //             VALUES('$palletid', '$partno', '$partname', '$palletid', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$uid')";
    // }

    $result1 = mysqli_query($conn, $query1);

    if ($result1 != 1) {
        error_log("Error in insert query: " . mysqli_error($conn));
    } else {
        // Data inserted successfully, set response as "success"
        $response = "success";

        // Retrieve que for the newly inserted data
        $query3 = "SELECT que FROM tisn WHERE tdono = '$tdono' AND tisn = '$tisn' AND tpn = '$tpno' LIMIT 1";
        $result3 = mysqli_query($conn, $query3);
        $row = mysqli_fetch_assoc($result3);
        $que = $row['que'];
    }
}

$responseData = array(
    'status' => $response,
    'que' => $que
);

echo json_encode($responseData);

mysqli_close($conn);
?>