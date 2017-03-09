<?php
include('db_config.inc.php');

$username = mysql_real_escape_string($_POST['newUsername']);
$password = md5($_POST['newPwrd']);	

mysql_query("INSERT INTO membership VALUES('','$username','$password')") or die();

$displayStatusMssg = "New Account Created. Please Login To Start Play";
include('template/home.html');
?>