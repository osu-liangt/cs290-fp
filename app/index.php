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
	<script type="text/javascript" src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
</head>
<body>
<div class="container">
<p><a href="?logout">Logout</a></p>
<?php
echo "<h2>Hello, $username</h2>\n";
?>
<p>To start, please enter in some details:</p>
<form id="new-sim" name="new-sim" method="POST">
	<div id="address" class="input-line">
		<label for="address-input">Full Address:</label>
		<input type="text" id="address-input" name="address">
		<div id="no-address" class="error">Please enter a valid full address</div>
	</div>
	<div id="annual-usage" class="input-line">
		<label for="annual-usage-input">Annual Usage (kWh):</label>
		<input type="number" id="annual-usage-input" name="annual-usage">
		<div id="no-annual-usage" class="error">Please enter a positive annual usage amount</div>
	</div>
	<div id="electric-rate" class="input-line">
		<label for="electric-rate-input">Electric Rate (cents/kWh):</label>
		<input type="number" id="electric-rate-input" name="electric-rate">
		<div id="no-electric-rate" class="error">Please enter a positive electric rate</div>
	</div>
	<div id="system-size" class="input-line">
		<label for="system-size-input">Desired System Size (kW):</label>
		<input type="number" id="system-size-input" name="system-size">
		<div id="no-system-size" class="error">Please enter a positive system size</div>
	</div>
	<input type="submit" id="run-simulation" value="Run Simulation">
</form>
<button id="load-sim">Load Simulation Results</button>
<div id="loaded-sims"></div>
<div id="simulation-results">
	<div id="old-bill" class="result"></div>
	<div id="new-bill" class="result"></div>
	<div id="savings" class="result"></div>
	<div id="oversized" class="result"></div>
	<button id="save-sim">Save Simulation Results</button>
	<p id="saved">Successfully saved. Click "Load Simulation Results" to refresh if desired.</p>
	<div id="acMonthlyLineChart"></div>
</div>
</div>
</body>
</html>