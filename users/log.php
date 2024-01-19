<?php
session_start();
include '../database/connect.php';

if (isset($_SESSION['login']) && $_SESSION['login'] == "yes") {
    header("location:../pages/delivery_order/index.php");
    exit();
}

$userid = $_POST['userid'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM tuser WHERE uid = '$userid' AND upass = '$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION['login'] = 'yes';
    $_SESSION['uid'] = $userid;
    $_SESSION['ucost'] = $row['ucost'];
    $_SESSION['uname'] = $row['uname'];
    $_SESSION['uemail'] = $row['uemail'];
    $_SESSION['utype'] = $row['utype'];
    $_SESSION['usection'] = $row['usection'];
    echo "success";
} else {
    echo "Invalid badge or password";
}

mysqli_close($conn);

?>