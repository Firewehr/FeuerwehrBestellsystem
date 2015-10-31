<?php
require_once('auth.php');
include_once ("include/db.php");

error_reporting(E_ALL);

$tischname = $_POST['neuerTischName'];
$tischnummer = $_POST['neueTischNummer'];

$sql = "INSERT `tische` SET `tischnummer`='$tischnummer',`tischname`='$tischname'";

if (!mysqli_query($conn, $sql)) {
    die('Error: ' . utf8_encode(mysqli_error($conn)));
}
echo "Tisch wurde erfolgreich gespeichert!";
mysqli_close($conn);
