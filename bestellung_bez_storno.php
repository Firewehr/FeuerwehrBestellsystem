<?require_once('auth.php');error_reporting(E_ALL);$rowid = intval($_GET['rowid']);include_once('include/db.php');$sql = "UPDATE `bestellungen` SET `timestampBezahlung` = '0000-00-00 00:00:00', `kellnerZahlung` = '' WHERE `bestellungen`.`rowid`=" . $rowid;if (mysqli_query($conn, $sql)) {    echo "Record updated successfully";} else {    echo "Error updating record: " . mysqli_error($conn);}#Bezahlung stornieren / Button sieht nur der Admin, aber PHP kann jeder ausführen.mysqli_close($conn);?>