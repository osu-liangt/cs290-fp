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

// TODO: HASH AND MAYBE SALT PASSWORD

$response = array();

$badPassword = false;

if (empty($password)) {
	$response["noPassword"] = true;
	$badPassword = true;
}

if (strlen($password) < 12 || strlen($password) > 32) {
	$response["badPasswordLength"] = true;
	$badPassword = true;
}

if (empty($username)) {
	$response["noUsername"] = true;
}
else {
	// Check if username already there
	//if so, check if password is good
		//if so, return a ok response
	//if not, wrong password or username already taken
	// If username not there, add if password is good

	// Prepare statement
	if (!($checkUsername = $mysqli->prepare(
		"SELECT username, password
		FROM users
		WHERE username = ?"))) {
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	// Bind parameters
	if (!$checkUsername->bind_param("s", $username)) {
  	echo "Binding parameters failed: (" . $checkUsername->errno . ") " .
  		$checkUsername->error;
	}

	// Execute statement
	if (!$checkUsername->execute()) {
   	echo "Execute failed: (" . $checkUsername->errno . ") " .
   		$checkUsername->error;
	}

	// Bind result
	if (!($checkUsername->bind_result(
		$existing_username, $existing_password))) {
	    echo "Binding results failed: (" . $checkUsername->errno . ") " .
	    	$checkUsername->error;
	}

	if ($checkUsername->fetch()) {
		//username exists
		if ($password == $existing_password) {
			$response["goodInputs"] = true;
			$_SESSION["username"] = $username;
		}
		else {
			$response["wrongOrTaken"] = true;
		}
	}
	else {
		//username not taken
		if (!$badPassword) {
			// Add to database
			if (!($addUser = $mysqli->prepare(
				"INSERT INTO users(username, password)
				VALUES (?, ?)"))) {
				    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			if (!$addUser->bind_param("ss", $username, $password)) {
		  	echo "Binding parameters failed: (" . $addUser->errno . ") " .
		  		$addUser->error;
			}
			if (!$addUser->execute()) {
	    	echo "Execute failed: (" . $addUser->errno . ") " .
	    		$addUser->error;
			}
			$addUser->close();
			$response["goodInputs"] = true;
			$_SESSION["username"] = $username;
		}
	}
	$checkUsername->close();
}

echo json_encode($response);

?>