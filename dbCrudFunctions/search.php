<?php
session_start();
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

// if ($_SESSION['islogin'] != "Yes") {
//     header("location:../users/login.php");
//     exit();
// }

if (
    isset($_POST['modelView']) && isset($_POST['sixtypartnumberView']) && isset($_POST['partnumberView'])
) {
    $modelView = $_POST['modelView'];
    $sixtypartnumberView = $_POST['sixtypartnumberView'];
    $partnumberView = $_POST['partnumberView'];

    // First JSON response
    $query = "SELECT ecd.*, ecd_details.* FROM ecd JOIN ecd_details on ecd.ecd_id = ecd_details.ecd_id WHERE model='$modelView' AND ecd.sixtypartnumber='$sixtypartnumberView' AND ecd_details.partnumber='$partnumberView' ORDER BY approvaldate DESC, ecd.ecd_id DESC LIMIT 1";
    if ($select_result = mysqli_query($conn, $query)) {
        $response1 = array();
        while ($row = mysqli_fetch_assoc($select_result)) {
            $response1 = $row;
        }
        if (empty($response1)) {
            $response1 = "null";
        }
    } else {
        $response1['status'] = 500;
        $response1['message'] = 'Error: ' . mysqli_error($conn);
    }

    // Second JSON response
    $query2 = "SELECT ecd.*, ecd_details.* FROM ecd JOIN ecd_details on ecd.ecd_id = ecd_details.ecd_id WHERE model='$modelView' AND ecd.sixtypartnumber='$sixtypartnumberView' AND ecd_details.partnumber='$partnumberView' ORDER BY approvaldate DESC, ecd.ecd_id DESC LIMIT 1 OFFSET 1";
    if ($select_result2 = mysqli_query($conn, $query2)) {
        $response2 = array();
        while ($row = mysqli_fetch_assoc($select_result2)) {
            $response2 = $row;
        }
        if (empty($response2)) {
        }
    } else {
        $response2['status'] = 500;
        $response2['message'] = 'Error: ' . mysqli_error($conn);
    }

    // Combine responses into an array
    $response = array($response1, $response2);

    // Send combined responses as JSON
    echo json_encode($response);
} else {
    $response['status'] = 400;
    $response['message'] = 'Error: ' . mysqli_error($conn);
    echo json_encode($response);
}

mysqli_close($conn);
?>