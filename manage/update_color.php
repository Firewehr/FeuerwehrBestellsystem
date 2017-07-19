<?php

//require_once('auth.php');

$tischnummer = intval($_POST['tischnummer']);
$color = $_POST['color'];


//str_replace(",",".",$Betrag);



require_once '../include/db.php';
$sql = "UPDATE `tische` SET `color`='" . $color . "' WHERE tischnummer=" . $tischnummer;

echo $sql;

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
mysqli_close($conn);
