<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html');

session_save_path('/nfs/stak/students/l/liangt/php_sessions');
session_start();
if (isset($_SESSION["username"])) {
	header('Location: /~liangt/290/fp/app');
}
$_SESSION["mainVisited"] = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Simple Solar Savings</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="main.js"></script>
</head>
<body>
	<div class="container">
		<div id="hero">
			<h1>Simple Solar Savings</h1>
			<h2>Calculate your solar savings quickly and easily!</h2>
		</div>
		<div id="login">
			<h3>Please log in or register:</h3>
			<form id="login-form" name="login-form" method="POST">
					<div id="username" class="input-line">
						<label for="username-input">Username:</label>
						<input type="text" id="username-input" name="username">
						<div id="no-username" class="error">Please enter a username</div>
					</div>
					<div id="password" class="input-line">
						<label for="password-input">Password:</label>
						<input type="password" id="password-input" name="password">
						<div id="no-password" class="error">Please enter a password</div>
						<div id="bad-password-length" class="error">Password must be between 12 and 32 characters</div>
						<div id="wrong-pass-taken-username" class="error">Wrong password, or if you're trying to register, that username is already taken</div>
					</div>
					<input type="submit" id="submit" value="Submit">
			</form>
		</div>
	</div>
</body>
</html>