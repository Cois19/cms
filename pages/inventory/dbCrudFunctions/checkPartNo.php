<?php
include '../../../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../../../users/session.php';
    $partNo = $_POST['partNo'];

    $query = "SELECT partno FROM tpartmaster WHERE partno = '$partNo'";
    $select_result = mysqli_query($conn, $query);
    if (mysqli_num_rows($select_result) > 0) {
        $status = 'fail';
    } else {
        $status = 'success';
    }
}

$responseData = array(
    'status' => $status
);

echo json_encode($responseData);

mysqli_close($conn);
?>