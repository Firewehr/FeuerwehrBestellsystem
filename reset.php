<?php

require_once('auth.php');
//echo $_SESSION['admin'];

if ($_SESSION['admin'] != 1) {
    header('Location: index.php');
}

//

require_once('include/db.php');

$connection = mysqli_connect($hostname, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

if ($_GET['cmd'] == "reset") {
    $sql = "TRUNCATE `bestellungen`";

    if (!mysqli_query($connection, $sql)) {
        die('Error: ' . mysqli_error($connection));
    }


    $sql = "TRUNCATE `print`";
    if (!mysqli_query($connection, $sql)) {
        die('Error: ' . mysqli_error($connection));
    }
}





mysqli_close($connection);
