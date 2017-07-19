<?php

//require_once('auth.php');

$rowid = intval($_POST['rowid']);
$color = $_POST['color'];


//str_replace(",",".",$Betrag);



require_once '../include/db.php';
$sql = "UPDATE `positionen` SET `color`='" . $color . "' WHERE rowid=" . $rowid;

echo $sql;

if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
mysqli_close($conn);
