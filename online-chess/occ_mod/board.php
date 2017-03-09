<?php
session_start();
include ('../db_config.inc.php');

$uid = $_SESSION['username'];
$gid = mysql_real_escape_string($_GET['gid']);
$host = mysql_real_escape_string($_GET['host']);
$opponent = mysql_real_escape_string($_GET['opponent']);

$whitePlayer = mysql_real_escape_string($_GET['wp']);
//find black player
$blackPlayer = ($whitePlayer == $_SESSION['username']) ? $opponent : $_SESSION['username'];

$currentTimeStamp = time();

/* Check game id */
/*if (isset($_POST['gid']))
$gid = $_POST['gid'];
else
if (isset($_GET['gid']))
$gid = $_GET['gid'];
if (preg_match('/[^\w\-\.]/', $gid))
$gid = null;*/


/* Browser variables */
$browse = 0;
$rotate = 0;
if (isset($_GET['browse']))
    $browse = $_GET['browse'];
if (isset($_GET['rotate']))
    $rotate = $_GET['rotate'];

/* Includes */
include 'misc.php';
include 'io.php';
include 'render.php';
include 'chess.php';

/* Check posted command. The POST name is always 'cmd' in any formular.*/

if (!$browse && !empty($_POST['cmd'])) {
    include ('../db_config.inc.php');

    //$gid = inputFilter($_POST['gid']);
    $currentTimeStamp = time();
    $cmd = $_POST['cmd'];
    $cmdres = '';

    //$sql = mysql_query("SELECT gameid FROM chess_offer_draw WHERE gameid='$gid'") or die(mysql_error());
    //if (mysql_num_rows($sql) >= 1)exit('pending draw acceptance');

    ioLock();
    $cmdres = handleMove($gid, $uid, $cmd);
    ioUnlock();

    if (!strstr($cmdres, 'ERROR')) {
        mysql_query("UPDATE ongoing_games SET urturn=1 WHERE gameid='$gid'") or die(mysql_error
            ());
        mysql_query("UPDATE ongoing_games SET urturn=0 WHERE username='{$_SESSION['username']}' AND gameid='$gid' LIMIT 1") or
            die(mysql_error());
    }

    if (strstr($cmdres, 'CHECKMATE')) {
        //record PGN
        include ('pgnformat.php');

        mysql_query("INSERT INTO pgn VALUES('{$_SESSION['username']}','$opponent','$pgnStrHeader','$pgnStr','$gid'),('$opponent','{$_SESSION['username']}','$pgnStrHeader','$pgnStr','$gid')", $conn) or die(mysql_error());
        //END record PGN
        
        mysql_query("INSERT INTO chess_stats VALUES('$username',1,0,0),('$opponent',0,1,0)", $conn) or die(mysql_error());
        mysql_query("DELETE FROM ongoing_games WHERE gameid='$gid'", $conn) or die(mysql_error());
    }
    if (strstr($cmdres, 'STALEMATE')) {
        //record PGN
        include ('pgnformat.php');

        mysql_query("INSERT INTO pgn VALUES('$username','$opponent','$pgnStrHeader','$pgnStr','$gid'),('$opponent','$username','$pgnStrHeader','$pgnStr','$gid')", $conn) or die(mysql_error());
        //END record PGN
        
        mysql_query("INSERT INTO chess_stats VALUES('$username',0,0,1),('$opponent',0,0,1)", $conn) or die(mysql_error());
        mysql_query("DELETE FROM ongoing_games WHERE gameid='$gid'", $conn) or die(mysql_error());
    }
}

/* Load game */
$game = ioLoadGame($gid, $uid);

/* Force browsing mode for archived games.
if ($game['archived'] && !$browse) {
$browse = 1;
$rotate = 0;
}
*/
/* Get mode depending javascript */
if ($browse) {
    if ($uid == $game['white'])
        $pcolor = 'w';
    else
        $pcolor = 'b';
    if ($rotate) {
        if ($pcolor == 'w')
            $pcolor = 'b';
        else
            $pcolor = 'w';
    }
    include 'browser.js';
} else {
    include 'board.js';
}


/* Build page */
$links = array();
$links['Overview'] = 'index.php';
if ($browse) {
    if (!$game['archived'])
        $links['Input Mode'] = 'board.php?gid=' . $gid;
    $links['Rotate Board'] = 'board.php?gid=' . $gid . '&browse=1&rotate=' . $rotate;
} else {
    if ($game['curmove'] > 0)
        $links['Browsing Mode'] = 'board.php?gid=' . $gid . '&browse=1&rotate=1';
}


renderPageBegin('boardPageTable');

echo '<TABLE width="345px" cellspacing=0 cellpadding=0><TR><TD valign="top">';
if ($browse)
    renderBoard(null, $pcolor, null);
else
    renderBoard($game['board'], $game['p_color'], $game['p_maymove'], 0);

echo '<IMG src="./chessset/spacer.gif" width=10></TD>';
//echo '<TD rowspan=2><IMG width=10 alt="" src="../../media/img/chessset/spacer.gif">SSS</TD>';
echo '<TR><TD width=0 valign="top">';
if ($browse) {
    renderBrowserForm($game);
    renderHistory($game['mhistory'], null, 1);
} else {
    renderCommandForm($game, $cmdres, $move);
    renderHistory($game['mhistory'], getCMDiff($game['board']), 0);
    /*if ((strstr($cmdres, 'CHECKMATE')) || (strstr($cmdres, 'STALEMATE'))){
    @unlink("../../games/chess/vasx9i0b2w/games/$gid");
    }*/
}
echo '</TD></TR>';

echo '</TABLE>';
echo '</BODY></HTML>';
if ($browse)
    echo '<script language="Javascript">gotoMove(0);gotoMove(move_count-1);renderBoard();</script>';
?>