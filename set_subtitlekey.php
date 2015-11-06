<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['key_subtitle']=$_GET['key'];
?>