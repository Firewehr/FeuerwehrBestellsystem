<?php
require_once('auth.php');
error_reporting(E_ALL);
?>

<div data-role="header">
    <h1>Schank</h1>
    <a href="#indexPage" onclick="AnzahlBestellungenAktuell = -1;" class="ui-btn-left"> Zur&uuml;ck </a>
	<a href="#SchankHistory" onclick="SchankHistory();" class="ui-btn-right">Historie</a>
</div>

<div class="ui-grid-a ui-responsive">
    <div class="ui-block-a">
        <div class="ui-bar ui-bar-a">

            <div class="ui-grid-a" >

                <?php
                error_reporting(E_ALL);
                $tischnummerselect = "";
                try {
                    include_once ("include/db.php");
                    $counter = 0;
                    $sql = "SELECT `positionen`.`type`,tische.tischname,"
                            . "bestellungen.tischnummer, "
                            . "`bestellungen`.`bestellt`, "
                            . "FLOOR( UNIX_TIMESTAMP(  bestellungen.zeitstempel ) /300 ) AS t, "
                            . "COUNT( * ), kellner "
                            . "FROM bestellungen "
                            . "JOIN positionen ON bestellungen.position=positionen.rowid "
                            . "JOIN tische ON tische.tischnummer=bestellungen.tischnummer "
                            . "WHERE "
                            . "`delete`=0 "
                            . "AND `kueche`=0 "
                            . "AND `type`=2 GROUP BY t, "
                            . "bestellungen.tischnummer "
                            . "ORDER BY bestellungen.zeitstempel, t LIMIT 50";
                    //echo $sql;

                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $Bestellungen = "";

                        $t = $row['t'];

                        if ($tischnummerselect != $row['tischnummer']) {
                            if ($row['bestellt'] == 1) {
                                echo '<div style="background-color: #0ed615;" class="ui-block-a">';
                            } else {
                                echo '<div style="background-color: #ff0000;" class="ui-block-a">';
                            }

                            echo '<h2 style="font-size:30px">Tisch: ' . $row['tischname'] . '</h2>'; //. ' (#' . $row['tischnummer'] . 
                            $tischname = $row['tischname'];
                            echo '</div>';
                            echo '<div class="ui-block-b">';
                            echo '<p>KellnerIn: ' . $row['kellner'] . '</p>';
                            echo '</div>';
                        }

                        echo '<div class="ui-block-a">';

                        $tischnummerselect = $row['tischnummer'];
						
						//Abfrage ohne Group um die einzelnen ID's zu bekommen
                        $query23 = "SELECT `bestellungen`.`zeitKueche`, "
                                . "`bestellungen`.`position`, "
                                . "`bestellungen`.`tischnummer`, "
                                . "`bestellungen`.`zeitstempel`, "
                                . "`positionen`.`rowid`, "
                                . "`positionen`.`Positionsname`, "
                                . "`positionen`.`type`, "
                                . "`bestellungen`.`kueche`, "
                                . "`bestellungen`.`delete`, "
                                . "`bestellungen`.`rowid`, "
                                . "FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  "
                                . "FROM bestellungen, positionen "
                                . "WHERE bestellungen.position=positionen.rowid "
                                . "AND `type`=2 "
                                // Ansonsten druckt er nicht bei "Gesamt Fertig" die bereits hergerichteten Speisen#. "AND bestellungen.zeitKueche='0000-00-00 00:00:00' "
								. "AND bestellungen.print <> 1 "
                                . "AND bestellungen.ausgeliefert=0 "
                                . "AND positionen.type=2 "
                                . "AND bestellungen.delete=0 "
                                . "AND bestellungen.tischnummer=" . $tischnummerselect . " "
                                . "AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " "
                                . "ORDER BY positionen.Positionsname ASC";
                        $result23 = mysqli_query($conn, $query23);
                        $arrayListe = '[';

                        while ($row23 = mysqli_fetch_assoc($result23)) { //Ausgabe der offenen Bestellungen eines Tisches
                            $Bestellungen = $Bestellungen . $row23['rowid'] . " OR rowid=";
                            $arrayListe = $arrayListe . $row23['rowid'] . ',';
                            $timestampBestellung = $row23['zeitstempel'];
                            $tischnr = $row23['tischnummer'];
                        }

						$arrayListe = substr($arrayListe, 0, -1);
                        $arrayListe = $arrayListe . ']';

                        $sql2 = "SELECT COUNT( * ) AS anzahl, "
                                . "`bestellungen`.`zeitKueche`, "
                                . "positionen.Kurzbezeichnung, "
                                . "`bestellungen`.`position`, "
                                . "bestellungen.Zusatzinfo, "
                                . "`bestellungen`.`tischnummer`, "
                                . "`bestellungen`.`zeitstempel`, "
                                . "`positionen`.`rowid`, "
                                . "`positionen`.`Positionsname`, "
                                . "`positionen`.`type`, "
                                . "`bestellungen`.`kueche`, "
                                . "`bestellungen`.`delete`, "
                                . "`bestellungen`.`rowid`, "
								. "`bestellungen`.`bestellt`, "
                                . "FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  "
                                . "FROM bestellungen, positionen "
                                . "WHERE bestellungen.position=positionen.rowid "
                                . "AND `type`=2 "
                                . "AND bestellungen.zeitKueche='0000-00-00 00:00:00' "
                                . "AND bestellungen.ausgeliefert=0 "
                                . "AND positionen.type=2 "
                                . "AND bestellungen.delete=0 "
                                . "AND bestellungen.tischnummer=" . $tischnummerselect . " "
                                . "AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " "
                                . "GROUP BY Zusatzinfo, Positionsname "
                                . "ORDER BY positionen.Positionsname ASC";

                        $result2 = mysqli_query($conn, $sql2);

                        while ($row2 = mysqli_fetch_assoc($result2)) { //Ausgabe der offenen Bestellungen eines Tisches
                            echo '<input style="background-color:#FFFF99; color:#f00;" type="button" value="(' . $row2['anzahl'] . 'x) ' . utf8_encode($row2['Positionsname']);
                            if (!empty($row2['Zusatzinfo'])) {
                                echo '<br/> (' . $row2['Zusatzinfo'] . ') ';
                            }
							if (mysqli_num_rows($result2)==1 && $row2['anzahl'] == 1 && $row2['bestellt'] == 1) {
							echo '" onclick="schankGesamtFertig(' . $arrayListe . ');"/>';
							}
							elseif (mysqli_num_rows($result2)==1 && $row2['anzahl'] == 1 && $row2['bestellt'] == 0) {
							echo '"onclick="bestellungSAAbschicken(' . $tischnummerselect . ');"/>';
							}
							else {
                            echo '" onclick="SchankFertig(' . $row2['rowid'] . ');"/>';
							}
                            $timestamp = strtotime($row2['zeitstempel']);
                        }
                        
                        echo '</div>';
                        echo '<div class="ui-block-b">';



                        $query = "SELECT bestellungen.kueche, "
                                . "COUNT( * ) AS anzahl, "
                                . "`bestellungen`.`zeitKueche`, "
                                . "bestellungen.Zusatzinfo, "
                                . "`bestellungen`.`position`, "
                                . "`bestellungen`.`tischnummer`, "
                                . "`bestellungen`.`zeitstempel`, "
                                . "`bestellungen`.`print`, "
                                . "`positionen`.`rowid`, "
                                . "`positionen`.`Positionsname`, "
                                . "`positionen`.`type`, "
                                . "`bestellungen`.`kueche`, "
                                . "`bestellungen`.`delete`, "
                                . "`bestellungen`.`rowid`, "
                                . "FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  "
                                . "FROM bestellungen, positionen "
                                . "WHERE bestellungen.position=positionen.rowid "
                                . "AND bestellungen.ausgeliefert=0 "
                                . "AND positionen.type=2 "
                                . "AND bestellungen.print=2 "
                                . "AND bestellungen.delete=0 "
                                . "AND bestellungen.zeitKueche!='0000-00-00 00:00:00' "
                                . "AND bestellungen.tischnummer=" . $tischnummerselect . " "
                                . "AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " "
                                . "GROUP BY Zusatzinfo, bestellungen.position "
                                . "ORDER BY `bestellungen`.`zeitKueche` DESC";

                        $result2 = mysqli_query($conn, $query);

                        $printListe = '[';


                        while ($row2 = mysqli_fetch_assoc($result2)) { //Ausgabe der bereits gerichteten Bestellungen
                            if ($row2['kueche'] == 1) {
                                //bestellung wartend

                                echo '<input class="durchgestrichen" style="text-decoration: line-through;text-decoration: overline line-through; background-color:#009933; color:#f00;" type="button" value="' . ' (' . $row2['anzahl'] . 'x) ' . utf8_encode($row2['Positionsname']) . ' (' . $row2['Zusatzinfo'] . ')"/>';
                            } else {
                                //fertig

                                echo '<input style="text-decoration: line-through; text-decoration: overline line-through; background-color:#009933; color:#f00;" type="button" value="' . ' (' . $row2['anzahl'] . 'x) ' . utf8_encode($row2['Positionsname']) . '"/>';
                            }


                            $timestamp = strtotime($row2['zeitstempel']);
                        }

                        //Für Print Befehl  
                        //Bereits hergerichtete Positionen
                        $query = "SELECT bestellungen.kueche, "
                                . ""
                                . "`bestellungen`.`zeitKueche`, "
                                . "bestellungen.Zusatzinfo, "
                                . "`bestellungen`.`position`, "
                                . "`bestellungen`.`tischnummer`, "
                                . "`bestellungen`.`zeitstempel`, "
                                . "`bestellungen`.`print`, "
                                . "`positionen`.`rowid`, "
                                . "`positionen`.`Positionsname`, "
                                . "`positionen`.`type`, "
                                . "`bestellungen`.`kueche`, "
                                . "`bestellungen`.`delete`, "
                                . "`bestellungen`.`rowid`, "
                                . "FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  "
                                . "FROM bestellungen, positionen "
                                . "WHERE bestellungen.position=positionen.rowid "
                                . "AND bestellungen.ausgeliefert=0 "
                                . "AND positionen.type=2 "
                                . "AND bestellungen.delete=0 "
                                . "AND bestellungen.zeitKueche!='0000-00-00 00:00:00' "
                                . "AND bestellungen.tischnummer=" . $tischnummerselect . " "
                                . "AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " "
                                . ""
                                . "ORDER BY `bestellungen`.`zeitKueche` DESC";

                        $result6 = mysqli_query($conn, $query);



                        while ($row2 = mysqli_fetch_assoc($result6)) { //Ausgabe der bereits gerichteten Bestellungen
                            if ($row2['kueche'] == 1) {
                                //bestellung wartend
                                //echo $row2['print'];
                                if ($row2['print'] != '1') {
                                    $printListe = $printListe . $row2['rowid'] . ',';
                                }
                            } else {
                                //fertig
                            }


                            $timestamp = strtotime($row2['zeitstempel']);
                        }



                        $printListe = substr($printListe, 0, -1);
                        $printListe = $printListe . ']';

                        echo '</div>';

                        echo '<div class="ui-block-a">';
                        $Bestellungen = substr($Bestellungen, 0, -10);

                        if ($counter == 0) {
                            echo '<script type="text/javascript">'
                            . 'bestellungListe=' . $arrayListe . ';'
                            . 'bestellungTischnr="' . $tischname . '";</script>';
                        } else {
                            //echo '<script type="text/javascript"></script>';
                        }

                        $counter++;
                        if ($row['bestellt'] == 1) {
                            echo '<input style="background-color:#00FF6A; color:#f99;" type="button" value="Gesamt Fertig" '
                            . 'onclick="schankGesamtFertig(' . $arrayListe . ',' . $tischnr . ');"/>';
                        } else {
							echo '<input style="background-color: #8904B1" type="button" value="FORCE Bestellung" onclick="bestellungSAAbschicken(' . $tischnummerselect . ');"/>';
                        }
                        echo '<br><h1>&nbsp;</h1>';
                        echo '</div>';

                        echo '<div class="ui-block-b">';

                        echo '<input style="background-color:#cc6600; color:#f99;" type="button" value="Drucken" '
                        . 'onclick="printSinglePositionen(' . $printListe . ',' . $tischnummerselect . ');"/>'
                        . '<p>(wartend:' . gmdate("i:s", (time() - $timestamp)) . ')</p>';
                        echo '</div>';
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                try {
                    include_once ("include/db.php");
                    $sql5 = "SELECT COUNT( * ) AS anzahl "
                            . "FROM bestellungen "
                            . "JOIN positionen ON bestellungen.position = positionen.rowid "
                            . "WHERE "
                            . "positionen.type=2 "
                            . "AND bestellungen.delete=0 "
                            . "AND `kueche`=0";
                    $query5 = mysqli_query($conn, $sql5);
                    while ($row = mysqli_fetch_assoc($query5)) {
                        if ($row['anzahl'] <= 30) {
                            $bgcolor = "#f9f9f9";
                            $colorAnzahl = "#000000";
                        }
                        if ($row['anzahl'] > 30 && $row['anzahl'] < 80) {
                            $colorAnzahl = "#FFFFFF";
                            $bgcolor = "#FFBF00";
                        }
                        if ($row['anzahl'] >= 80) {
                            $bgcolor = "#FF0000";
                            $colorAnzahl = "#FFFFFF"; //rot
                        }
                        $wartendeBestellungen = $row['anzahl'];
                        echo '<script type="text/javascript">AnzahlOffeneBestellungenSchank=' . $wartendeBestellungen . ';</script>';
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                ?>
            </div>
        </div></div><div class="ui-block-b"><div class="ui-bar ui-bar-a" style="background-color:<?php echo $bgcolor; ?>"><?php
                echo '<p style="font-size:30px;color:' . $colorAnzahl . '" align="center">' . $wartendeBestellungen . ' Positionen wartend</p>';
                echo '<script type="text/javascript">AnzahlBestellungenAktuell="' . $wartendeBestellungen . '"</script>';
                try {
                    include_once ("include/db.php");
                    $sql6 = "SELECT bestellungen.position,"
                            . " positionen.rowid,"
                            . " positionen.positionsname,"
                            . " COUNT( * ) AS anzahl"
                            . " FROM positionen, bestellungen"
                            . " WHERE bestellungen.position = positionen.rowid"
                            . " AND bestellungen.kueche=0"
                            . " AND positionen.type=2"
                            . " AND bestellungen.delete=0"
                            . " GROUP BY bestellungen.position"
                            . " ORDER BY anzahl DESC";
                    //echo $sql6;
                    $query6 = mysqli_query($conn, $sql6);
                    while ($row = mysqli_fetch_assoc($query6)) {

                        if ($row['anzahl'] <= 10) {
                            $bgcolor = "#776F6F";
                            $colorAnzahl = "#FFFFFF";
                        }
                        if ($row['anzahl'] > 10 && $row['anzahl'] < 25) {
                            $colorAnzahl = "#FFFFFF";
                            $bgcolor = "#FFBF00";
                        }
                        if ($row['anzahl'] >= 25) {
                            $bgcolor = "#FF0000";
                            $colorAnzahl = "#FFFFFF"; //rot
                        }

                        echo '<input style="background-color: ' . $bgcolor . ';" type="button" value="' . $row['anzahl'] . 'x ' . utf8_encode($row['positionsname']) . '"/>';
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                ?></div></div>
</div>
<script>

    if (AnzahlOffeneBestellungenSchank < 1) {
        PlaySoundSchank = true;
    }

    if (AnzahlOffeneBestellungenSchank > 0 && PlaySoundSchank === true) { //
        document.getElementById("sound1").play();
        //alert(AnzahlOffeneBestellungenSchank + "neuer Eintrag!");

        //Notification if Supported by the Browser

        /*
         if (!("Notification" in window)) {
         //alert("This browser does not support desktop notification");
         } else if (Notification.permission === "granted") {
         // If it's okay let's create a notification
         var notification = new Notification("Neue Bestellung!");
         } else if (Notification.permission !== 'denied') { // Otherwise, we need to ask the user for permission
         Notification.requestPermission(function (permission) {
         // If the user accepts, let's create a notification
         if (permission === "granted") {
         //var notification = new Notification("Hi there!");
         }
         });
         
         }
         */

        PlaySoundSchank = false;
    }
</script>
<?php
echo "</div>";
?>
