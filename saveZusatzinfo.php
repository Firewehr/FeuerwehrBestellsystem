<?php

require_once('auth.php');

$rowid = intval($_POST['rowid']);
$Zusatzinfo = mysql_escape_string($_POST['Zusatzinfo']);

if (!empty($Zusatzinfo)) {

    require_once 'include/db.php';
    $sql = "UPDATE `bestellungen` SET `Zusatzinfo`='" . $Zusatzinfo . "' WHERE rowid=" . $rowid;

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}