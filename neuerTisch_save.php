<?php

require_once('auth.php');
include_once ("include/db.php");

error_reporting(E_ALL);

$tischname = mysqli_real_escape_string($conn, $_POST['neuerTischName']);

$neueTischFarbe = mysqli_real_escape_string($conn, $_POST['neueTischFarbe']);
$neueTischX = intval($_POST['neueTischX']);
$neueTischY = intval($_POST['neueTischY']);

$sql = "INSERT `tische` SET "
        . "`tischname`='$tischname',"
        . "`x`=$neueTischX,"
        . "`y`=$neueTischY,"
        . "`color`=\"$neueTischFarbe\"";
echo $sql;

if (!mysqli_query($conn, $sql)) {
    die('Error: ' . utf8_encode(mysqli_error($conn)));
}
echo "Tisch wurde erfolgreich gespeichert!";
mysqli_close($conn);
