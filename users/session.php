<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: /vsite/cms/users/login.php");
    exit();
}

$uid = $_SESSION['uid'];
$ucost = $_SESSION['ucost'];
$uname = $_SESSION['uname'];
$uemail = $_SESSION['uemail'];
$utype = $_SESSION['utype'];
$usection = $_SESSION['usection'];

$login = $_SESSION['login'];
if ($login != 'yes' || !isset($uid)) {
    header("Location: /vsite/cms/users/login.php");
    exit();
}

$inactive_timeout = 900; // 15 minutes in seconds
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive_timeout) {
    session_unset(); // Unset all session variables if needed
    session_destroy(); // Destroy the session if needed
    header("Location: /vsite/cms/users/login.php"); // Redirect the user to your_page.php
    exit(); // Stop further execution of the script
}

$_SESSION['last_activity'] = time();

?>