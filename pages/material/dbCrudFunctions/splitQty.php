<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");
$response = '';
$que = '';
$qty = '';

function getParts($string, $positions)
{
    $parts = array();

    foreach ($positions as $position) {
        $parts[] = substr($string, 0, $position);
        $string = substr($string, $position);
    }

    return $parts;
}

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $response = 'timeout'; // Set response as "timeout" for session timeout
    $que = 'timeout';
} else {
    include '../../../users/session.php';
    $mode = $_POST['mode'];

    if ($mode == 'splitQty') {
        if (!empty($_POST['que'])) {
            $que = $_POST['que'];


            $query1 = "SELECT qty FROM mc_materialmaster WHERE que = $que";

            // Execute the query1
            $result1 = mysqli_query($conn, $query1);

            if ($result1 != 1) {
                error_log("Error in insert query: " . mysqli_error($conn));
            } else {
                $updateStatusQuery = "UPDATE mc_materialmaster SET status = 1 WHERE que = '$que'";
                mysqli_query($conn, $updateStatusQuery);
                $response = "success";

                // check if all materials are received
                $getDhuQuery = "SELECT d_hu FROM mc_materialmaster WHERE que = '$que' LIMIT 1";
                $getDhuResult = mysqli_query($conn, $getDhuQuery);
                $dhu = mysqli_fetch_array($getDhuResult)[0];

                $getPendingQtyQuery = "SELECT
                                            mchu.d_hu,
                                            SUM(CASE WHEN mcm.status = 0 THEN 1 ELSE 0 END) AS 'PENDING'
                                        FROM mc_materialmaster mcm JOIN mc_hu mchu ON mcm.d_hu = mchu.d_hu
                                        WHERE mchu.d_hu = '$dhu'
                                        GROUP BY mchu.d_hu
                                        ORDER BY mchu.cd DESC
                                        LIMIT 5000";
                $getPendingQtyResult = mysqli_query($conn, $getPendingQtyQuery);
                $qty = mysqli_fetch_array($getPendingQtyResult)[1];

                // update mc_hu
                if ($qty == 0) {
                    $updateMc_hu = "UPDATE mc_hu SET status = 1 WHERE d_hu = '$dhu'";
                    mysqli_query($conn, $updateMc_hu);
                }

                // // Insert into tlog
                // $query2 = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('INSERT INVENTORY TAG', '$tagno', CURRENT_TIMESTAMP, '$uid')";
                // $result2 = mysqli_query($conn, $query2);
            }
        } else {
            $response = "empty";
            $que = "empty";
        }
    }
}

$responseData = array(
    'status' => $response,
    'que' => $que,
    'qty' => $qty
);

echo json_encode($responseData);

mysqli_close($conn);
