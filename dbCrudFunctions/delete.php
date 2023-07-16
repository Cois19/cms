<?php
include '../database/connect.php';
include '../users/session.php';
date_default_timezone_set("Asia/Jakarta");

$tdono = $_POST['tdono'];
$reset_status = '';

if ($utype != 1) {
    $reset_status = 'unauthorized';
} else {
    $query9 = "UPDATE tdoc SET tstatus = 0, lup = '$uid', lud = CURRENT_TIMESTAMP WHERE tdono = '$tdono'";
    $result9 = mysqli_query($conn, $query9);

    if ($result9) {
        $reset_status = 'success';

        // Insert into tlog
        $query = "INSERT INTO tlog(tprocess, tdata, cd, cp) VALUES('DELETE DO', '$tdono', CURRENT_TIMESTAMP, '$uid')";
        $result = mysqli_query($conn, $query);

    } else {
        $reset_status = 'fail'; // Default value when an error occurs
    }
}

echo $reset_status;

mysqli_close($conn);
?>