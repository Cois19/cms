<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$response = '';
$que = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $response = 'timeout'; // Set response as "timeout" for session timeout
    $que = 'timeout';
} else {
    include '../../../users/session.php';
    $doId = $_POST['doId'];
    $tisn = $_POST['isn'];

    if (strlen($tisn) < 15 || strlen($tisn) > 20) {
        $response = 'length';
        $que = 'length';

        // Insert into tlog
        $query2 = "INSERT INTO tlog(tprocess, tdata, var1, cd, cp) VALUES('WRONG ISN LENGTH', '$tisn', '$doId', CURRENT_TIMESTAMP, '$uid')";
        $result2 = mysqli_query($conn, $query2);
    } else {

        // get tdono, tpno, tvendor from tdoc table
        $query6 = "SELECT tdono, tpno, tvendor FROM tdoc WHERE que = $doId";
        $result6 = mysqli_query($conn, $query6);
        $row6 = mysqli_fetch_assoc($result6);

        $tdono = $row6['tdono'];
        $tpno = $row6['tpno'];
        $tvendor = $row6['tvendor'];

        // get model from tdatamaster
        $query4 = "SELECT tpmodel FROM tdatamaster WHERE tpn = '$tpno'";
        $result4 = mysqli_query($conn, $query4);
        $row4 = mysqli_fetch_assoc($result4);
        $tpmodel = $row4['tpmodel'];

        // Check if the same data already exists in tdoc table
        $query = "SELECT que FROM tisn WHERE tdono = '$tdono' AND tisn = '$tisn' AND tpn = '$tpno' AND tvendor = '$tvendor' AND tstatus = 1 LIMIT 1";
        $result = mysqli_query($conn, $query);

        if (!empty($tisn)) {
            if (mysqli_num_rows($result) > 0) {
                // Data already exists, set response as "fail" and retrieve que
                $response = "fail";
                $row = mysqli_fetch_assoc($result);
                $que = $row['que'];
            } else {
                $query1 = "INSERT INTO tisn(tdono, tisn, tpn, tstatus, tmodel, tvendor, tcost, tdoc_que, cd, cp) 
                    VALUES('$tdono', '$tisn', '$tpno', 1, '$tpmodel', '$tvendor', '$ucost', $doId, CURRENT_TIMESTAMP, '$uid')";
                $result1 = mysqli_query($conn, $query1);

                if ($result1 != 1) {
                    error_log("Error in insert query: " . mysqli_error($conn));
                } else {
                    // Data inserted successfully, set response as "success"
                    $response = "success";

                    // Insert into tlog
                    $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT TISN', '$tisn', CURRENT_TIMESTAMP, '$uid')";
                    $result2 = mysqli_query($conn, $query2);

                    // Retrieve que for the newly inserted data
                    // $query3 = "SELECT que FROM tisn WHERE tdono = '$tdono' AND tisn = '$tisn' AND tpn = '$tpno' AND tvendor = '$tvendor' AND tcost = '$ucost'  LIMIT 1";
                    // $result3 = mysqli_query($conn, $query3);
                    // $row = mysqli_fetch_assoc($result3);
                    // $que = $row['que'];
                }
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    }
}

$responseData = array(
    'status' => $response,
    'que' => $que
);

echo json_encode($responseData);

mysqli_close($conn);
?>