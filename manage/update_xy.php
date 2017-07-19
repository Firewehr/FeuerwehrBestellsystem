<?php

//require_once('auth.php');

$tischnummer = intval($_GET['tischnummer']);
$x = intval($_GET['x']);
$y = intval($_GET['y']);


if ($tischnummer > 0) {

    require_once '../include/db.php';
    $sql = "UPDATE `tische` SET `x`=$x,`y`=$y WHERE tischnummer=" . $tischnummer;

    echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}