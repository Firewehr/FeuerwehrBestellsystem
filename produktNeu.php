<?php

require_once('auth.php');
include_once ("include/db.php");

error_reporting(E_ALL);

$Positionsname = mysqli_real_escape_string($conn, $_POST['Positionsname']);
$Positionsname = utf8_decode($Positionsname);
$Betrag = mysqli_real_escape_string($conn, $_POST['Betrag']);
$type = intval($_POST['type']);
$Kapazitaet = intval($_POST['Kapazitaet']);

$sql = "INSERT `positionen` SET `type`='$type',`positionsname`='$Positionsname',`Betrag`=$Betrag,`maxBestellbar`=$Kapazitaet";

if (!mysqli_query($conn, $sql)) {
    die('Error: ' . utf8_encode(mysqli_error($conn)));
}
echo "Position wurde erfolgreich gespeichert!";
mysqli_close($conn);
