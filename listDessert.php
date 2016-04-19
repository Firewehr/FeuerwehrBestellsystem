<?php

require_once('auth.php');
$Tischnummer = intval($_GET['tischnummer']);
echo '<div id="Dessert">';
//echo '<h3>Desserts</h3>';
include_once ("include/db.php");

$resulttt = mysql_query("SELECT * FROM positionen WHERE type=3 ORDER BY Positionsname");
while ($rowww = mysql_fetch_array($resulttt)) {

    echo '<button class="ui-btn ui-corner-all" onclick="saveBestellung(' . $rowww['rowid'] . ',2,' . $Tischnummer . ');"';

    $result4 = mysql_query("SELECT COUNT(*) as cnt FROM bestellungen WHERE tischnummer=" . $Tischnummer . " AND kueche=0 AND `delete`=0 AND position=" . $rowww['rowid']);

    $cnt = "";
    while ($row4 = mysql_fetch_array($result4)) {

        if ($row4['cnt'] > 0) {

            $Colour = "yellow";
            $cnt = $row4['cnt'] . "x";
        } else {
            $Colour = "white";
        }
    }

    echo ' style="background:' . $Colour . ';">';
    echo utf8_encode($rowww['Positionsname']);
    echo ' ' . $cnt;

    echo '</button>';
}
echo '</div>';
