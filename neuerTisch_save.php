<?php
require_once('auth.php');
include_once ("include/db.php");

error_reporting(E_ALL);

$tischname = mysql_escape_string($_POST['neuerTischName']);
$tischnummer = intval($_POST['neueTischNummer']);

$sql = "INSERT `tische` SET `tischnummer`='$tischnummer',`tischname`='$tischname'";

if (!mysqli_query($conn, $sql)) {
    die('Error: ' . utf8_encode(mysqli_error($conn)));
}
echo "Tisch wurde erfolgreich gespeichert!";
mysqli_close($conn);
