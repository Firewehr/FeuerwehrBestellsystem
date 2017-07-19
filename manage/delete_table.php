<?php

$tischnummer = intval($_GET['tischnummer']);


if ($tischnummer > 0) {

    require_once '../include/db.php';
    $sql = "DELETE FROM `tische` WHERE tischnummer=$tischnummer LIMIT 1";

    echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}