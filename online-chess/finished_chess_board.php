<?php
session_start();
include ('db_config.inc.php');

if (!isset($_SESSION['username'])) {
    header("location: ./index.php");
    exit;
}

$username = $_SESSION['username'];
$opponent = mysql_real_escape_string($_GET['opponent']);
$gid = mysql_real_escape_string($_GET['gid']);

//display PGN text string
$sql = mysql_query("SELECT hd,mv FROM pgn WHERE gameid='$gid' LIMIT 1") or die(mysql_error());
list($pgnHeader,$pgnStr) = mysql_fetch_row($sql);

//find opponent chess stats from database
$sql = mysql_query("SELECT sum(win) as oppWin,sum(loss) as oppLoss,sum(draw) as oppDraw FROM chess_stats WHERE username='$opponent'", $conn) or die(mysql_error());
$total = mysql_fetch_array($sql);

$oppWin = $total['oppWin'];
$oppLoss = $total['oppLoss'];
$oppDraw = $total['oppDraw'];
    
$oppWin = ($oppWin == null)?0:$oppWin;
$oppLoss = ($oppLoss == null)?0:$oppLoss;
$oppDraw = ($oppDraw == null)?0:$oppDraw;

include('template/show_finished_chess_board.html');	
?>