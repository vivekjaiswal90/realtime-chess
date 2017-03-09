<?php
//extract host, opp username, id 
$sql = mysql_query("SELECT host FROM {$tablePrefix}_viewongoingbg WHERE gid='$gid'", $conn) or q_die(__file__, __line__, $conn);
list($host_username) = mysql_fetch_row($sql);

$sql = mysql_query("SELECT white,black,timestart FROM {$tablePrefix}_chessstart WHERE gid='$gid'", $conn) or q_die(__file__, __line__, $conn);
list($wPlayer, $bPlayer, $date_start_stamp) = mysql_fetch_row($sql);

$opponent = ($wPlayer == $host_username) ? $bPlayer : $wPlayer;

$sql = mysql_query("SELECT host.id host_id ,opponent.id opponent_id FROM {$tablePrefix}_userlogin host INNER JOIN {$tablePrefix}_userlogin opponent ON opponent.username = '$opponent' WHERE host.username = '$host_username'", $conn) or q_die(__FILE__,__LINE__,$conn);

$row = mysql_fetch_array($sql);

$host_id = $row['host_id'];
$oppMemberid = $row['opponent_id'];

/*$sql = mysql_query("SELECT id FROM {$tablePrefix}_userlogin WHERE username='$host_username'", $conn) or q_die(__file__, __line__, $conn);
list($host_id) = mysql_fetch_row($sql);

$sql = mysql_query("SELECT id FROM {$tablePrefix}_userlogin WHERE username='$opponent'", $conn) or q_die(__FILE__,__LINE__,$conn);
list($oppMemberid) = mysql_fetch_row($sql);*/
//END extract host, opp username, id

//record completed games
$sql = mysql_query("INSERT INTO {$tablePrefix}_ended_games_tracking VALUES('$host_username','$opponent','$gid','$game_name',0,0,'$date_start_stamp','$currentTimeStamp')",
    $conn) or q_die(__file__, __line__, $conn);

$sql = mysql_query("SELECT username FROM {$tablePrefix}_ended_games_tracking WHERE username='$host_username'",
    $conn) or q_die(__file__, __line__, $conn);

if (mysql_num_rows($sql) > 100) {

    $sql = mysql_query("SELECT gid FROM {$tablePrefix}_ended_games_tracking WHERE username='$host_username' ORDER BY endtime ASC LIMIT 1", $conn) or q_die(__file__, __line__, $conn);
    list($game_id_to_del) = mysql_fetch_row($sql);

    mysql_query("DELETE      
                    {$tablePrefix}_chesspgn,
                    {$tablePrefix}_ended_games_tracking
             FROM
                    {$tablePrefix}_chesspgn
             LEFT JOIN
                    {$tablePrefix}_ended_games_tracking ON {$tablePrefix}_ended_games_tracking.gid = {$tablePrefix}_chesspgn.gid
			 WHERE 
			        {$tablePrefix}_chesspgn.gid='$game_id_to_del'", $conn) or q_die(__file__,__line__, $conn);

}

//determine if host is VIP or not
$sql_vip = mysql_query("SELECT * FROM {$tablePrefix}_vip WHERE memberid='$host_id'", $conn) or q_die(__file__, __line__, $conn);

if (mysql_num_rows($sql_vip) == 0) {

    $sql2 = mysql_query("SELECT username FROM {$tablePrefix}_chess_ptracking WHERE username='$host_username'",
        $conn) or q_die(__file__, __line__, $conn);

    if (mysql_num_rows($sql2) > 100) {

        $sql = mysql_query("SELECT gid FROM {$tablePrefix}_chess_ptracking WHERE username='$host_username' ORDER BY endtime ASC LIMIT 1", $conn) or q_die(__file__, __line__, $conn);
        list($game_id_to_del) = mysql_fetch_row($sql);

        mysql_query("DELETE      
                    {$tablePrefix}_chesspgn,
                    {$tablePrefix}_chess_ptracking
             FROM
                    {$tablePrefix}_chesspgn
             LEFT JOIN
                    {$tablePrefix}_chess_ptracking ON {$tablePrefix}_chess_ptracking.gid = {$tablePrefix}_chesspgn.gid
			 WHERE 
			        {$tablePrefix}_chesspgn.gid='$game_id_to_del'", $conn) or q_die(__file__,__line__, $conn);

    }

}
?>