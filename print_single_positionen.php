<?php

require_once('auth.php');
require_once 'include/db.php';

$tischnummer = intval($_POST['tischnummer']);
$PositionsListe = $_REQUEST['listePositionen'];

$timestamp = date('Y-m-d G:i:s');

foreach ($PositionsListe as $row) {

//Insert Print
    $sql = "INSERT INTO print (bestellungID,timestamp) VALUES (" . intval($row) . ",'" . $timestamp . "')";
    echo $sql;
    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}


mysqli_close($conn);
