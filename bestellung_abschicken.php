<?php

require_once('auth.php');
header("Cache-Control: no-cache");
$tischnummer = intval($_POST['tischnummer']);

require_once('include/db.php');

$connection = mysqli_connect($hostname, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if ($tischnummer != 0) {
    $sql = "UPDATE `bestellungen` SET `bestellt`='1' WHERE tischnummer=$tischnummer";
}


echo $sql;
if (!mysqli_query($connection, $sql)) {
    die('Error: ' . mysqli_error($connection));
}
mysqli_close($connection);
