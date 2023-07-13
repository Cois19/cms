<?php
session_start();
$_SESSION['uid'] = "";
$_SESSION['login'] = "no";
session_destroy();

header("location:login.php");
?>