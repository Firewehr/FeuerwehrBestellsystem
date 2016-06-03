<?php

include_once ("include/db.php");

$Tischnummer=999999;

$sql = "SELECT `bestellungen`.`tischnummer`,bestellungen.timestampBezahlung, `positionen`.`Betrag` as betrag, `positionen`.`Positionsname`, `bestellungen`.`zeitKueche`,`bestellungen`.`position`, `positionen`.`rowid`, `bestellungen`.`zeitstempel`, `bestellungen`.`rowid` as rowidBestellung,`bestellungen`.`delete`,`bestellungen`.`kueche` AS kuechef FROM `bestellungen`, `positionen` WHERE  `positionen`.`rowid`=`bestellungen`.`position` AND `bestellungen`.`tischnummer`=" . $Tischnummer . ' AND `bestellungen`.`delete`=0 AND `bestellungen`.`kueche`=1 AND `bestellungen`.`timestampBezahlung`="0000-00-00 00:00:00" ORDER BY positionen.Positionsname DESC LIMIT 300';

$Summe = 0;
$rowIDsBezahltAlle = "";
$result = mysqli_query($conn, $sql);
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    $Colour = "white";

    $Summe+=$row['betrag'];

    if ($row['kuechef'] == 1) {
        $Colour = "yellow";
    }
    $timestamp = strtotime($row['zeitstempel']);

    $rowIDsBezahltAlle = $rowIDsBezahltAlle . $row['rowidBestellung'] . ' OR rowid=';
}

echo '<h2>Gesamt: ';
echo money_format('%i', (double) $Summe);
echo '</h2>';

//echo $rowIDsBezahltAlle;

if ($count > 0) {
    echo '<a style="background-color:rgba(255, 255, 0,0.5)" onclick="BestellungBezahlt(\'' . $rowIDsBezahltAlle . '\',1);" class="ui-btn">Bezahlen</a>';
}
    