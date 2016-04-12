<?php


header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename(date("Y-m-d_H-i",time()). '_Backup_Bestellsystem.html'));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
//header('Content-Length: ' . filesize('file.txt'));

include_once ("include/db.php");

$tischnummerselect = "";
try {
    include_once ("include/db.php");

    $sql = "SELECT `positionen`.`type`,tische.tischname, bestellungen.tischnummer, FLOOR( UNIX_TIMESTAMP(  bestellungen.zeitstempel ) /300 ) AS t, COUNT( * ), kellner FROM bestellungen, positionen,tische WHERE tische.tischnummer=bestellungen.tischnummer AND bestellungen.position=positionen.rowid AND `delete`=0 AND `kueche`=0 AND `type`=1 GROUP BY t, bestellungen.tischnummer ORDER BY bestellungen.zeitstempel, t LIMIT 500";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $counter = 0;
        $Bestellungen = "";

        $t = $row['t'];

        if ($tischnummerselect != $row['tischnummer']) {
            echo '<h2 style="font-size:30px">Tisch: ' . $row['tischname'] . '</h2>';
            $tischname = $row['tischname'];
            echo '<p>KellnerIn: ' . $row['kellner'] . '</p>';
        }


        $tischnummerselect = $row['tischnummer'];

        $sql2 = "SELECT COUNT( * ) AS anzahl, `bestellungen`.`zeitKueche`, `bestellungen`.`position`, bestellungen.Zusatzinfo, `bestellungen`.`tischnummer`, `bestellungen`.`zeitstempel`, `positionen`.`rowid`, `positionen`.`Positionsname`, `positionen`.`type`, `bestellungen`.`kueche`, `bestellungen`.`delete`, `bestellungen`.`rowid`, FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  FROM bestellungen, positionen WHERE bestellungen.position=positionen.rowid AND `type`=1 AND  bestellungen.zeitKueche='0000-00-00 00:00:00' AND bestellungen.ausgeliefert=0 AND positionen.type=1 AND bestellungen.delete=0 AND bestellungen.tischnummer=" . $tischnummerselect . " AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " GROUP BY Zusatzinfo,Positionsname ORDER BY positionen.Positionsname ASC LIMIT 100";

        $result2 = mysqli_query($conn, $sql2);

        while ($row2 = mysqli_fetch_assoc($result2)) { //Ausgabe der offenen Bestellungen eines Tisches
            echo $row2['anzahl'] . 'x) ' . utf8_encode($row2['Positionsname']);
            if (!empty($row2['Zusatzinfo'])) {
                echo ' (' . $row2['Zusatzinfo'] . ') ';
            }
            echo '<br>';
            $timestamp = strtotime($row2['zeitstempel']);
        }

        if ($counter == 0) {
            echo gmdate("H:m", $timestamp) . '<br>';
        }
        $counter++;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>