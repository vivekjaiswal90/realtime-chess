<?php
session_start();
include ('db_config.inc.php');

$loginUsername = mysql_real_escape_string($_POST['username']);

$sql = mysql_query("SELECT id,username FROM membership WHERE username='$loginUsername' ") or die(mysql_error());

list($memberID) = mysql_fetch_row($sql);

//if valid, logged user and assign a session variable to track member

if ($memberID >= 1) {

    $_SESSION['username'] = $loginUsername;
    header("location: ./member_area.php");
    //echo "Hello $loginUsername, please <a href=\"./member_area.php\">CLICK HERE</a> to continue";

} else {
    
    $displayStatusMssg = "Please register yourself first.";
    include('template/home.html');

}

?>
