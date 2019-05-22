<a  href="#indexPage" data-theme="b" class="ui-btn ui-icon-arrow-l ui-btn-icon-left">Zurück</a>

<?php

require_once('auth.php');

error_reporting(E_ALL);
include_once ("include/db.php");
$sql2 = "SELECT "
        . "tische.tischname, "
        . "`bestellungen`.`zeitKueche`, `bestellungen`.`position`, bestellungen.Zusatzinfo, `bestellungen`.`tischnummer`, `bestellungen`.`zeitstempel`, `positionen`.`rowid`, `positionen`.`Positionsname`, `positionen`.`type`, `bestellungen`.`kueche`, `bestellungen`.`delete`, `bestellungen`.`rowid` "
        . "FROM bestellungen, positionen, tische "
        . "WHERE "
        . "tische.tischnummer=bestellungen.tischnummer AND "
        . "bestellungen.position=positionen.rowid AND `type`=1 AND bestellungen.delete=0 ORDER BY zeitKueche DESC LIMIT 100";

$result2 = mysqli_query($conn, $sql2);
echo '<table width="100%">';

echo '<tr><th>Tisch#</th><th>Bestellt</th><th>Fertig</th><th>Positionsname</th></tr>';
while ($row2 = mysqli_fetch_assoc($result2)) { //Ausgabe der offenen Bestellungen eines Tisches
    echo '<tr>';
    echo '<td>';
    echo $row2['tischname'];
    echo '</td>';

    echo '<td>';
    echo $row2['zeitstempel'];
    echo '</td>';

    echo '<td>';
    echo $row2['zeitKueche'];
    echo '</td>';

    echo '<td>';
    echo utf8_encode($row2['Positionsname']);
    echo ' - ' . $row2['Zusatzinfo'];
    echo '</td>';
    echo '</tr>';
}
echo '</table>';
?>
