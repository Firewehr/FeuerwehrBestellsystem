<?php
require_once('auth.php');
error_reporting(E_ALL);
?>

<div data-role="header">
    <h1>Schank</h1>
    <a href="#indexPage" onclick="AnzahlBestellungenAktuell = -1;" class="ui-btn-left"> Zur&uuml;ck </a>
    <a onclick="SchankAnsicht();" class="ui-btn-right"> Aktualisieren </a>
</div>

<div class="ui-grid-a ui-responsive">
    <table>
        <tr>
            <td width="85%">

                <div class="ui-bar ui-bar-a">

                    <div class="ui-grid-a" >

                        <?php
                        error_reporting(E_ALL);
                        $tischnummerselect = "";
                        try {
                            include_once ("include/db.php");
                            $counter = 0;
                            $sql = "SELECT `positionen`.`type`,tische.tischname, bestellungen.tischnummer, FLOOR( UNIX_TIMESTAMP(  bestellungen.zeitstempel ) /300 ) AS t, COUNT( * ), kellner FROM bestellungen, positionen,tische WHERE tische.tischnummer=bestellungen.tischnummer AND bestellungen.position=positionen.rowid AND `delete`=0 AND `kueche`=0 AND `type`=2 GROUP BY t, bestellungen.tischnummer ORDER BY bestellungen.zeitstempel, t LIMIT 50";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $Bestellungen = "";

                                $t = $row['t'];

                                if ($tischnummerselect != $row['tischnummer']) {
                                    echo '<div class="ui-block-a">';
                                    echo '<h2 style="font-size:30px">Tisch: ' . $row['tischname'] . '</h2>'; //. ' (#' . $row['tischnummer'] . 
                                    $tischname = $row['tischname'];
                                    echo '</div>';
                                    echo '<div class="ui-block-b">';
                                    echo '<p>KellnerIn: ' . $row['kellner'] . '</p>';
                                    echo '</div>';
                                }


                                echo '<div class="ui-block-a">';
                                $tischnummerselect = $row['tischnummer'];

                                $sql2 = "SELECT COUNT( * ) AS anzahl, `bestellungen`.`zeitKueche`, positionen.Kurzbezeichnung, `bestellungen`.`position`, bestellungen.Zusatzinfo, `bestellungen`.`tischnummer`, `bestellungen`.`zeitstempel`, `positionen`.`rowid`, `positionen`.`Positionsname`, `positionen`.`type`, `bestellungen`.`kueche`, `bestellungen`.`delete`, `bestellungen`.`rowid`, FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  FROM bestellungen, positionen WHERE bestellungen.position=positionen.rowid AND `type`=2 AND  bestellungen.zeitKueche='0000-00-00 00:00:00' AND bestellungen.ausgeliefert=0 AND positionen.type=2 AND bestellungen.delete=0 AND bestellungen.tischnummer=" . $tischnummerselect . " AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " GROUP BY Zusatzinfo, Positionsname ORDER BY positionen.Positionsname ASC";

                                $result2 = mysqli_query($conn, $sql2);

                                while ($row2 = mysqli_fetch_assoc($result2)) { //Ausgabe der offenen Bestellungen eines Tisches
                                    echo '<input style="background-color:#FFFF99; color:#f00;" type="button" value="(' . $row2['anzahl'] . 'x) ' . utf8_encode($row2['Kurzbezeichnung']);
                                    if (!empty($row2['Zusatzinfo'])) {
                                        echo ' (' . $row2['Zusatzinfo'] . ') ';
                                    }
                                    echo '" onclick="SchankFertig(' . $row2['rowid'] . ');"/>';
                                    $timestamp = strtotime($row2['zeitstempel']);
                                }

                                //Abfrage ohne Group um die einzelnen ID's zu bekommen
                                $query23 = "SELECT `bestellungen`.`zeitKueche`, `bestellungen`.`position`, `bestellungen`.`tischnummer`, `bestellungen`.`zeitstempel`, `positionen`.`rowid`, `positionen`.`Positionsname`, `positionen`.`type`, `bestellungen`.`kueche`, `bestellungen`.`delete`, `bestellungen`.`rowid`, FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  FROM bestellungen, positionen WHERE bestellungen.position=positionen.rowid AND `type`=2 AND  bestellungen.zeitKueche='0000-00-00 00:00:00' AND bestellungen.ausgeliefert=0 AND positionen.type=2 AND bestellungen.delete=0 AND bestellungen.tischnummer=" . $tischnummerselect . " AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " ORDER BY positionen.Positionsname ASC";
                                $result23 = mysqli_query($conn, $query23);

                                while ($row23 = mysqli_fetch_assoc($result23)) { //Ausgabe der offenen Bestellungen eines Tisches
                                    $Bestellungen = $Bestellungen . $row23['rowid'] . " OR rowid=";
                                    $timestampBestellung = $row23['zeitstempel'];
                                }

                                echo '</div>';
                                echo '<div class="ui-block-b">';
                                //Bereits hergerichtete Positionen
                                $query = "SELECT bestellungen.kueche, COUNT( * ) AS anzahl, `bestellungen`.`zeitKueche`, bestellungen.Zusatzinfo, `bestellungen`.`position`, `bestellungen`.`tischnummer`, `bestellungen`.`zeitstempel`, `positionen`.`rowid`, `positionen`.`Positionsname`, `positionen`.`type`, `bestellungen`.`kueche`, `bestellungen`.`delete`, `bestellungen`.`rowid`, FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/900) AS tt  FROM bestellungen, positionen WHERE bestellungen.position=positionen.rowid AND bestellungen.ausgeliefert=0 AND positionen.type=2 AND bestellungen.delete=0 AND bestellungen.zeitKueche!='0000-00-00 00:00:00' AND bestellungen.tischnummer=" . $tischnummerselect . " AND FLOOR(UNIX_TIMESTAMP(`bestellungen`.`zeitstempel`)/300)=" . $t . " GROUP BY Zusatzinfo, bestellungen.position ORDER BY `bestellungen`.`zeitKueche` DESC";

                                $result2 = mysqli_query($conn, $query);

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

                                echo '</div>';

                                echo '<div class="ui-block-a">';
                                $Bestellungen = substr($Bestellungen, 0, -10);

                                if ($counter == 0) {
                                    echo '<script type="text/javascript">bestellungSQL="' . $Bestellungen . '"; bestellungTischnr="' . $tischname . '";</script>';
                                } else {
                                    //echo '<script type="text/javascript"></script>';
                                }

                                $counter++;
                                echo '<input style="background-color:#00FF6A; color:#f99;" type="button" value="Gesamt Fertig" onclick="schankGesamtFertig(\'' . $Bestellungen . '\');"/>';
                                echo '<br><h1>&nbsp;</h1>';
                                echo '</div>';

                                echo '<div class="ui-block-b">';

                                echo '<p>(wartend:' . gmdate("i:s", (time() - $timestamp)) . ')</p>';
                                echo '</div>';
                            }
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }

                        try {
                            include_once ("include/db.php");
                            $sql5 = "SELECT COUNT( * ) AS anzahl FROM bestellungen, positionen WHERE bestellungen.position = positionen.rowid AND positionen.type=2 AND bestellungen.delete=0 AND `kueche`=0";
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


            </td>
            <td>
                <div class="ui-block-b"><div class="ui-bar ui-bar-a" style="background-color:<?php echo $bgcolor; ?>"><?php
                        echo '<p style="font-size:30px;color:' . $colorAnzahl . '" align="center">' . $wartendeBestellungen . ' Bestellungen wartend</p>';
                        echo '<script type="text/javascript">AnzahlBestellungenAktuell="' . $wartendeBestellungen . '"</script>';
                        try {
                            include_once ("include/db.php");
                            $sql6 = "SELECT bestellungen.position, positionen.rowid, positionen.positionsname, COUNT( * ) AS anzahl FROM positionen, bestellungen WHERE bestellungen.position = positionen.rowid AND bestellungen.kueche=0 AND positionen.type=2 AND bestellungen.delete=0 GROUP BY bestellungen.position ORDER BY anzahl DESC";
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
            </td>
        </tr>
    </table>
    <script>

        if (AnzahlOffeneBestellungenSchank < 1) {
            PlaySound = true;
        }

        if (AnzahlOffeneBestellungenSchank > 0 && PlaySound === true) {
            document.getElementById("sound1").play();
            PlaySound = false;
        }
    </script>
    <?php
    echo "</div>";
    ?>