<?php
require_once('auth.php');
?>

<!DOCTYPE html> 
<html>
    <head>
        <meta charset="utf-8">
        <title>Pos Bestellsystem</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="stylesheet" href="include/jquery/jquery.mobile-1.4.5.css" />
        <!--<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">-->

        <!--<link rel="stylesheet" href="own.css"/>-->
        <link rel="stylesheet" href="style.css"/>


        <script src="include/jquery/jquery-2.1.4.min.js"></script>
        <script src="include/jquery/jquery.mobile-1.4.5.js"></script>


        <script type="text/javascript">
            //Test HTML IndexedDB
            var request = indexedDB.open("speisekarte");

            request.onupgradeneeded = function () {
                // The database did not previously exist, so create object stores and indexes.
                var db = request.result;
                var store = db.createObjectStore("positionen", {keyPath: "rowid"});
                //var titleIndex = store.createIndex("by_title", "title", {unique: true});
                //var authorIndex = store.createIndex("by_author", "author");

                // Populate with initial data.
                store.put({Positionsname: "Grillhendl", Kurzbezeichnung: "Grillhendl", rowid: 71});
<?php
include_once ("include/db.php");


$sql1 = "SELECT * FROM positionen ORDER BY type, reihenfolge";
$result1 = mysqli_query($conn, $sql1);
$fontColour = "#000000";
$Colour = "#FFFFFF";

include_once ("include/db.php");
$i = 0;
while ($rowww = mysqli_fetch_assoc($result1)) {
    echo 'store.put({Positionsname: "' . utf8_encode($rowww['Positionsname'])
    . '", Kurzbezeichnung: "'
    . utf8_encode($rowww['Kurzbezeichnung']) . '", rowid: ' . $rowww['rowid'] . '});';
}
?>
                //store.put({title: "Water Buffaloes", author: "Fred", isbn: 234567});
                //store.put({title: "Bedrock Nights", author: "Barney", isbn: 345678});
            };

            request.onsuccess = function () {
                db = request.result;
            };
        </script>
        <script type="text/javascript">

            window.onbeforeunload = function (e) {
                //return 'Dialog text here.';
                console.log("Achtung Seite wird neu geladen!");
            };

            $(document).ready(function () {
                $.ajaxSetup({cache: false});
            });

            /*
             var Notification = window.Notification || window.mozNotification || window.webkitNotification;
             Notification.requestPermission();
             
             
             Notification.requestPermission(function (permission) {
             console.log(permission);
             });
             */
            $.mobile.defaultPageTransition = "fade";
            var AnzahlOffeneBestellungenSchank = 0;
            var AnzahlOffeneBestellungenKueche = 0;
            var PlaySound = false;
            var PlaySoundKueche = false;
            var PlaySoundSchank = false;
            var arrayZahlungGetrennt = [];

            $(document).on({
                ajaxStart: function () {
                    $.mobile.loading('show');
                },
                ajaxStop: function () {
                    $.mobile.loading('hide');
                }
                ,
                ajaxError: function () {
                    $.mobile.loading('hide');
                }
            });
            var Tischnummer = 0;
            var Summe = 0;
            var AnzahlBestellungenAktuell = -1;
            var AnzahlGetraenkeWartendAktuell = -1;
            var bestellungSQL = "";
            var bestellungTischnr = "";
            var Beilagen = "";
            var rowid = "";
            $(document).keypress(function (e) {
                if (e.which == 13) {
                    if ($(":mobile-pagecontainer").pagecontainer('getActivePage').prop("id") == "Kuechenansicht") {
                        Check = confirm("Bestellung von Tisch \n \n" + bestellungTischnr + "\n\nvollstaendig?");
                        if (Check == false) {
                            //return false;
                        } else {
                            //return true;
                            kuecheGesamtFertig(bestellungListe);
                        }
                    }

                    if ($(":mobile-pagecontainer").pagecontainer('getActivePage').prop("id") == "Schankansicht") {
                        Check = confirm("Bestellung von Tisch \n \n" + bestellungTischnr + "\n\nvollstaendig?");
                        if (Check == false) {
                            //return false;
                        } else {
                            //return true;
                            schankGesamtFertig(bestellungListe);
                            //alert(bestellungTischnr + ":  Bestellung Kueche abgeschlossen!");
                        }
                    }
                }
            });
            function fetchBestellungen() {
                $.getJSON('bestellungen_json.php', function (jd) {
                    $('#test').html('<h1>Temperatur: ' + jd.tempc + ' °C</h1><h1>Taupunkt: ' + jd.Taupunkt + ' °C</h1>')
                    $('#test').append('<h2>Luftfeuchtigkeit: ' + jd.humiditycurr + '%</h2>');
                });
            }

            function updateKapazitaet(position, kapazitaet) {

                kapazitaet = prompt("Neue Kapazitaet:", kapazitaet);
                console.log("Update Kapazität");
                if (kapazitaet !== null) {
                    $.ajax({
                        url: 'update_kapazitaet.php?rowid=' + position + '&kapazitaet=' + kapazitaet,
                        type: "get",
                        complete: function (data, responseText) {
                            AdminAnsicht();
                        },
                        success: function (data) {

                        },
                        dataType: "json"
                    });
                }
            }


            function updateSpeisekarte(tischnummer) {
                console.log("UpdateSpeisekarte");

                $.ajax({
                    url: 'tisch_json.php?tischnummer=' + tischnummer,
                    type: "get",
                    success: function (data) {
                        html = "";
                        html += '<div class="ui-grid-a">';
                        i = 0;
                        $.each(data, function (i, object) {
                            i++;
                            if (i === 1) {
                                html += '<div class="ui-block-a">';
                            } else {
                                html += '<div class="ui-block-b">';
                                i = 0;
                            }
                            //html+=;
                            html += '<button class="ui-btn ui-corner-all" onclick="saveBestellung(' + object['id'] + ',0,11);" select="" count(*)="" as="" cnt="" from="" `bestellungen`="" where="" `delete`="0" and="" position="9Anzahl153" style="white-space: normal; !important; color:#B18904; height: 80px;background:' + object['cl'] + ';">';
                            html += object["P"];
                            if (object["nr"] !== "") {
                                html += ' (' + object["nr"] + 'x)';
                            }

                            html += '</button>'



                            html += '</div>';




                            //alert(object["P"]);
                            //speisekarteTest <-- DIV

                        });
                        html += '</div>';
                        $('#speisekarteTest').html(html);
                    },
                    dataType: "json"
                });
            }


            $(document).ready(function () {
                $("#KuecheButton").click(function () {
                    Kuechenansicht();
                });
            });
            $(document).ready(function () {
                $("#SchankButton").click(function () {
                    SchankAnsicht();
                });
            });
            $(document).ready(function () {
                $("#TischeButton").click(function () {
                    TischAnsicht();
                });
            });
            $(document).ready(function () {
                $("#RechnerButton").click(function () {
                    RechnerAnsicht();
                });
            });
            $(document).ready(function () {
                $("#AdminButton").click(function () {
                    AdminAnsicht();
                });
            });

            function bestellungAbschicken(tischnummer) {
                console.log("bestellung Abschicken " + tischnummer);

                dataString = "tischnummer=" + tischnummer;
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "bestellung_abschicken.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {
                        //todo: Gehe zu Tische
                        TischAnsicht();
                        $.mobile.changePage("#listTische", {transition: "slideup", changeHash: false});
                    },
                    error: onError
                });
            }
			
			function bestellungKUAbschicken(tischnummer) {
                console.log("bestellung KUECHE Abschicken " + tischnummer);

                dataString = "tischnummer=" + tischnummer;
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "bestellung_abschicken.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {
                        //todo: Gehe zu Tische
                        KuechenansichtRefresh();
                    },
                    error: onError
                });
            }
			
			function bestellungSAAbschicken(tischnummer) {
                console.log("bestellung SCHANK Abschicken " + tischnummer);

                dataString = "tischnummer=" + tischnummer;
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "bestellung_abschicken.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {
                        //todo: Gehe zu Tische
                        SchankAnsichtRefresh();
                    },
                    error: onError
                });
            }

            function saveNeuerTisch() {
                neuerTischName = document.getElementById('neuerTischName').value;
                neueTischFarbe = $('#neueTischFarbe').val();
                neueTischX = $('#neueTischX').val();
                neueTischY = $('#neueTischY').val();
                dataString = "neuerTischName=" + neuerTischName
                        + "&neueTischFarbe=" + neueTischFarbe
                        + "&neueTischX=" + neueTischX
                        + "&neueTischY=" + neueTischY;


                if (neuerTischName.length > 0) {
                    $.ajax({
                        type: "POST",
                        async: false,
                        dataType: "text",
                        url: "neuerTisch_save.php",
                        cache: false,
                        data: dataString,
                        complete: function (data, responseText) {
                            document.getElementById('neuerTischName').value = "";
                            $('#neueTischFarbe').val("");
                            $('#neueTischX').val("");
                            $('#neueTischY').val("");

                        },
                        success: function (text)
                        {
                            response = text;
                            alert(response);
                        },
                        error: onError

                    });
                } else {
                    alert("Eingabefeld darf nicht leer sein!");
                }

            }

            function saveBestellung(position, tab, tischnummer, fertig) {
                console.log("saveBestellung()");
                $.mobile.loading('show');
                dataString = "Tischnummer=" + tischnummer + "&positionsid=" + position + "&Zusatzinfo=" + Beilagen + "&kuechefertig=" + fertig;
                console.log(dataString);
                //Wenn Speisen gespeichert werden sollen...

                /*
                 if (tab === 1) {
                 $("#popupDialog2Content").html("loading ...");
                 $("#popupDialog2Content").load("beilagen.php?position=" + position);
                 $("#popupDialog2").popup("open", {positionTo: '#TischAnzeigen'});
                 //popupDialog2Content <= in dieses Div sollen die Beilagen kommen
                 
                 }
                 */

                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "bestellung_save.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {
                        if (fertig === 1) {
                            $('#TischAnzeigenDirektverkauf').tabs('load', tab);
                        } else {
                            $('#TischAnzeigen').tabs('load', tab);
                        }
                        console.log("Bestellung saved for Tisch " + tischnummer);
                        Summe = 0;
                    },
                    error: onError
                });
            }

            function saveBestellung2(position, tab, tischnummer) {
                $.mobile.loading('show');
                dataString = "Tischnummer=" + Tischnummer + "&positionsid=" + position + "&Zusatzinfo=" + Beilagen;
                //Wenn Speisen gespeichert werden sollen...
                alert(Beilagen);
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "bestellung_save.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {
                        $('#TischAnzeigen').tabs('load', tab);
                        Summe = 0;
                        Beilagen = "";
                    },
                    error: onError
                });
            }

            function updateZusatzinfo(info) {

                alert(rowid);
                dataString = 'Zusatzinfo=' + Beilagen + '&rowid=' + rowid;
                //alert(text);
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "saveZusatzinfo.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {

                    },
                    success: function (text)
                    {
                        rowid = "";
                        response = text;
                        alert(response);
                    },
                    error: onError
                });
            }


            function saveZusatzinfo(text, rowid) {

                dataString = 'Zusatzinfo=' + text + '&rowid=' + rowid;
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "saveZusatzinfo.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {

                    },
                    success: function (text)
                    {
                        response = text;
                        TischAnsichtHistory();
                    },
                    error: onError
                });
            }

            function BenutzerNeu() {
                dataString = "username=" + document.getElementById('username').value + "&password=" + document.getElementById('password').value + "&password_again=" + document.getElementById('password_again').value + "&admin=" + document.getElementById('adminyesno').value;
                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "register.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {

                    },
                    success: function (text)
                    {
                        response = text;
                        alert(response);
                        $('#username').val("");
                        $('#password').val("");
                        $('#password_again').val("");
                    },
                    error: onError
                });
            }

            function ProduktNeu() {
                //dataString = "username=" + document.getElementById('username').value + "&password=" + document.getElementById('password').value + "&password_again=" + document.getElementById('password_again').value;
                dataString = "Positionsname=" + $('#Positionsname').val() + "&Betrag=" + $('#Betrag').val() + "&type=" + $('#produktkategorie').val() + "&Kapazitaet=" + $('#Kapazitaet').val();

                $.ajax({
                    type: "POST",
                    async: true,
                    dataType: "text",
                    url: "produktNeu.php",
                    cache: false,
                    data: dataString,
                    complete: function (data) {

                    },
                    success: function (text)
                    {
                        response = text;
                        //alert(response);
                        $('#produktname').val("");
                        $('#produktkategorie').val("");
                        $('#produktpreis').val("");
                        $('#Kapazitaet').val("");
                        AdminAnsicht();
                    },
                    error: onError
                });
            }

            function ProduktLoeschen(rowid) {
                var r = confirm("Wirklich löschen?");
                if (r == true) {
                    dataString = "rowid=" + rowid;

                    $.ajax({
                        type: "POST",
                        async: true,
                        dataType: "text",
                        url: "produkt_loeschen.php",
                        cache: false,
                        data: dataString,
                        complete: function (data) {

                        },
                        success: function (text)
                        {
                            response = text;
                            //alert(response);
                            AdminAnsicht();
                        },
                        error: onError
                    });
                } else {
                    //"You pressed Cancel!";
                }


            }

            function kuecheFertig(rowid) {
                console.log("KuecheFertig()" + rowid)
                $.ajax({
                    type: "GET",
                    url: "kueche_fertig.php?rowid=" + rowid,
                    cache: false,
                    complete: function (data) {
                        //KuecheFertig();
                        KuechenansichtRefresh();
                    },
                    error: onError
                });
            }

            function BestellungBezahlt(arrayListe, direktverkauf) {
                $.ajax({
                    type: "POST",
                    url: "BestellungBezahlt.php",
                    data: {listePositionen: arrayListe},
                    cache: false,
                    //data: formData,
                    complete: function (data) {
                        if (direktverkauf === 1) {
                            Direktverkauf();
                        } else {
                            TischBezahlen();
                        }
                    },
                    error: onError
                });
                /*
                 * REMOVED FOR IOS FRANZ PROBLEM
                 Check = confirm("Zahlen?");
                 if (Check == false) {
                 //return false;
                 } else {
                 //return true;
                 
                 }*/


            }

            function schankGesamtFertig(arrayListe, tischnummer) {
                $.ajax({
                    type: "POST",
                    url: "kueche_fertig_tisch.php",
                    data: {listePositionen: arrayListe, tischnummer: tischnummer},
                    cache: false,
                    complete: function (data) {
                        SchankAnsichtRefresh();
                    },
                    error: onError
                });
            }

            function kuecheGesamtFertig(arrayListe, tischnummer) {
                $.ajax({
                    type: "POST",
                    url: "kueche_fertig_tisch.php",
                    data: {listePositionen: arrayListe, tischnummer: tischnummer},
                    cache: false,
                    //data: formData,
                    complete: function (data) {
                        KuechenansichtRefresh();
                    },
                    error: onError
                });
            }

            function KuecheFertig() {
                setTimeout("Kuechenansicht()", 300);
            }

            function SchankFertig(rowid) {
                $.ajax({
                    type: "GET",
                    url: "kueche_fertig.php?rowid=" + rowid,
                    cache: false,
                    //data: formData,
                    complete: function (data) {
                        SchankAnsichtRefresh();
                    },
                    error: onError
                });
            }

            function bestellungLoeschen(rowid, tischnummer) {
                $.ajax({
                    type: "GET",
                    url: "bestellung_loeschen.php?rowid=" + rowid,
                    cache: false,
                    complete: function (data) {
                        Summe = 0;
                        TischAnsichtHistory();
                    },
                    error: onError
                });
                /* DEBUG REMOVED FOR IOS FRANZ PROBLEM
                 var r = confirm("Bestellung wirklich stornieren?");
                 if (r == true) {
                 
                 }*/
            }

            function onSuccess()
            {
                alert("erfolg!");
                data = $.trim(data);
            }

			function Direkt_reset() {

                if (confirm('Bestellungen reseten?')) {
					dataString = "cmd=direkt_reset";
                    $.ajax({						
						url: 'Direkt_reset.php',
						type: "GET",
						data: dataString,
						complete: function (data) {
							Direktverkauf();
						},
						dataType: "json"
					});
                } else {
                    alert("Keine Änderungen!");
                }
            }
			
            function resetBestellungen() {

                if (confirm('Wirklich alle Bestellungen löschen?')) {
                    if (confirm('Wirklich?')) {
                        dataString = "cmd=reset";
                        $.ajax({
                            url: 'reset.php',
                            type: "GET",
                            data: dataString,
                            complete: function (data, responseText) {

                                alert("Bestellungen zurückgesetzt!");
                            },
                            success: function (data) {

                            },
                            dataType: "json"
                        });
                    }
                } else {
                    alert("Keine Änderungen!");
                }
            }


            function onError(data, status)
            {
                alert("Fehler: Der Eintrag konnte nicht gespeichert werden!");
            }

		function logout() 
		{
			console.log("logout");
			window.location.href = "logout.php";
		}

        </script>
    </head>
    <body>
        <audio id="sound1" src="doorbell-1.ogg"></audio>
    	<audio id="sound2" src="doorbell-2.ogg"></audio>
        <!--
        <div data-role="page">
            <div data-role="header">Header</div>
            <div role="main" class="ui-content"><h1>Überschrift</h1>
                <a href="#popupDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-delete ui-btn-icon-left ui-btn-b">Delete page...</a>
                <div data-role="popup" id="popupDialog" data-overlay-theme="b" data-theme="b" data-dismissible="false" style="max-width:400px;">
                        <div data-role="header" data-theme="a">
                            <h1>Delete Page?</h1>
                            </div>
                        <div role="main" class="ui-content">
                                <h3 class="ui-title">Are you sure you want to delete this page?</h3>
                            <p>This action cannot be undone.</p>
                                <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>
                                <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back" data-transition="flow">Delete</a>
                            </div>
                </div>
            </div>
            <div data-role="footer">Footer</div>
        </div>
        -->

        <div data-role="page" id="indexPage">

            <div data-role="header" data-position="fixed">
                <h1><p><?=$FFName?> <?=$Titellogin?></p></h1>
            </div>

            <div data-role="content">


                <ul data-role="listview" data-inset="true">
                    <li>
                        <a href="#listTische" id="TischeButton">Tische</a>
                    </li>
                    <li>
                        <a href="#Kuechenansicht" id="KuecheButton">K&uuml;che</a>
                    </li>
                    <li>
                        <a href="#Schankansicht" id="SchankButton">Schank</a>
                    </li>
                    <li>
                        <a href="#Direktverkauf" id="DirektverkaufButton" onclick="Direktverkauf()">Direktverkauf</a>
                    </li>    
                    <li>
                        <a href="#myOrdersPage" id="myOrdersButton" onclick="myOrdersAnsicht();">Meine Bestellungen</a>
                    </li>
                    <?php
                    if ($_SESSION['admin'] == 1) {
                        echo '<li>
                        <a href="#adminPage" id="AdminButton">Admin</a>
                    </li>';
                    }
                    ?>
                    <li>
                        <a href="backup_download.php" target="_blank">Sicherung Starten</a>
                    </li>
                    <li>
                        <a onclick="logout();" class="ui-btn ui-btn-b" >als <?php echo htmlspecialchars($_SESSION['user']['username']); ?> abmelden</a>
                    </li>


                </ul>
                <!--<button onclick="updateSpeisekarte(11);">Speisekarte</button>-->
                <div id="speisekarteTest">

                </div>
            </div>

            <div data-role="footer">
                <h1></h1>
            </div>
        </div>

        <div data-role="page" id="adminPage">
            <a href="#indexPage">zurueck</a>
        </div>


        <div data-role="page" id="listTisch">

        </div>


        <div data-role="page" id="Direktverkauf">
            <a href="#indexPage">zurueck</a>

        </div>

        <div data-role="page" id="myOrdersPage">
            <a href="#indexPage">zurueck</a>
        </div>

        <div data-role="page" id="TischHistory">
            <a href="#indexPage">zurueck</a>
        </div>

        <div data-role="page" id="Kuechenansicht">
            <script>
                $(document).ready(function () {
                    Kuechenansicht();
                });
            </script>
        </div>

        <div data-role="page" id="Schankansicht">
            <a href="#indexPage">zurueck</a>

        </div>

        <div data-role="page" id="listTische">
            <a href="#indexPage">zurueck</a>

        </div>

        <div data-role="page" id="RechnerAnsicht">
            <a href="#indexPage">zurueck</a>
        </div>

        <div data-role="page" id="KuecheHistory">
        </div>

        <div data-role="page" id="SchankHistory">
        </div>
		
		<div data-role="page" id="DirektHistory">
        </div>
		

        <div data-role="page" id="listTischBestellungen">
            <a href="#listTische">zurueck</a>
        </div>


        <script type="text/javascript">

            /*
             * 
             $(document).ready(function () {
             $('#btnReload').click(function () {
             location.reload();
             });
             });
             
             
             function RechnerAnsicht() {
             $('#RechnerAnsicht').load('rechner.php', function () {
             $('#RechnerAnsicht').trigger('create');
             });
             }
             
             
             $('#listTische').load('list_tische.php', function () {
             $('#listTische').trigger('create');
             //$('#listTische').load("list_tische.php");
             });
             */

            /*
             $(document).on("#listTische", function () {
             // alert("pageshow event fired - pagetwo is now shown");
             TischAnsicht();
             
             });
             $("#listTische").ready(function () {
             TischAnsicht();
             });
             **/
            /*
             $('#listTische').load('list_tische.php', function () {
             $('#listTische').trigger('create');
             });
             $('#myOrdersPage').load('myOrders.php', function () {
             $('#myOrdersPage').trigger('create');
             });
             */


            function offeneBestellungen() {

                $('#offeneBestellungen').load('list_Bestellungen.php?tischnummer=' + Tischnummer, function () {
                    $('#offeneBestellungen').trigger('create');
                });
            }
            function TischAnsichtHistory() {
                //$(".text").html("loading");
                $("#Bestellungen").load('list_Bestellungen.php?tischnummer=' + Tischnummer);
            }

            function TischBezahlen() {
                $("#Bestellungen").load('list_BestellungenZahlen.php?tischnummer=' + Tischnummer);
            }


            function tisch() {
                $("#listTischBestellungen").html("loading ...");
                $('#listTischBestellungen').load('tisch_anzeigen.php?tischnummer=' + Tischnummer, function () {
                    $('#listTischBestellungen').trigger('create');
                });
                $.mobile.changePage('#listTischBestellungen');
            }

            function tischnr(nummer) {
                $('#listTischBestellungen').load('tisch_anzeigen.php?tischnummer=' + nummer, function () {
                    $('#listTischBestellungen').trigger('create');
                });
                $.mobile.changePage('#listTischBestellungen');
            }
            function TischAnsicht() {
                console.log("TischAnsicht()");
                //$("#listTische").html("loading ...");
                $.mobile.loading('show');
                $('#listTische').load('list_tische.php', function () {
                    $('#listTische').trigger('create');
                });
                $.mobile.loading('hide');
            }
            function AdminAnsicht() {
                $('#adminPage').load('admin.php', function () {
                    $('#adminPage').trigger('create');
                });
            }
            function myOrdersAnsicht() {
                $('#myOrdersPage').load('myOrders.php', function () {
                    $('#myOrdersPage').trigger('create');
                });
            }


            function SchankAnsicht() {
                $('#Schankansicht').load('list_schank.php', function () {
                    $('#Schankansicht').trigger('create');
                    if ($(":mobile-pagecontainer").pagecontainer('getActivePage').prop("id") == "Schankansicht") {
                        if (AnzahlGetraenkeWartendAktuell == 0) {
                            setTimeout("SchankAnsicht()", 2000);
                        } else if (AnzahlGetraenkeWartendAktuell < 10 && AnzahlGetraenkeWartendAktuell != -1) {
                            setTimeout("SchankAnsicht()", 2000);
                        } else if (AnzahlGetraenkeWartendAktuell < 20 && AnzahlGetraenkeWartendAktuell != -1) {
                            setTimeout("SchankAnsicht()", 10000);
                        } else if (AnzahlGetraenkeWartendAktuell >= 20) {
                            setTimeout("SchankAnsicht()", 45000);
                        } else {
                            setTimeout("SchankAnsicht()", 5000);
                        }
                    }
                });
            }

            function Kuechenansicht() {
                $('#Kuechenansicht').load('list_kueche.php', function () {
                    $('#Kuechenansicht').trigger('create');
                    if ($(":mobile-pagecontainer").pagecontainer('getActivePage').prop("id") == "Kuechenansicht") {

                        //alert(AnzahlOffeneBestellungenSchank + "neuer Eintrag!");


                        if (AnzahlBestellungenAktuell === 0) {
                            setTimeout("Kuechenansicht()", 5000);
                        } else if (AnzahlBestellungenAktuell < 10 && AnzahlBestellungenAktuell != -1) {
                            setTimeout("Kuechenansicht()", 2000);
                        } else if (AnzahlBestellungenAktuell < 25 && AnzahlBestellungenAktuell != -1) {
                            setTimeout("Kuechenansicht()", 10000);
                        } else if (AnzahlBestellungenAktuell >= 25 && AnzahlBestellungenAktuell < 20) {
                            setTimeout("Kuechenansicht()", 15000);
                        } else {
                            setTimeout("Kuechenansicht()", 5000);
                        }

                    }
                });
            }


            function Direktverkauf() {
                //$("#KuecheHistory").html("loading ...");
                $('#Direktverkauf').load('direktverkauf.php', function () {
                    $('#Direktverkauf').trigger('create');
                });
                $.mobile.changePage('#Direktverkauf');
            }

            function printSinglePositionen(arrayListe, tischnummer) {

                $.ajax({
                    type: "POST",
                    url: "print_single_positionen.php",
                    data: {listePositionen: arrayListe, tischnummer: tischnummer},
                    cache: false,
                    //data: formData,
                    complete: function (data) {
                    },
                    error: onError
                });
            }

            function KuecheHistory() {
                //$("#KuecheHistory").html("loading ...");
                $('#KuecheHistory').load('kueche_history.php', function () {
                    $('#KuecheHistory').trigger('create');
                });
            }

            function SchankHistory() {
                //$("#KuecheHistory").html("loading ...");
                $('#SchankHistory').load('schank_history.php', function () {
                    $('#SchankHistory').trigger('create');
                });
            }
			
			function DirektHistory() {
                //$("#KuecheHistory").html("loading ...");
                $('#DirektHistory').load('direkt_history.php', function () {
                    $('#DirektHistory').trigger('create');
                });
            }

            function KuechenansichtRefresh() {
                $('#Kuechenansicht').load('list_kueche.php', function () {
                    $('#Kuechenansicht').trigger('create');
                });
            }

            function SchankAnsichtRefresh() {
                $('#Schankansicht').load('list_schank.php', function () {
                    $('#Schankansicht').trigger('create');
                });
            }

            function updatePW(userid) {

                pwneu = prompt("Neues Passwort:");

                if (pwneu == null || pwneu == "") {
                    console.log("pw edit cancled");
                } else {
                    dataString = 'pw=' + pwneu + "&userid=" + userid;
                    $.ajax({
                        url: 'update_pw.php',
                        type: "POST",
                        data: dataString,
                        complete: function (data, responseText) {
                            alert("Passwort geaendert");
                        },
                        success: function (data) {

                        },
                        dataType: "json"
                    });
                }
            }


        </script>
    </body>
</html>
