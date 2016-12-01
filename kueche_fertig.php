<?php

require_once('auth.php');

require_once 'include/db.php';

$myArray = $_REQUEST['rowid'];

/*
  foreach ($myArray as $row) {

  } */

$sql = "UPDATE `bestellungen` "
        . "SET `zeitKueche`=current_timestamp,"
        . "`print`='2',"
        . "`kueche`='1' "
        . "WHERE rowid=" . intval($_GET['rowid']);

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

echo $sql;
mysqli_close($conn);
