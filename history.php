<?php
require_once('auth.php');
error_reporting(E_ALL);
$Tischnummer = intval($_GET['tischnummer']);
$tischnummerselect = 0;
try {
    include_once ("include/db.php");

    //$result1 = mysql_query();
    $sql1 = "SELECT `positionen`.`type`,tische.tischname, bestellungen.tischnummer, FLOOR( UNIX_TIMESTAMP(  bestellungen.zeitstempel ) /300 ) AS t, COUNT( * ) FROM bestellungen, positionen,tische WHERE tische.tischnummer=bestellungen.tischnummer AND bestellungen.position=positionen.rowid AND `delete`=0 AND `kueche`=0 GROUP BY t, bestellungen.tischnummer ORDER BY bestellungen.zeitstempel, t LIMIT 50";

    $result1 = mysqli_query($conn, $sql1);

    $Summe = 0;
    
    echo '<table>';
    while ($row1 = mysqli_fetch_assoc($result1)) {
        
        $t = $row1['t'];

        /*
          if ($tischnummerselect != $row1['tischnummer']) {
          echo '<h2 style="font-size:30px">Tisch: ' . $row1['tischname'] . ' (#' . $row1['tischnummer'] . ")</h2>";
          $tischname = $row1['tischname'];
          }
         * 
         */
        $tischnummerselect = $row1['tischnummer'];
        //$result = mysql_query("SELECT `bestellungen`.`tischnummer`,bestellungen.timestampBezahlung,`positionen`.`Betrag` as betrag, `positionen`.`Positionsname`, `bestellungen`.`zeitKueche`,`bestellungen`.`position`, `positionen`.`rowid`, `bestellungen`.`zeitstempel`, `bestellungen`.`rowid`,`bestellungen`.`delete`,`bestellungen`.`kueche` AS kuechef FROM `bestellungen`, `positionen` WHERE  `positionen`.`rowid`=`bestellungen`.`position` AND `bestellungen`.`tischnummer`=" . $Tischnummer . ' AND `bestellungen`.`delete`=0  ORDER BY bestellungen.zeitstempel DESC LIMIT 300');
        $sql2 = "SELECT COUNT( * ) AS anzahl, `bestellungen`.`zeitKueche`, `bestellungen`.`position`, `bestellungen`.`tischnummer`, `bestellungen`.`zeitstempel`, `positionen`.`rowid`, `positionen`.`Positionsname`, `positionen`.`type`, `bestellungen`.`kueche`, `bestellungen`.`delete`, `bestellungen`.`rowid`, FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  FROM bestellungen, positionen WHERE bestellungen.position=positionen.rowid AND `type`=1 AND  bestellungen.zeitKueche='0000-00-00 00:00:00' AND bestellungen.ausgeliefert=0 AND positionen.type=1 AND bestellungen.delete=0 AND bestellungen.tischnummer=" . $tischnummerselect . " AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " GROUP BY bestellungen.position ORDER BY positionen.Positionsname ASC";

        $result2 = mysqli_query($conn, $sql2);
        $Summe = 0;

        while ($row = mysqli_fetch_assoc($result2)) {
            echo '<tr>';
            //echo '<tr>';
            //echo $row['rowid'];
            $Colour = "white";

            $Summe+=$row['betrag'];

            if ($row['kuechef'] == 1) {
                $Colour = "yellow";
            }

            //echo '<td>';
            //echo '<input id="' . $row['rowid'] . '" style="background:' . $Colour . ';" onclick="Summe+=' . $row['betrag'] . ';document.getElementById(' . $row['rowid'] . ').style.backgroundColor = \'#00CC00\';document.getElementById(sum).value=1;" type="button" value="' . utf8_encode($row['Positionsname']) . '" />';
            ////echo '<button class="ui-btn ui-corner-all" id="' . $row['rowid'] . '" style="background:' . $Colour . ';">' . utf8_encode($row['Positionsname']) . '"</button>';
            //echo '</td>';
            //echo '<td>' . $row['kueche'] . '</td>';

            $timestamp = strtotime($row['zeitstempel']);
            //echo date("H:i:s", $timestamp);
            echo '<td>' . date("H:i", $timestamp) . '</td>';
            //echo '<td>' . $row['rowid'] . '</td>';
            echo '<td>';
            if ($row['zeitKueche'] == '0000-00-00 00:00:00') {
                $color = "rgba(255, 255, 0,0.8)";
                echo '<a style="background-color:' . $color . '" href="#" class="ui-btn ui-icon-delete ui-btn-icon-left" onclick="bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');"' . '>' . utf8_encode($row['Positionsname']) . ' Bestellt: ' . date("H:i", strtotime($row['zeitstempel'])) . ' Fertig: ' . date("H:i", strtotime($row['zeitKueche'])) . '</a>';
            } elseif ($row['timestampBezahlung'] == '0000-00-00 00:00:00') {
                echo "TEST";
                $color = "rgba(240, 14, 14,0.8)";
                echo '<a style="background-color:' . $color . '" href="#" class="ui-btn ui-icon-delete ui-btn-icon-left" onclick="bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');"' . '>' . utf8_encode($row['Positionsname']) . ' Bestellt: ' . date("H:i", strtotime($row['zeitstempel'])) . ' Fertig: ' . date("H:i", strtotime($row['zeitKueche'])) . '</a>';
            } else {
                $color = "rgba(51, 204, 51,0.8)";
                echo '<a style="background-color:' . $color . '" href="#" class="ui-btn ui-icon-delete ui-btn-icon-left" onclick="bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');"' . '>' . utf8_encode($row['Positionsname']) . ' Bestellt: ' . date("H:i", strtotime($row['zeitstempel'])) . ' Fertig: ' . date("H:i", strtotime($row['zeitKueche'])) . '</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';

    echo '<input id="sum" type="number" value="' . $Summe . '"';
    //echo "<div id='sum'></div>";
    //echo '</div>';
} catch (Exception $e) {
    echo $e->getMessage();
}
?>