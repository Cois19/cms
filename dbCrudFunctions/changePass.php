<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$update_status = '';

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

echo $update_status;

mysqli_close($conn);
?>