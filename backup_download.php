<?php
require_once('auth.php');
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8"/>
        <title>Sicherung</title>
        <script src="include/jquery/jquery-2.1.4.min.js"></script>
        <script>
            /*
             function DownloadBackup() {
             
             //window.location = 'backup.php';
             window.open(
             'backup.php',
             '_blank' // <- This is what makes it open in a new window.
             );
             
             
             }
             
             function DownloadGetraenke() {
             // window.location = 'backup_getraenke.php';
             window.open(
             'backup_getraenke.php',
             '_blank' // <- This is what makes it open in a new window.
             );
             document.getElementById('DatumsFeld').innerHTML = "<h2>" + Date() + "</h2>";
             }
             
             
             */
        </script>
        <script src="include/jquery.binarytransport.js"></script>
        <script>
            compareDate = new Date();
            $(document).ready(function () {
                $("body").css("background-color", "white");
            });

            function downloadSpeisen() {
                document.getElementById('DatumsFeld').innerHTML = "<h2>" + Date() + "</h2>";
                console.log("Download Backup ...");
                
                var diff = Math.abs(new Date() - compareDate);
                
                if (diff > 40000) {
                    $("body").css("background-color", "red");
                    $("#Fehlerausgabe").html("<h1>Fehler - Internetverbindung!</h1><p>Seit " + (diff/1000) +  "Sekunden kein Backup mehr!</p>");
                }
                else {
                    $("body").css("background-color", "white");
                }
                //document.getElementById('DatumsFeld').innerHTML = (diff/1000) + " Sekunden";
                var dt = new Date();
                var time = dt.getFullYear() + "-" + dt.getMonth() + "-" + dt.getDay() + "_" + dt.getHours() + "-" + dt.getMinutes() + "-" + dt.getSeconds();
                $.ajax({
                    url: "backup.php",
                    type: "GET",
                    dataType: 'binary',
                    success: function (result) {
                        var url = URL.createObjectURL(result);
                        var $a = $('<a />', {
                            'href': url,
                            'download': time + '_backup.html',
                            'text': "click"
                        }).hide().appendTo("body")[0].click();
                        setTimeout(function () {
                            URL.revokeObjectURL(url);
                        }, 10000);
                        compareDate = new Date();
                    },
                    error: function () {
                        alert("Fehler!");
                        $('#DatumsFeld').html("<h1>Keine Internetverbindung vorhanden!</h1>");
                    }
                });
                setTimeout(downloadGetraenkeBackup, 5000);
            }

            function downloadGetraenkeBackup() {
                console.log("Download Backup ...");
                var dt = new Date();
                var time = dt.getFullYear() + "-" + dt.getMonth() + "-" + dt.getDay() + "_" + dt.getHours() + "-" + dt.getMinutes() + "-" + dt.getSeconds();
                $.ajax({
                    url: "backup_getraenke.php",
                    type: "GET",
                    dataType: 'binary',
                    success: function (result) {
                        var url = URL.createObjectURL(result);
                        var $a = $('<a />', {
                            'href': url,
                            'download': time + '_backup.html',
                            'text': "click"
                        }).hide().appendTo("body")[0].click();
                        setTimeout(function () {
                            URL.revokeObjectURL(url);
                        }, 10000);
                    },
                    error: function () {
                        alert("Fehler!");
                        $('#DatumsFeld').html("<h1>Keine Internetverbindung vorhanden!</h1>");
                    }
                });
            }

            setInterval(function () {
                downloadSpeisen();
            }, 30000);
        </script>


    </head>
    <body onload="downloadSpeisen();">
        <h1>Backup Funktion - WICHTIG Pop Ups erlauben im Browser und regelmässig kontrollieren ob im Download Ordner Backups vorhanden sind</h1>
        <p>Alle 30 Sekunden wir die aktuelle Bestellliste vom Server abgerufen und lokal gespeichert.</p>
        <h4>Letzte Sicherung:</h4>
        <div id="DatumsFeld"></div>
        <div id="Fehlerausgabe"></div>
    </body>
</html>