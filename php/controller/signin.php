<?php

require_once '../API/Authenticator.php';

$username = $_POST["username"];
$password = $_POST["password"];

$auth = new Authenticator();
$login_success = $auth->SignIn($username, $password);
if ($login_success === FALSE) {
    session_start();
    $_SESSION['user_logged'] = true;
    echo json_encode(true);
} else {
    echo json_encode(false);
}
?>