<?php
session_start();
include '../database/connect.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == "yes") {
    header("location:../ecd.php");
    exit();
}

$badge = $_POST['badge'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM d_user WHERE userid = '$badge' AND upasswd = '$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION['login'] = 'yes';
    $_SESSION['badge'] = $badge;
    $_SESSION['coid'] = $row['ucost'];
    $_SESSION['username'] = $row['uname'];
    $_SESSION['email'] = $row['uemail'];
    echo "success";
} else {
    echo "Invalid badge or password";
}

mysqli_close($conn);

?>