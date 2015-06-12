<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

session_save_path('/nfs/stak/students/l/liangt/php_sessions');
session_start();

if (!isset($_SESSION["username"]) || count($_POST) == 0) {
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

$username = $_SESSION["username"];
$address = $_POST["address"];
$usage = $_POST["annual-usage"];
$rate = $_POST["electric-rate"];
$size = $_POST["system-size"];
$oldbill = $_POST["oldbill"];
$newbill = $_POST["newbill"];
$savings = $_POST["savings"];

if (!($addSim = $mysqli->prepare(
	"INSERT INTO sims(author, address, annual_usage, rate, size, oldbill, newbill, savings)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?)"))) {
	    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$addSim->bind_param("ssdddddd", $username, $address, $usage, $rate, $size, $oldbill, $newbill, $savings)) {
	echo "Binding parameters failed: (" . $addSim->errno . ") " .
		$addSim->error;
}
if (!$addSim->execute()) {
	echo "Execute failed: (" . $addSim->errno . ") " .
		$addSim->error;
}
$addSim->close();

$response["success"] = true;
echo json_encode($response);
?>