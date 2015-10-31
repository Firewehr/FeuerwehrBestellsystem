<?php
require_once('auth.php');

$sql = $_GET['rowid'];


$sql = substr($sql, 0, -10);

require_once 'include/db.php';


//UPDATE `pos`.`bestellungen` SET `zeitKueche`=current_timestamp, `kueche`= '1' WHERE rowid=432 OR rowid=434
$sql = "UPDATE `bestellungen` SET `timestampBezahlung`=current_timestamp, `kueche`='1', `kellnerZahlung`='" . htmlspecialchars($_SESSION['user']['username']) . "' WHERE rowid=" . $sql;


//echo $sql;
if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);