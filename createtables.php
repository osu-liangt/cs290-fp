<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/plain');

include 'secure.php';

$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'liangt-db';
$dbuser = 'liangt-db';
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " .
		$mysqli->connect_error;
}

if (!$mysqli->query("DROP TABLE IF EXISTS users") ||
	!$mysqli->query(
		"CREATE TABLE users(
			id INT PRIMARY KEY AUTO_INCREMENT,
			username VARCHAR(255) NOT NULL UNIQUE,
			password VARCHAR(255) NOT NULL
		)")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$mysqli->query("DROP TABLE IF EXISTS sims") ||
	!$mysqli->query(
		"CREATE TABLE sims(
			id INT PRIMARY KEY AUTO_INCREMENT,
			author VARCHAR(255) NOT NULL,
			address VARCHAR(255) NOT NULL,
			annual_usage DOUBLE(5,2) NOT NULL,
			rate DOUBLE(5,2) NOT NULL,
			size DOUBLE(5,2) NOT NULL
		)")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

?>