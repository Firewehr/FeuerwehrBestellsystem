<?php

$Tischnummer = 999999; 


try {
    include_once ("include/db.php");
	// Anzeige für Drucker mit Anzahl
	// Zum Beispiel 3x Bier
	$sql = "SELECT COUNT(*) as cnt, `bestellungen`.`rowid`, `bestellungen`.`tischnummer`, bestellungen.kellnerZahlung, bestellungen.timestampBezahlung, `positionen`.`Kurzbezeichnung`, `positionen`.`Betrag` as betrag From `bestellungen`, `positionen` WHERE NOT `bestellungen`.`timestampBezahlung`='0000-00-00 00:00:00' AND `bestellungen`.`position`= `positionen`.`rowid` AND `bestellungen`.`delete`=0 AND `bestellungen`.`kueche`=1 AND `bestellungen`.`print`=0 AND `bestellungen`.`tischnummer`= 999999 GROUP BY `positionen`.`Positionsname` ORDER BY `positionen`.`Positionsname`";
    
	// Debug
	//echo $sql;

    $result1 = mysqli_query($conn, $sql);
    mysqli_set_charset($conn, "SET NAMES utf8");
    mysqli_set_charset($conn, "utf8");

    $array = array();
    while ($row = mysqli_fetch_assoc($result1)) {
        $array[] = array_map('utf8_encode', $row);
    }
    echo json_encode($array);

	// Alle Rowids herausfinden ohne Gruppierung und Counting
	$sql = "SELECT `bestellungen`.`rowid`, `bestellungen`.`tischnummer`, bestellungen.kellnerZahlung, bestellungen.timestampBezahlung, `positionen`.`Kurzbezeichnung`, `positionen`.`Betrag` as betrag From `bestellungen`, `positionen` WHERE NOT `bestellungen`.`timestampBezahlung`='0000-00-00 00:00:00' AND `bestellungen`.`position`= `positionen`.`rowid` AND `bestellungen`.`delete`=0 AND `bestellungen`.`kueche`=1 AND `bestellungen`.`print`=0 AND `bestellungen`.`tischnummer`= 999999 ORDER BY `bestellungen`.`rowid`";

    //Debug
	//echo $sql;

    $result1 = mysqli_query($conn, $sql);
    mysqli_set_charset($conn, "SET NAMES utf8");
    mysqli_set_charset($conn, "utf8");

    $Summe = 0;

    $array = array();
    while ($row = mysqli_fetch_assoc($result1)) {
        $array[] = array_map('utf8_encode', $row);
    }
    //echo json_encode($array);    

    foreach ($array as $item) {

        $sql2 = "UPDATE `bestellungen` "
                . "SET `print`='1' "
                . "WHERE rowid=" . intval($item['rowid']);
        //echo "<br>" . $sql2;
        if (mysqli_query($conn, $sql2)) {
            //echo "Record updated successfully";
        } else {
            //echo "Error updating record: " . mysqli_error($conn);
        }
	}
	mysqli_close($conn);
} 
catch (Exception $e) {
    echo $e->getMessage();
}
