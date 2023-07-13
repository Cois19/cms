<?php
session_start();

if(!isset($_SESSION['login'])) {
    header("Location: /store_scanner/users/login.php");
}

$uid = $_SESSION['uid'];
$ucost = $_SESSION['ucost'];
$uname = $_SESSION['uname'];
$uemail = $_SESSION['uemail'];

$login = $_SESSION['login'];
if ($login != 'yes' || !isset($uid)) {
    header("Location: /store_scanner/users/login.php");
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset(); // unset $_SESSION variable for the run-time 
    session_destroy(); // destroy session data in storage
    header("Location: /store_scanner/users/login.php");
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>