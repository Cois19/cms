<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$doId = $_POST['doId'];

// Get qty count
$query9 = "SELECT tqty, (tqty - COUNT(*)) AS qtyCount FROM tdoc JOIN tisn ON tdoc.tdono = tisn.tdono WHERE tdoc.que = $doId AND tisn.tstatus = 1 GROUP BY tisn.tdono";
$result9 = mysqli_query($conn, $query9);

if ($result9) {
    if (mysqli_num_rows($result9) > 0) {
        $row9 = mysqli_fetch_assoc($result9);
        $qtyCount = $row9['qtyCount'];
        echo $qtyCount;
    } else {
        // No rows found in tisn table, return the value of tqty instead
        $query10 = "SELECT tqty FROM tdoc WHERE que = $doId";
        $result10 = mysqli_query($conn, $query10);
        $row10 = mysqli_fetch_assoc($result10);
        $qtyCount = $row10['tqty'];
        echo $qtyCount;
    }
} else {
    echo "0"; // Default value when an error occurs
}


mysqli_close($conn);
?>