<?php

//require_once('auth.php');

$rowid = intval($_GET['rowid']);
$Positionsname = $_GET['Positionsname'];

if ($Positionsname !="" ) {

    require_once '../include/db.php';
    $sql = "UPDATE `positionen` SET `Positionsname`='" . utf8_decode($Positionsname) . "' WHERE rowid=" . $rowid;

    //echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}