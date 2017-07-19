<?php

//require_once('auth.php');

$rowid = intval($_GET['rowid']);
$Kurzbezeichnung = $_GET['Kurzbezeichnung'];

if ($Kurzbezeichnung !="" ) {

    require_once '../include/db.php';
    $sql = "UPDATE `positionen` SET `Kurzbezeichnung`='" . utf8_decode($Kurzbezeichnung) . "' WHERE rowid=" . $rowid;

    //echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}