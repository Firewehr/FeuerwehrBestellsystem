<?php
//Globale Variablen
$Titellogin = 'Bestellsystem';
$FFName = 'FF-ABCdorf';

/*
  $hostname = 'localhost';
  $username = 'pos';
  $password = 'pos';
  $dbname = 'pos';
 */

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
