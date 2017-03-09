<?php
$sql = mysql_query("SELECT opponents,gameid,host,white FROM ongoing_games WHERE username='{$_SESSION['username']}' AND urturn=1") or die(mysql_error());

while ($row = mysql_fetch_array($sql)) {
    
    $playURL = "play_chess.php?act=play_chess&white={$row['white']}&host={$row['host']}&gid={$row['gameid']}";

    echo "<tr>
           <td>{$row['opponents']}</td>
           <td>{$row['host']}</td>
           <td><a href=\"$playURL\">play!</a></td>
          </tr>";
}	
?>