<?php
include '../../../database/connect.php';
include '../../../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$doId = $_POST['doId'];
$response = array();

// Get remaining qty count
$query9 = "SELECT (tqty - COUNT(*)) AS remainingQty, (tqty - (tqty - COUNT(tisn.tdono))) AS scannedQty FROM tdoc JOIN tisn ON tdoc.que = tisn.tdoc_que WHERE tdoc.que = $doId AND tisn.tstatus = 1 GROUP BY tisn.tdono";
$result9 = mysqli_query($conn, $query9);

if ($result9) {
    if (mysqli_num_rows($result9) > 0) {
        $row9 = mysqli_fetch_assoc($result9);
        $remainingQty = $row9['remainingQty'];
        $scannedQty = $row9['scannedQty'];
        $response['remainingQty'] = $remainingQty;
        $response['scannedQty'] = $scannedQty;
    } else {
        // No rows found in tisn table, return the value of tqty instead
        $query10 = "SELECT tqty FROM tdoc WHERE que = $doId";
        $result10 = mysqli_query($conn, $query10);
        $row10 = mysqli_fetch_assoc($result10);
        $remainingQty = $row10['tqty'];
        $response['remainingQty'] = $remainingQty;
        $response['scannedQty'] = 0; // Default value for scannedQty
    }
} else {
    $response['remainingQty'] = 0; // Default value for qtyCount
    $response['scannedQty'] = 0; // Default value for scannedQty
}

mysqli_close($conn);

echo json_encode($response);
?>
