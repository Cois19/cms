<?php
include '../database/connect.php';
date_default_timezone_set("Asia/Jakarta");

$insert_status = '';

session_start();
$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed

    $insert_status = 'timeout'; // Set response as "timeout" for session timeout
} else {
    include '../users/session.php';
    $userid = $_POST['userid'];
    $upassword = md5($_POST['upassword']);
    $username = $_POST['username'];
    $usercost = $_POST['usercost'];
    $useremail = $_POST['useremail'];
    $usertype = $_POST['usertype'];
    $remarks = $_POST['remarks'];

    // check if user already exists
    $query2 = "SELECT uid FROM tuser WHERE uid = '$uid'";
    $result2 = mysqli_query($conn, $query2);

    if (mysqli_num_rows($result2) > 0) {
        $insert_status = 'duplicate';
    } else {
        $query9 = "INSERT INTO tuser(uid, upass, uname, ucost, uemail, utype, remarks, ustatus, cd, cp)
            VALUES('$userid', '$upassword', \"$username\", '$usercost', \"$useremail\", '$usertype', '$remarks', 1, CURRENT_TIMESTAMP, '$uid')";
        $result9 = mysqli_query($conn, $query9);

        if ($result9) {
            $insert_status = 'success';

            // Insert into tlog
            $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('ADD USER', '$userid', CURRENT_TIMESTAMP, '$uid')";
            $result = mysqli_query($conn, $query);
        } else {
            $insert_status = 'fail'; // Default value when an error occurs
        }
    }
}

echo $insert_status;

mysqli_close($conn);
?>