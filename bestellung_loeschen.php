<?php

require_once('auth.php');

error_reporting(E_ALL);
$rowid = $_GET['rowid'];


include_once('include/db.php');

$sql = "UPDATE `bestellungen` SET `delete`='1' WHERE `bestellungen`.`rowid`=" . $rowid;

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
