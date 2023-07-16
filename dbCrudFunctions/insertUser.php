<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$insert_status = '';

$userid = $_POST['userid'];
$upassword = md5($_POST['upassword']);
$username = $_POST['username'];
$usercost = $_POST['usercost'];
$useremail = $_POST['useremail'];
$usertype = $_POST['usertype'];
$remarks = $_POST['remarks'];

$query9 = "INSERT INTO tuser(uid, upass, uname, ucost, uemail, utype, remarks, ustatus, cp, cd)
            VALUES('$userid', '$upassword', '$username', '$usercost', '$useremail', '$usertype', '$remarks', 1, CURRENT_TIMESTAMP, '$uid')";
$result9 = mysqli_query($conn, $query9);

if ($result9) {
    $insert_status = 'success';

    // Insert into tlog
    $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('ADD USER', '$userid', CURRENT_TIMESTAMP, '$uid')";
    $result = mysqli_query($conn, $query);
} else {
    $insert_status = 'fail'; // Default value when an error occurs
}

echo $insert_status;

mysqli_close($conn);
?>