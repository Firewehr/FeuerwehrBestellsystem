<?php
require_once('auth.php');

require_once 'include/db.php';

$myArray = $_REQUEST['listePositionen'];


foreach ($myArray as $row) {

    $sql = "UPDATE `bestellungen` SET `zeitKueche`=current_timestamp, `kueche`= '1' WHERE rowid=" . intval($row);

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);