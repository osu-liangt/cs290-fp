<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

session_start();

if (!isset($_SESSION["mainVisited"]) || count($_POST) == 0) {
	header('Location: /~liangt/290/fp');
}

include 'secure.php';

$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'liangt-db';
$dbuser = 'liangt-db';
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " .
		$mysqli->connect_error;
}

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

// Check if username already there
	//if so, check if password is good
		//if so, return a ok response
	//if not, wrong password or username already taken


$taken = "george";

if ($username == $taken) {
	$response["wrongOrTaken"] = true;
}

if (strlen($password) < 12 || strlen($password) > 32) {
	$response["badPasswordLength"] = true;
}

echo json_encode($response);

?>