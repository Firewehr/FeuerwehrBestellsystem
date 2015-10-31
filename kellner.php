<?php

require_once('auth.php');
include_once ("include/db.php");
$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT COUNT(*) as cnt, kellnerZahlung,SUM(positionen.Betrag) as summe FROM `bestellungen`,positionen WHERE bestellungen.position=positionen.rowid AND `delete`=0 AND timestampBezahlung!='0000-00-00 00:00:00' GROUP by kellnerZahlung ORDER BY kellnerZahlung";
echo $sql;
$result = mysqli_query($conn, $sql);
echo '<table>';
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr><td>' . $row['kellnerZahlung'] . '</td><td>' . $row['cnt'] . '</td><td>Summe' . $row['summe'] . ' €</td></tr>';
}
echo '</table>';

