<?php

$Tischnummer = intval($_GET['tischnummer']);
$type = intval($_GET['type']);

try {
    include_once ("include/db.php");
    $sql = "SELECT "
            . "COUNT(*) as cnt,"
            . "`bestellungen`.`tischnummer`,"
            . "bestellungen.kellner,"
            . "bestellungen.kellnerZahlung,"
            . "bestellungen.timestampBezahlung, "
            . "bestellungen.Zusatzinfo, "
            . "`positionen`.`Betrag` as betrag, "
            . "`positionen`.`Positionsname`, "
            . "`positionen`.`Kurzbezeichnung`, "
            . "`bestellungen`.`zeitKueche`,"
            . "`bestellungen`.`position`, "
            . "`positionen`.`rowid`, "
            . "`bestellungen`.`zeitstempel`, "
            . "`bestellungen`.`rowid`,"
            . "`bestellungen`.`delete`,"
            . "`bestellungen`.`kueche` AS kuechef,"
            . "tischname "

            #. "DATE_FORMAT(FROM_UNIXTIME(`bestellungen.zeitstempel`), '%e %b %Y') AS 'ZeitBestellung'"
            . "FROM `bestellungen`, "
            . "`positionen`,"
            . "print,"
            . "tische "
            . "WHERE `positionen`.`rowid`=`bestellungen`.`position` "
            . "AND `bestellungen`.`delete`=0 "
            . "AND `bestellungen`.`kueche`=1 "
            . "AND `bestellungen`.`print`=2 "
            . "AND bestellungen.tischnummer=tische.tischnummer "
            . "AND type=" . $type . " "
            . "AND `bestellungen`.`timestampBezahlung`='0000-00-00 00:00:00' "
            . "AND print.bestellungID=bestellungen.rowid "
            . "GROUP BY `bestellungen`.`tischnummer`,`bestellungen`.`position` "
            . "ORDER BY bestellungen.tischnummer DESC LIMIT 100";

    //echo $sql;

    $result1 = mysqli_query($conn, $sql);
    mysqli_set_charset($conn, "SET NAMES utf8");
    mysqli_set_charset($conn, "utf8");

    $Summe = 0;

    $array = array();
    while ($row = mysqli_fetch_assoc($result1)) {
        $array[] = array_map('utf8_encode', $row);
    }
    echo json_encode($array);



    $sql = "SELECT "
            . "timestampBestellung, `bestellungen`.`tischnummer`,"
            . "bestellungen.kellner,"
            . "bestellungen.kellnerZahlung,"
            . "bestellungen.timestampBezahlung, "
            . "bestellungen.timestampBezahlung, "
            . "bestellungen.Zusatzinfo, "
            . "`positionen`.`Betrag` as betrag, "
            . "`positionen`.`Positionsname`, "
            . "`bestellungen`.`zeitKueche`,"
            . "`bestellungen`.`position`, "
            . "`positionen`.`rowid`, "
            . "`bestellungen`.`zeitstempel`, "
            . "`bestellungen`.`rowid`,"
            . "`bestellungen`.`delete`,"
            . "`bestellungen`.`kueche` AS kuechef "
            . "FROM `bestellungen`, "
            . "`positionen`,"
            . "print "
            . "WHERE  `positionen`.`rowid`=`bestellungen`.`position` "
            . "AND `bestellungen`.`delete`=0 "
            . "AND `bestellungen`.`kueche`=1 "
            . "AND `bestellungen`.`print`=2 "
            . "AND type=" . $type . " "
            . "AND `bestellungen`.`timestampBezahlung`='0000-00-00 00:00:00' "
            . "AND print.bestellungID=bestellungen.rowid "
            . "GROUP BY bestellungen.rowid ORDER BY bestellungen.tischnummer DESC LIMIT 100";

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


        $sql2 = "DELETE FROM `print` WHERE `print`.`bestellungID`=" . intval($item['rowid']);
        //echo "<br>" . $sql2;
        if (mysqli_query($conn, $sql2)) {
            //echo "Record updated successfully";
        } else {
            //echo "Error updating record: " . mysqli_error($conn);
        }
    }


    /*


     */
    mysqli_close($conn);
} catch (Exception $e) {
    echo $e->getMessage();
}
