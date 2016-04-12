<?php
require_once('auth.php');
error_reporting(E_ALL);
?>

<html>
    <head>
        <title>Sicherung</title>
        <script>
            function DownloadBackup() {
                window.open("backup.php", '_blank');
                document.getElementById('DatumsFeld').innerHTML = "<h2>" + Date() + "</h2>";
            }
            window.setTimeout(DownloadBackup, 30000);
        </script>

    </head>
    <body onload="DownloadBackup();">
        <h1>Backup Funktion - WICHTIG Pop Ups erlauben im Browser und regelmässig kontrollieren ob im Download Ornder Backups vorhanden sind</h1>
        <p>Alle 30 Sekunden wir die aktuelle Bestellliste vom Server abgerufen und lokal gespeichert.</p>
        <h4>Letzte Sicherung:</h4>
        <div id="DatumsFeld"></div>
    </body>
</html>