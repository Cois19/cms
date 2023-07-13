<?php
session_start();

if(!isset($_SESSION['login'])) {
    header("Location: /eng_checksum_data/users/login.php");
}

$ubadge = $_SESSION['badge'];
$ucoid = $_SESSION['coid'];
$username = $_SESSION['username'];
$uemail = $_SESSION['email'];

$login = $_SESSION['login'];
if ($login != 'yes' || !isset($ubadge)) {
    header("Location: /eng_checksum_data/users/login.php");
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset(); // unset $_SESSION variable for the run-time 
    session_destroy(); // destroy session data in storage
    header("Location: /eng_checksum_data/users/login.php");
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>