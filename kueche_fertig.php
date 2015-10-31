<?php
require_once('auth.php');
$rowid = $_GET['rowid'];

require_once 'include/db.php';



$sql = "UPDATE `bestellungen` SET `zeitKueche`=current_timestamp, `kueche`= '1' WHERE `bestellungen`.`rowid`=" . $rowid;


//echo $sql;
if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);