<?php
$sql = mysql_query("SELECT opponents,gameid FROM pgn WHERE username='{$_SESSION['username']}'") or die(mysql_error());

while ($row = mysql_fetch_array($sql)) {
    
    $viewURL = "finished_chess_board.php?opponent={$row['opponents']}&gid={$row['gameid']}";

    echo "<tr>
           <td>{$row['opponents']}</td>
           <td><a href=\"$viewURL\">view!</a></td>
          </tr>";
}	
?>