<?php

require_once('auth.php');

error_reporting(E_ALL);
$rowid = intval($_POST['rowid']);


include_once('include/db.php');

$sql = "DELETE FROM `positionen` WHERE `rowid`=" . $rowid . " LIMIT 1";

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
