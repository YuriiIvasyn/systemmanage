<?php session_start();
$_SESSION["user_id"]=null;
header('Location: /beta/login.php');
exit;
?>