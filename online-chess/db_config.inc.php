<?php
$dbName = "multiplayer_chess";

$dbUsername = "root";

$dbPassword = "mdrg";

$dbHostName = "localhost";

$conn = mysql_connect($dbHostName,$dbUsername,$dbPassword);mysql_select_db($dbName,$conn) or die('Failed to select database');	
?>
