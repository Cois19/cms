<?php
session_start();
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

// if ($_SESSION['islogin'] != "Yes") {
//     header("location:../users/login.php");
//     exit();
// }

$count = 1;
$output = '';
while (true) {
    $count++;
    $output .= '<div class="form-group mb-3">
                    <label for="partnumber' . $count . '">Part Number ' . $count . '</label>
                    <input type="text" class="form-control" id="partnumber' . $count . '" placeholder="Part Number ' . $count . '">
                </div>';
    break;
}

echo $output;
mysqli_close($conn);
?>