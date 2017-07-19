<?php

//require_once('auth.php');

$tischnummer = intval($_GET['tischnummer']);
$tischname = $_GET['tischname'];

if ($tischnummer > 0) {

    require_once '../include/db.php';
    $sql = "UPDATE `tische` SET `tischname`='" . $tischname . "' WHERE tischnummer=" . $tischnummer;

    //echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}