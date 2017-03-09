<?php
session_start();
include ('db_config.inc.php');
ob_start();

if (!isset($_SESSION['username'])) {
    header("location: ./index.php");
    exit;
}

$username = $_SESSION['username'];

//create and start chess table here
if (isset($_GET['act']) and $_GET['act'] == 'join_chess_table') {

    $host = mysql_real_escape_string($_GET['host']);
    $hostColor = sprintf('%d', $_GET['color']);
    $timeStamp = sprintf('%d', $_GET['time_stamp']);
    
    //can't play game hosted by member themself
    if($host == $username){
        echo "Can't Start Game Hosted By You!";exit;
    }
    
    //color of the side
    if ($hostColor == '1') {
        $whitePlayer = $host;
        $blackPlayer = $username;

        $hostTurn = 1;
        $urTurn = 0;
    } else {
        $whitePlayer = $username;
        $blackPlayer = $host;

        $hostTurn = 0;
        $urTurn = 1;
    }

    include ('./occ_mod/misc.php');
    function ioSaveGame($game, $gameID)
    {
        $hfile = fopen("./occ_mod/data/games/$gameID", 'w');

        fwrite($hfile, date('Y m d H i ', $game['ts_start']));
        fwrite($hfile, date('Y m d H i', time()));
        fwrite($hfile, "\n");

        fwrite($hfile, $game['white'] . ' ' . $game['black'] . ' ');
        fwrite($hfile, $game['curmove'] . ' ' . $game['curplyr'] . ' ');
        fwrite($hfile, $game['curstate'] . ' ' . $game['wcs'] . ' ' . $game['wcl'] . ' ');
        fwrite($hfile, $game['bcs'] . ' ' . $game['bcl'] . ' ' . $game['w2spm'] . ' ');
        fwrite($hfile, $game['b2spm'] . ' ' . $game['lastmove'] . ' ');
        fwrite($hfile, $game['lastkill'] . ' ' . $game['oscf'] . ' ');
        fwrite($hfile, $game['olcf'] . "\n");

        for ($i = 0; $i < 64; $i++)
            if ($game['board'][$i] != '' && $game['board'][$i][0] == 'w') {
                $c = i2bc($i);
                fwrite($hfile, $game['board'][$i][1] . $c . ' ');
            }
        fwrite($hfile, "\n");
        for ($i = 0; $i < 64; $i++)
            if ($game['board'][$i] != '' && $game['board'][$i][0] == 'b') {
                $c = i2bc($i);
                fwrite($hfile, $game['board'][$i][1] . $c . ' ');
            }
        fwrite($hfile, "\n");

        for ($i = 0, $j = 1; $i < count($game['mhistory']); $i += 2, $j++)
            fwrite($hfile, $j . ' ' . $game['mhistory'][$i] . ' ' . $game['mhistory'][$i + 1] .
                "\n");
        fclose($hfile);
    }
    function ioCreateGame($white, $black)
    {
        $game = array();
        $game['ts_start'] = time();
        $game['white'] = $white;
        $game['black'] = $black;
        $game['curmove'] = 0;
        $game['curplyr'] = 'w';
        $game['curstate'] = '?';
        $game['wcs'] = 1;
        $game['wcl'] = 1;
        $game['bcs'] = 1;
        $game['bcl'] = 1;
        $game['w2spm'] = 'x';
        $game['b2spm'] = 'x';
        $game['lastmove'] = 'x';
        $game['lastkill'] = 'x';
        $game['oscf'] = 'x';
        $game['olcf'] = 'x';
        $game['board'] = array('wR', 'wN', 'wB', 'wQ', 'wK', 'wB', 'wN', 'wR', 'wP',
            'wP', 'wP', 'wP', 'wP', 'wP', 'wP', 'wP', '', '', '', '', '', '', '', '', '', '',
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
            '', '', 'bP', 'bP', 'bP', 'bP', 'bP', 'bP', 'bP', 'bP', 'bR', 'bN', 'bB', 'bQ',
            'bK', 'bB', 'bN', 'bR');

        $gameFileName = sprintf('%s-%s-%s-', date('YmdHi', $game['ts_start']), $white, $black);
        ioSaveGame($game, $gameFileName);
        return $gameFileName;
    }

    $gameID = ioCreateGame($whitePlayer, $blackPlayer);

    //used to show ongoing games
    mysql_query("INSERT INTO ongoing_games VALUES
    ('$username','$host','$gameID','$host','$whitePlayer','$urTurn'),
    ('$host','$username','$gameID','$host','$whitePlayer','$hostTurn')") or die(mysql_error());
    
    //delete chess table
    mysql_query("DELETE FROM chess_table WHERE host='$host' and timestamp='$timeStamp'", $conn) or die(mysql_error());
    
    header("location: ./play_chess.php?act=play_chess&white=$whitePlayer&host=$host&gid=$gameID");exit;
    
}
//END create and start chess table

/*display opponent chess performance status, win, loss, draw...*/
//first find out opponent
$player = explode('-',$_GET['gid']);

$opponent = ($player[1] == $username)?$player[2]:$player[1];

//find opponent chess stats from database
$sql = mysql_query("SELECT sum(win) as oppWin,sum(loss) as oppLoss,sum(draw) as oppDraw FROM chess_stats WHERE username='$opponent'", $conn) or die(mysql_error());
$total = mysql_fetch_array($sql);

$oppWin = $total['oppWin'];
$oppLoss = $total['oppLoss'];
$oppDraw = $total['oppDraw'];
    
$oppWin = ($oppWin == null)?0:$oppWin;
$oppLoss = ($oppLoss == null)?0:$oppLoss;
$oppDraw = ($oppDraw == null)?0:$oppDraw;

include('template/show_chess_board.html');
ob_end_flush();
?>