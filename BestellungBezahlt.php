<?php

require_once('auth.php');


require_once 'include/db.php';


$myArray = $_REQUEST['listePositionen'];


foreach ($myArray as $row) {

    $sql = "UPDATE `bestellungen` "
            . "SET `timestampBezahlung`=current_timestamp,"
            . "`kueche`='1',"
            . "`kellnerZahlung`='" . htmlspecialchars($_SESSION['user']['username']) . "' "
            . "WHERE rowid=" . intval($row);

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
