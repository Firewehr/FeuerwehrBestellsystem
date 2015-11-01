<?php
require_once('auth.php');
?>
<a style="background-color:rgba(158, 158, 150,0.3)" href="#" data-theme="b" onclick="tisch();" class="ui-btn ui-icon-arrow-l ui-btn-icon-left">Zurück zur Speisekarte</a>

<?php
$Tischnummer = $_GET['tischnummer'];
try {
    include_once ("include/db.php");
    $sql = "SELECT `bestellungen`.`tischnummer`,bestellungen.kellner, bestellungen.timestampBezahlung, bestellungen.Zusatzinfo, `positionen`.`Betrag` as betrag, `positionen`.`Positionsname`, `bestellungen`.`zeitKueche`,`bestellungen`.`position`, `positionen`.`rowid`, `bestellungen`.`zeitstempel`, `bestellungen`.`rowid`,`bestellungen`.`delete`,`bestellungen`.`kueche` AS kuechef FROM `bestellungen`, `positionen` WHERE  `positionen`.`rowid`=`bestellungen`.`position` AND `bestellungen`.`tischnummer`=" . $Tischnummer . ' AND `bestellungen`.`delete`=0 ORDER BY bestellungen.zeitstempel DESC LIMIT 30';
    $result1 = mysqli_query($conn, $sql);
    $Summe = 0;

    echo '<table width="100%"><tr><th>Name</th><th>Bestellt</th><th>Fertig</th><th>&nbsp;</th></tr>';
    while ($row = mysqli_fetch_assoc($result1)) {

        $Colour = "";
        if ($row['zeitKueche'] == '0000-00-00 00:00:00') {
            $color = "rgba(255, 255, 0,0.1)";
            $Colour = "yellow";
            $class = "ui-icon-delete ui-btn-icon-right";
            $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
        } elseif ($row['timestampBezahlung'] == '0000-00-00 00:00:00') {
            $color = "rgba(240, 14, 0,0.5)";
            $class = "ui-icon-delete ui-btn-icon-right";
            $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
            $Colour = "orange";
        } else {
            $color = "rgba(51, 204, 51,0.1)";
            $class = "";
            $onClick = "";
            $Colour = "lightgray";
        }


        echo '<tr style="background-color:' . $Colour . '">';

        $Colour = "white";

        $Summe+=$row['betrag'];

        if ($row['kuechef'] == 1) {
            $Colour = "yellow";
        }


        $timestamp = strtotime($row['zeitstempel']);
        //echo date("H:i:s", $timestamp);
        echo '<td><b>' . utf8_encode($row['Positionsname']) . '</b><a data-role="button"  onclick=\'saveZusatzinfo(prompt("Zusatzinfo ' . utf8_encode($row['Positionsname']) . ':","' . $row['Zusatzinfo'] . '"),' . $row['rowid'] . ');\'>+Info</a>';
        if (!empty($row['Zusatzinfo'])) {
            echo '<p>' . $row['Zusatzinfo'] . '</p>';
        }
        echo '</td>';
        echo '<td>' . date("H:i", $timestamp) . '<br>' . $row['kellner'] . '</td>';
        echo '<td>' . date("H:i", strtotime($row['zeitKueche'])) . '</td>';

        //echo '<td>' . $row['rowid'] . '</td>';
        echo '<td>';
        if ($row['zeitKueche'] == '0000-00-00 00:00:00') {
            $color = "rgba(255, 255, 0,0.1)";
            $class = "ui-icon-delete ui-btn-icon-right";
            $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
            echo '<a href="#"  data-role="button" data-icon="delete" data-iconpos="notext" onclick="' . $onClick . '">Löschen</a>';
        } elseif ($row['timestampBezahlung'] == '0000-00-00 00:00:00') {
            $color = "rgba(240, 14, 0,0.5)";
            $class = "ui-icon-delete ui-btn-icon-right";
            $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
            echo '<a href="#"  data-role="button" data-icon="delete" data-iconpos="notext" onclick="' . $onClick . '">Löschen</a>';
        } else {
            $color = "rgba(51, 204, 51,0.1)";
            $class = "";
            $onClick = "";
        }


        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';

} catch (Exception $e) {
    echo $e->getMessage();
}

  