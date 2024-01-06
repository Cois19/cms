<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$mode = $_POST['mode'];

if ($mode == 'rules') {
    include '../../../users/session.php';
    $query2 = "SELECT que, model, type, ruleseparator, labelrules, remarks, cd FROM tlabelrules ORDER BY que DESC";

    if ($select_result2 = mysqli_query($conn, $query2)) {
        $response1 = array();
        while ($row = mysqli_fetch_assoc($select_result2)) {
            $response1[] = array_values($row);
        }
        if (empty($response1)) {
            $response1 = null;
        }
        $response = array(
            'data' => $response1
        );
    } else {
        $response = array(
            'status' => 500,
            'message' => 'Error: ' . mysqli_error($conn)
        );
    }
} else if ($mode == 'label') {
    include '../../../users/session.php';
    
    $model = $_POST['model'];
    $query2 = "SELECT que, model, type, value, cd FROM tlabel WHERE model = '$model'";
    // echo $query2;

    if ($select_result2 = mysqli_query($conn, $query2)) {
        $response1 = array();
        while ($row = mysqli_fetch_assoc($select_result2)) {
            $response1[] = array_values($row);
        }
        if (empty($response1)) {
            $response1 = null;
        }
        $response = array(
            'data' => $response1
        );
    } else {
        $response = array(
            'status' => 500,
            'message' => 'Error: ' . mysqli_error($conn)
        );
    }
}

echo json_encode($response);

mysqli_close($conn);
?>