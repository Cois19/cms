<?php
session_start();
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

// if ($_SESSION['islogin'] != "Yes") {
//     header("location:../users/login.php");
//     exit();
// }

if (isset($_POST['sixtypartnumber'])) {
    $sixtypartnumber = $_POST['sixtypartnumber'];

    $data = '';

    // $query1 = "SELECT id FROM ecd WHERE sixtypartnumber = '$sixtypartnumber'";
    // $query1_result = mysqli_query($conn, $query1);
    // $id = mysqli_fetch_array($query1_result)[0];

    $query = "SELECT DISTINCT partnumber FROM ecd_details WHERE sixtypartnumber = '$sixtypartnumber' ORDER BY partnumber";
    if ($select_result = mysqli_query($conn, $query)) {
        $data .= "<option disabled selected>Select Part Number</option>";
        while ($row = mysqli_fetch_assoc($select_result)) {
            $data .= '<option value="' . $row["partnumber"] . '">
                        ' . $row["partnumber"] . '
                    </option>';
        }
    }
}

echo $data;
mysqli_close($conn);
?>