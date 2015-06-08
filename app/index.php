<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html');

session_start();

if (isset($_GET["logout"])) {
	session_destroy();
	header('Location: /~liangt/290/fp');
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Simple Solar App</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="main.js"></script>
</head>
<body>
<div class="container">
<?php
echo "<p>Hello, $username</p>\n";
?>
<p>To start, please enter in some details:</p>
<form id="new-sim" name="new-sim" method="POST">
	<div id="address" class="input-line">
		<label for="address-input">Full Address:</label>
		<input type="text" id="address-input" name="address">
		<div id="no-address" class="error">Please enter a full address</div>
	</div>
	<div id="annual-usage" class="input-line">
		<label for="annual-usage-input">Annual Usage (kWh):</label>
		<input type="number" id="annual-usage-input" name="annual-usage">
		<div id="no-annual-usage" class="error">Please enter an annual usage amount</div>
		<div id="neg-annual-usage" class="error">Annual usage cannot be negative</div>
	</div>
	<div id="electric-rate" class="input-line">
		<label for="electric-rate-input">Electric Rate (cents/kWh):</label>
		<input type="number" id="electric-rate-input" name="electric-rate">
		<div id="no-electric-rate" class="error">Please enter an electric rate</div>
		<div id="neg-electric-rate" class="error">Electric rate cannot be negative</div>
	</div>
	<div id="system-size" class="input-line">
		<label for="system-size-input">System Size (kW):</label>
		<input type="number" id="system-size-input" name="system-size">
		<div id="no-system-size" class="error">Please enter a system size</div>
		<div id="neg-system-size" class="error">System size cannot be negative</div>
	</div>
	<input type="submit" id="run-simulation" value="Run Simulation">
</form>
<div id="simulation"></div>
<p><a href="?logout">Logout</a></p>
</div>
</body>
</html>