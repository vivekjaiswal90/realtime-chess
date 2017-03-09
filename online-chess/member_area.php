<?php
session_start();
include('db_config.inc.php');

if (!isset($_SESSION['username'])) {
    header("location: ./index.php");
    exit;
}

$username = $_SESSION['username'];

$displayStatusMssg = "Hello, {$_SESSION['username']}, Join an existing game or start a new game.";

if (isset($_POST['actCreateChessTbl'])) {

    $colorSide = sprintf('%d', $_POST['color']);
    $currentTimeStamp = time();
    
    //0 = random, 1 = white, 2 = black

    if ($colorSide == 0) {
        //if random is chosen, assign either white or black
        $colorSide = mt_rand(1, 2);
        
        mysql_query("INSERT INTO chess_table VALUES('$username','$username','$colorSide','$currentTimeStamp')") or die(mysql_error());
    } else {
        mysql_query("INSERT INTO chess_table VALUES('$username','$username','$colorSide','$currentTimeStamp')") or die(mysql_error());
    }
    
    $displayStatusMssg = "Chess Table Created";

}

include ('template/member_area.html');
?>
