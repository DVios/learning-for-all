<?php

require_once '../API/Authenticator.php';
require_once '../API/User.php';

$user = new User();

$user->setUsername($_POST["username"]);
$user->setPassword($_POST["password"]);
$user->setFirst_name($_POST["fname"]);
isset($_POST["mname"]) ? $user->setMiddle_name($_POST["mname"]) : $user->setMiddle_name("");
$user->setLast_name($_POST["lname"]);
$user->setDob($_POST["dob"]);
$user->setGender($_POST["gender"]);
isset($_POST["about"]) ? $user->setAbout($_POST["about"]) : $user->setAbout("");

$auth = new Authenticator();
$signup_success = $auth->signUp($user);
if ($signup_success) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}
?>