<?php

require_once('auth.php');

$rowid = intval($_GET['rowid']);
$kapazitaet = intval($_GET['kapazitaet']);

if ($rowid > 0 && $rowid > 0) {

    require_once 'include/db.php';
    $sql = "UPDATE `positionen` SET `maxBestellbar`='" . $kapazitaet . "' WHERE rowid=" . $rowid;

    //echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}