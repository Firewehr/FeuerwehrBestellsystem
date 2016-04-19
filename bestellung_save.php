<?php

require_once('auth.php');
header("Cache-Control: no-cache");
$positionsid = intval($_POST['positionsid']);
$tischnummer = intval($_POST['Tischnummer']);

require_once('include/db.php');

$connection = mysqli_connect($hostname, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql = "INSERT `bestellungen` SET `tischnummer`='$tischnummer',`position`=$positionsid, `timestampBestellung`=now(), `kellner`='" . htmlspecialchars($_SESSION['user']['username']) . "'";

if (!mysqli_query($connection, $sql)) {
    die('Error: ' . mysqli_error($connection));
}
mysqli_close($connection);
