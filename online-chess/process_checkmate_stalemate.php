<?php
session_start();
//Game end. Record PGN into database, as well as wins, losses or draws 
$currentTimeStamp = time();
$username = $uid;

//record PGN
include('./occ_mod/pgnformat.php');

mysql_query("INSERT INTO pgn VALUES('$username','$opponent','$pgnStrHeader','$pgnStr','$gid'),('$opponent','$username','$pgnStrHeader','$pgnStr','$gid')", $conn) or die(mysql_error());
//END record PGN

//wins, losses or draws 
if($checkMate){
    mysql_query("INSERT INTO chess_stats VALUES('$username',1,0,0),('$opponent',0,1,0)", $conn) or die(mysql_error());
}

if($staleMate){
    mysql_query("INSERT INTO chess_stats VALUES('$username',0,0,1),('$opponent',0,0,1)", $conn) or die(mysql_error());
}
   
mysql_query("DELETE FROM ongoing_games WHERE gameid='$gid'", $conn) or die(mysql_error());
?>