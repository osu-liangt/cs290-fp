<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

session_start();

$username = $_POST["username"];
$password = $_POST["password"];

$_SESSION["username"] = $username;
$_SESSION["password"] = $password;

$response = array();

if (empty($username)) {
	$response["noUsername"] = true;
}

if (empty($password)) {
	$response["noPassword"] = true;
}

$taken = "george";

if ($username == $taken) {
	$response["usernameTaken"] = true;
}

if (strlen($password) < 12 || strlen($password) > 32) {
	$response["badPasswordLength"] = true;
}

echo json_encode($response);

?>