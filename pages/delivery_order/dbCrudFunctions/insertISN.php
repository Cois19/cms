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
    $pid = $_POST['pid'];
    $pno = $_POST['pno'];
    $tisn = $_POST['isn'];

    if (!empty($tisn)) {
        if (strlen($tisn) < 15 || strlen($tisn) > 20) {
            $response = 'length';

            // Insert into tlog
            $query2 = "INSERT INTO tlog(tprocess, tdata, var1, cd, cp) VALUES('WRONG ISN LENGTH', '$tisn', '$doId', CURRENT_TIMESTAMP, '$uid')";
            $result2 = mysqli_query($conn, $query2);
        } else {
            $numofrows = 1;
            if (strlen($pid) == 8) {
                // check if ISN exists in shipping table
                $query8 = "SELECT tshipping.messageDetailSN FROM tshipping WHERE partNumber = '$pno' AND palletId = '$pid' and messageDetailSN = '$tisn'";
                $result8 = mysqli_query($conn, $query8);
                $numofrows = mysqli_num_rows($result8);
            }

            if ($numofrows == 0) {
                $response = 'wrongisn';
            } else {
                // get tdono, tpno, tvendor, tpmodel from tdoc table
                $query6 = "SELECT tdono, tpno, tvendor, tpmodel FROM tdoc WHERE que = $doId";
                $result6 = mysqli_query($conn, $query6);
                $row6 = mysqli_fetch_assoc($result6);

                $tdono = $row6['tdono'];
                $tpno = $row6['tpno'];
                $tvendor = $row6['tvendor'];
                $tpmodel = $row6['tpmodel'];

                // Check if the same data already exists in tdoc table
                $query = "SELECT que FROM tisn WHERE tdono = '$tdono' AND tisn = '$tisn' AND tpn = '$tpno' AND tvendor = '$tvendor' AND tstatus = 1 LIMIT 1";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    // Data already exists, set response as "fail" and retrieve que
                    $response = "fail";
                    $row = mysqli_fetch_assoc($result);
                } else {
                    $query1 = "INSERT INTO tisn(tdono, tisn, tpn, tstatus, tmodel, tvendor, tcost, tdoc_que, cd, cp) 
                                VALUES('$tdono', '$tisn', '$tpno', 1, '$tpmodel', '$tvendor', '$ucost', $doId, CURRENT_TIMESTAMP, '$uid')";
                    $result1 = mysqli_query($conn, $query1);

                    if ($result1 != 1) {
                        error_log("Error in insert query: " . mysqli_error($conn));
                    } else {
                        // Data inserted successfully, set response as "success"
                        $response = "success";
                    }
                }
            }
        }
    } else {
        $response = "empty";
    }
}

$responseData = array(
    'status' => $response,
    'que' => $que
);

echo json_encode($responseData);

mysqli_close($conn);
?>