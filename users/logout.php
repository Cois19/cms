<?php
session_start();
$_SESSION['badge'] = "";
$_SESSION['login'] = "no";
session_destroy();

header("location:login.php");
?>