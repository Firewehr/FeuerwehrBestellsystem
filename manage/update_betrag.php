<?php

require_once('../auth.php');

$rowid = intval($_GET['rowid']);
$Betrag = $_GET['Betrag'];


//str_replace(",",".",$Betrag);

if ($Betrag !="" ) {

    require_once '../include/db.php';
    $sql = "UPDATE `positionen` SET `Betrag`=" . $Betrag . " WHERE rowid=" . $rowid;

    //echo $sql;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}