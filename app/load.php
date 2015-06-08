<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

session_start();

if (!isset($_SESSION["username"])) {
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

if (!($loadSims = $mysqli->prepare(
	"SELECT address, annual_usage, rate, size, oldbill, newbill, savings
	FROM sims
	WHERE author = ?
	ORDER BY id ASC"))) {
	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$loadSims->bind_param("s", $username)) {
	echo "Binding parameters failed: (" . $loadSims->errno . ") " .
		$loadSims->error;
}
if (!$loadSims->execute()) {
     echo "Execute failed: (" . $loadSims->errno . ") " .
     	$loadSims->error;
}
if (!($loadSims->bind_result(
	$address, $usage, $rate, $size, $oldbill, $newbill, $savings))) {
    echo "Binding results failed: (" . $loadSims->errno . ") " .
    	$loadSims->error;
}

$index = 0;

while($loadSims->fetch()) {
	$response[$index] = new stdClass();
	$response[$index]->address = $address;
	$response[$index]->usage = $usage;
	$response[$index]->rate = $rate;
	$response[$index]->sysSize = $size; //.size is a method in JS
	$response[$index]->oldbill = $oldbill;
	$response[$index]->newbill = $newbill;
	$response[$index]->savings = $savings;
	$index++;
}

$response["numSims"] = $index;

echo json_encode($response);
?>