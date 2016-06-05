<?php

require_once('auth.php');

include_once ("include/db.php");

$Tischnummer = 999999;

$sql = "SELECT `bestellungen`.`tischnummer`,"
        . "bestellungen.timestampBezahlung, "
        . "`positionen`.`Betrag` as betrag,"
        . " `positionen`.`Positionsname`,"
        . " `bestellungen`.`zeitKueche`,"
        . "`bestellungen`.`position`,"
        . " `positionen`.`rowid`,"
        . " `bestellungen`.`zeitstempel`,"
        . " `bestellungen`.`rowid` as rowidBestellung,"
        . "`bestellungen`.`delete`,"
        . "`bestellungen`.`kueche` AS kuechef,"
        . "`bestellungen`.`kellner`"
        . " FROM `bestellungen`, `positionen`"
        . " WHERE  `positionen`.`rowid`=`bestellungen`.`position`"
        . " AND `bestellungen`.`tischnummer`=" . $Tischnummer . ''
        . " AND `bestellungen`.`kellner`='" . htmlspecialchars($_SESSION['user']['username']) . "'"
        . ' AND `bestellungen`.`delete`=0'
        . ' AND `bestellungen`.`kueche`=1'
        . ' AND `bestellungen`.`timestampBezahlung`="0000-00-00 00:00:00"'
        . ' ORDER BY positionen.Positionsname DESC LIMIT 300';

$Summe = 0;
$rowIDsBezahltAlle = "";
$result = mysqli_query($conn, $sql);
$count = 0;
$arrayListe = '[';

while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    $Colour = "white";

    $Summe+=$row['betrag'];

    if ($row['kuechef'] == 1) {
        $Colour = "yellow";
    }
    $timestamp = strtotime($row['zeitstempel']);

    $rowIDsBezahltAlle = $rowIDsBezahltAlle . $row['rowidBestellung'] . ' OR rowid=';
    $arrayListe = $arrayListe . $row['rowidBestellung'] . ',';
}
$arrayListe = substr($arrayListe, 0, -1);
$arrayListe = $arrayListe . ']';
echo '<h2>Gesamt: ';
echo money_format('%i', (double) $Summe);
echo '</h2>';

//echo $rowIDsBezahltAlle;

if ($count > 0) {
    echo '<a style="background-color:rgba(255, 255, 0,0.5)" '
    . 'onclick="BestellungBezahlt(' . $arrayListe . ',1);" '
    . 'class="ui-btn">Bezahlen</a>';
}
    