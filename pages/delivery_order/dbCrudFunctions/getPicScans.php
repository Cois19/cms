<?php
include '../../../database/connect.php';
include '../../../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$query = "SELECT count(tisn_sum.cp) AS total_scan, tisn_sum.cp, tuser.uname FROM tisn_sum JOIN tuser ON tisn_sum.cp = tuser.uid WHERE DATE(tisn_sum.cd) = CURDATE() GROUP BY tisn_sum.cp ORDER BY total_scan DESC";
$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

mysqli_close($conn);
?>