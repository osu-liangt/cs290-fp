<?php
session_start();
$username = $_SESSION["username"];
$password = $_SESSION["password"];

// If username and password are new, add it to the list
?>