<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$response = '';
$que = '';

$do = explode(',', $_POST['do']);

$palletid = $do[0];
$partno = $do[1];
$partname = $do[2];
$dnnumber = $do[3];
$qty = $do[4];
$boxcount = $do[5];
$date = $do[6];

// Check if the same data already exists in tdoc table
$query = "SELECT que FROM tdoc WHERE tpid = '$palletid' AND tpno = '$partno' AND tpname = '$partname' AND tqty = '$qty' AND tdate = '$date' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Data already exists, set response as "fail" and retrieve que
    $response = "fail";
    $row = mysqli_fetch_assoc($result);
    $que = $row['que'];
} else {
    if (strlen($palletid) == 16) {
        $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
                VALUES('$palletid', '$partno', '$partname', '$dnnumber', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$uid')";
    } else if (strlen($palletid) == 8) {
        $query1 = "INSERT INTO tdoc(tpid, tpno, tpname, tdono, tqty, tbxcount, tdate, cd, cp) 
                VALUES('$palletid', '$partno', '$partname', '$palletid', '$qty', '$boxcount', '$date', CURRENT_TIMESTAMP, '$uid')";
    }

    $result1 = mysqli_query($conn, $query1);

    if ($result1 != 1) {
        error_log("Error in insert query: " . mysqli_error($conn));
    }
    else {
        // Data inserted successfully, set response as "success"
        $response = "success";

        // Retrieve que for the newly inserted data
        $query3 = "SELECT que FROM tdoc WHERE tpid = '$palletid' AND tpno = '$partno' AND tpname = '$partname' AND tqty = '$qty' AND tdate = '$date' LIMIT 1";
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
