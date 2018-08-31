<a  href="#indexPage" data-theme="b" class="ui-btn ui-icon-arrow-l ui-btn-icon-left">Zurück</a>

<?php

error_reporting(E_ALL);
include_once ("include/db.php");
$sql2 = "SELECT "
		."`positionen`.`rowid`,`bestellungen`.`rowid`, `bestellungen`.`zeitstempel`, `bestellungen`.`position`, `bestellungen`.`Zusatzinfo`, `bestellungen`.`tischnummer`,  `bestellungen`.`rowid`,`positionen`.`Positionsname`, `bestellungen`.`timestampBezahlung`, `bestellungen`.`delete`" 
		."FROM `bestellungen`, `positionen`  "
		."WHERE "
		."`tischnummer` = 999999 AND `bestellungen`.`position`=`positionen`.`rowid`AND `bestellungen`.`delete`=0 "
		."ORDER BY `bestellungen`.`rowid` DESC LIMIT 100";

$result2 = mysqli_query($conn, $sql2);
echo '<table width="100%">';

echo '<tr><th>RowID#</th><th>Bestellt</th><th>Fertig</th><th>Positionsname</th><th>Stornieren</th></tr>';
$Tischnummer = 999999;
while ($row2 = mysqli_fetch_assoc($result2)) { //Ausgabe der offenen Bestellungen eines Tisches
    echo '<tr>';
    echo '<td>';
    echo $row2['rowid'];
    echo '</td>';

    echo '<td>';
    echo $row2['zeitstempel'];
    echo '</td>';
    echo '<td>';
    echo $row2['timestampBezahlung'];
    echo '</td>';
    echo '<td>';
    echo utf8_encode($row2['Positionsname']);
    echo '</td>';
	echo '<td>';
	if ($row2['timestampBezahlung'] == '0000-00-00 00:00:00') {
	echo '<input style="background-color:#00FF6A; color:#ffff00;" type="button" value="Stornieren" '
    . 'onclick="bestellungLoeschen(' . $row2['rowid'] . ',' . $Tischnummer . ');"/>';
	} else {
	echo '<input style="background-color:#ff0095; color:#ffff00;" type="button" value="Stornieren" '
    . 'onclick="bestellungLoeschen(' . $row2['rowid'] . ',' . $Tischnummer . ');"/>';
	}
	echo '</td>';
    echo '</tr>';
}
echo '</table>';
?>