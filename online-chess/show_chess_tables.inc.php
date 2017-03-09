<?php
$sql = mysql_query("SELECT host,color,timestamp FROM chess_table") or die(mysql_error());

while ($row = mysql_fetch_array($sql)) {
    if($row['color'] == 1){
        $color = 'White';
    }else{
        $color = 'Black';
    }
    
    $playURL = "play_chess.php?act=join_chess_table&host={$row['host']}&color={$row['color']}&time_stamp={$row['timestamp']}";

    echo "<tr>
           <td>{$row['host']}</td>
           <td>$color</td>
           <td><a href=\"$playURL\">play!</a></td>
          </tr>";
}
?>