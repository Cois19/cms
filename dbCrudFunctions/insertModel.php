<?php
session_start();
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

// if ($_SESSION['islogin'] != "Yes") {
//     header("location:../users/login.php");
//     exit();
// }

$insert_result = "";

if (
    isset($_POST['model']) && isset($_POST['costcenter']) &&
    isset($_POST['remarks'])
) {
    $model = filter_var($_POST['model'], FILTER_SANITIZE_STRING);
    $costcenter = filter_var($_POST['costcenter'], FILTER_SANITIZE_STRING);
    $remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);

    // Check if the same data already exists in model table
    $query = "SELECT * FROM d_model WHERE mname = ? AND coid = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $model, $costcenter);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Data already exists, show alert and do not execute INSERT query
        $insert_result = "Data already exists!";
    } else {
        $query1 = "INSERT INTO d_model(mname, coid, remarks) 
            VALUES(?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query1);
        mysqli_stmt_bind_param($stmt, 'sss', $model, $costcenter, $remarks);
        if (!mysqli_stmt_execute($stmt)) {
            error_log("Error in insert query: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
    }
}

echo json_encode(array('result' => $insert_result));
mysqli_close($conn);
?>