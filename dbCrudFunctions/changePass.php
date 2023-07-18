<?php
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$update_status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $update_status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../users/session.php';
    $oldPassword = md5($_POST['upasswordOld']);
    $newPassword = md5($_POST['upasswordNew']);

    // check if old password match
    $query9 = "SELECT upass FROM tuser WHERE uid = '$uid'";
    $result9 = mysqli_query($conn, $query9);
    $row9 = mysqli_fetch_assoc($result9);

    if ($row9['upass'] == $oldPassword) {
        // update password
        $query2 = "UPDATE tuser SET upass = '$newPassword', lup = '$uid', lud = CURRENT_TIMESTAMP WHERE uid = '$uid'";
        $result2 = mysqli_query($conn, $query2);

        // Insert into tlog
        $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('CHANGE PASSWORD', '$uid', CURRENT_TIMESTAMP, '$uid')";
        $result = mysqli_query($conn, $query);

        $update_status = 'success';
    } else {
        $update_status = "fail";
    }
}

echo $update_status;

mysqli_close($conn);
?>