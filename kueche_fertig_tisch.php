<?php

require_once('auth.php');
require_once 'include/db.php';

$tischnummer = intval($_POST['tischnummer']);
$PositionsListe = $_REQUEST['listePositionen'];

$timestamp = date('Y-m-d G:i:s');

foreach ($PositionsListe as $row) {

    $sql = "UPDATE `bestellungen` SET "
            . "`zeitKueche`=current_timestamp,"
            . "`kueche`= '1',"
            . "`print`=2 "
            . "WHERE zeitKueche='0000-00-00 00:00:00' AND rowid=" . intval($row);

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";

        if (mysqli_affected_rows($conn) > 0) {
            //Insert Print
            $sql = "INSERT INTO print (bestellungID,timestamp) VALUES (" . intval($row) . ",'" . $timestamp . "')";
            echo $sql;
            if (mysqli_query($conn, $sql)) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}



mysqli_close($conn);
