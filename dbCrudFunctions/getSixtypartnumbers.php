<?php
session_start();
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

// if ($_SESSION['islogin'] != "Yes") {
//     header("location:../users/login.php");
//     exit();
// }

if (isset($_POST['model'])) {
    $model = $_POST['model'];

    $data = '';

    $query = "SELECT DISTINCT sixtypartnumber FROM ecd WHERE model = '$model' ORDER BY sixtypartnumber";
    if ($select_result = mysqli_query($conn, $query)) {
        $data .= "<option disabled selected>Select 60 Main Part Number</option>";
        while ($row = mysqli_fetch_assoc($select_result)) {
            $data .= '<option value="' . $row["sixtypartnumber"] . '">
                        ' . $row["sixtypartnumber"] . '
                    </option>';
        }
    }
}

echo $data;
mysqli_close($conn);
?>