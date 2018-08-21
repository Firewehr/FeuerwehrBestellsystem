<?php
require_once('auth.php');
//echo $_SESSION['admin'];

if ($_SESSION['admin'] != 1) {
    header('Location: index.php');
}
?>
<div data-role="header">
    <h1>Fr&uuml;hschoppen Bestellsystem</h1>
    <a href="#indexPage" class="ui-btn-left"> Zur&uuml;ck </a>
</div>
<div data-role="content">

    <ul data-role="listview" data-inset="true">

        <div id="neuerTisch" data-role="collapsible" data-collapsed="true">
            <a href="manage/" target="_blank">Tische verwalten (extern)</a>
            <h3>Tisch hinzufügen:</h3>

            <form>
                <p>Tischname:<input id="neuerTischName" type="text" value=""></p>
                <p>Farbe:<input id="neueTischFarbe" type="text" value=""></p>
                <p>X (Spalte):<input id="neueTischX" type="number" value=""></p>
                <p>Y (Zeile):<input id="neueTischY" type="number" value=""></p>
                <input type="button" onclick="saveNeuerTisch();" value="Speichern">
            </form>

        </div>


        <div id="Benutzer" data-role="collapsible" data-collapsed="true">
            <h3>Benutzer</h3>

            <div id="neuerBenutzer" data-role="collapsible" data-collapsed="true">
                <h3>Benutzer anlegen</h3>

                <form>
                    <?php if (isset($message['error'])): ?>
                        <fieldset class="error"><legend>Fehler</legend><?php echo $message['error'] ?></fieldset>
                        <?php
                    endif;
                    if (isset($message['success'])):
                        ?>
                        <fieldset class="success"><legend>Erfolg</legend><?php echo $message['success'] ?></fieldset>
                        <?php
                    endif;
                    if (isset($message['notice'])):
                        ?>
                        <fieldset class="notice"><legend>Hinweis</legend><?php echo $message['notice'] ?></fieldset>
                    <?php endif; ?>
                    <fieldset>

                        <div><label for="username">Benutzername</label> <input type="text" name="f[username]" id="username"<?php echo isset($_POST['f']['username']) ? ' value="' . htmlspecialchars($_POST['f']['username']) . '"' : '' ?> /></div>
                        <div><label for="adminyesno">Benutzertyp:</label>
                            <select type="select" name="f[adminyesno]" id="adminyesno">
                                <option value="0">normaler Benutzer</option>
                                <option value="1">Administrator</option>
                            </select></div>
                        <div><label for="password">Kennwort</label> <input type="password" name="f[password]" id="password" /></div>
                        <div><label for="password_again">Kennwort wiederholen</label> <input type="password" name="f[password_again]" id="password_again" /></div>
                    </fieldset>
                    <fieldset>
                        <div><input type="button" onclick="BenutzerNeu();" value="anlegen" /></div>
                    </fieldset>
                </form>


            </div>
            <?php
            try {
                include_once ("include/db.php");
                echo '<table data-role="table" class="ui-responsive" id="myUsers">'
                . '<thead>'
                . '<tr>'
                . '<th>Benutzer</th>'
                . '<th>Benutzerrechte</th>'
                . '<th>Erstellungsdatum</th>'
                . '<th></th>'
                . '</tr>'
                . '</thead>';

                $sql = "SELECT * FROM `users` ORDER BY username LIMIT 100";
                $result = mysqli_query($conn, $sql);
                echo '<tbody>';

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['username'] . '</td>';
                    echo '<td>';
                    if ($row['admin'] == 1) {
                        echo 'Administrator';
                    } else {
                        echo '&nbsp';
                    }
                    echo '</td>';
                    echo '<td>' . $row['timestamp'] . '</td';
                    echo '<td><input value="PW ändern" type="button" onclick="updatePW(' . $row['id'] . ')"></td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>

        </div>


        <div id="Speisekarte" data-role="collapsible" data-collapsed="true">
            <h3>Speisekarte</h3>
            <a href="manage/" target="_blank">Speisekarte verwalten (extern)</a>

            <div id="Positionanlegen" data-role="collapsible" data-collapsed="true">
                <h3>Position anlegen</h3>
                <form>
                    <div><label for="Positionsname">Positionsname</label> <input type="text" name="f[Positionsname]" id="Positionsname" required/></div>
                    <div><label for="produktkategorie">Produktkategorie</label>
                        <select name="f[produktkategorie]" id="produktkategorie">
                            <option value="1">Speise</option>
                            <option value="2">Getränk</option>
                        </select>
                    </div>
                    <div><label for="Betrag">Preis</label> <input type="text" name="f[Betrag]" id="Betrag" placeholder="Bitte Punkt statt Komma für Kommazahlen verwenden!" required /></div>
                    <div><label for="Kapazitaet">Kapazität</label> <input value placeholder="Die maximal bestellbare Menge. -1 für unendlich" type="number" name="f[Kapazitaet]" id="Kapazitaet" /></div>

                    <fieldset>
                        <div>
                            <a onclick="ProduktNeu();" data-icon="check" data-role="button" data-inline="true" data-theme="a">Speichern</a>
                        </div>
                    </fieldset>
                </form>
            </div>


            <?php
            error_reporting(E_ALL);
            try {
                include_once ("include/db.php");

                $result4 = mysqli_query($conn, "SELECT * FROM positionen");
                echo '<table data-role="table" data-mode="columntoggle" class="ui-responsive" id="mySpeisekarte">'
                . '<thead><tr>'
                . '<th data-priority="3">Reihung</th>'
                . '<th data-priority="1">Position</th>'
                . '<th data-priority="1">Betrag</th>'
                . '<th data-priority="3">Bestellt</th>'
                . '<th data-priority="1">Kapazität</th>'
                . '<th data-priority="2">Rest</th>'
                . '<th data-priority="1">&nbsp;</th></tr>'
                . '</thead>';
                echo '<tbody>';
                while ($row4 = mysqli_fetch_assoc($result4)) {

                    //echo $row4['rowid'];
                    $sql = "SELECT COUNT(*) as cnt FROM `bestellungen` WHERE `delete`=0 AND position=" . $row4['rowid'];

                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $anzahlBestellt = $row['cnt'];
                    }

                    $maxBestellbar = $row4['maxBestellbar'];

                    $text = "";
                    $rest = 0;
                    if ($maxBestellbar > 0) {
                        $rest = $maxBestellbar - $anzahlBestellt;
                        if (($maxBestellbar - $anzahlBestellt) <= 0) {
                            $text = "nicht mehr Verfügbar!";
                            $Colour = "red";
                        } else if (($maxBestellbar - $anzahlBestellt) < 10) {
                            $text = "";
                            $Colour = "orange";
                        }
                    }

                    echo '<tr>'
                    . '<td>';
                    echo $row4['reihenfolge'];
                    echo '</td><td>';
                    echo utf8_encode($row4['Positionsname']);
                    echo '</td><td>€ ';
                    echo utf8_encode($row4['Betrag']);
                    echo '</td><td>';
                    echo $anzahlBestellt . ' von ' . $maxBestellbar;
                    echo '</td><td>';
                    echo '<a onclick="updateKapazitaet(' . $row4['rowid'] . ',' . $maxBestellbar . ')" href="#" class="ui-btn ui-icon-edit ui-btn-icon-left">' . $row4['maxBestellbar'] . '</a>';
                    echo '</td><td>';
                    echo $rest . ' ' . $text;
                    echo '</td><td>';
                    echo '<a onclick="ProduktLoeschen(' . $row4['rowid'] . ')" href="#" class="ui-btn ui-icon-delete ui-btn-icon-left"></a>';
                    echo '</td></tr>';
                }
                echo '</tbody>';
                echo '</table>';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>
        </div>

        <?php
        try {
            include_once ("include/db.php");



            echo '<div id="Statistik" data-role="collapsible" data-collapsed="true">';
            echo '<h3 class="ui-bar ui-bar-a">Statistik</h3>';


            $sql4 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen");
            while ($row4 = mysqli_fetch_assoc($sql4)) {
                echo "<li>Bestellungen Gesamt: " . utf8_encode($row4['cnt']) . "</li>";
            }



            $sql5 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen WHERE `delete`=1");

            while ($row = mysqli_fetch_assoc($sql5)) {
                echo "<li>Stornierte Bestellungen: " . utf8_encode($row['cnt']) . "</li>";
            }



            echo '<div id="kellnerStat" data-role="collapsible" data-collapsed="true">';
            echo '<h3>Kellner Abgerechnete Positionen</h3>';
            $conn = mysqli_connect($hostname, $username, $password, $dbname);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            //echo $row4['rowid'];
            $sql = "SELECT COUNT(*) as cnt, "
                    . "kellnerZahlung,"
                    . "SUM(positionen.Betrag) as summe "
                    . "FROM `bestellungen`,"
                    . "positionen "
                    . "WHERE "
                    . "bestellungen.position=positionen.rowid AND "
                    . "`delete`=0 "
                    . "AND timestampBezahlung!='0000-00-00 00:00:00' "
                    . "GROUP by kellnerZahlung "
                    . "ORDER BY kellnerZahlung";
            //echo $sql;
            
            setlocale(LC_MONETARY,"de_DE"); //needed for money_format
            
            $result = mysqli_query($conn, $sql);
            echo '<table>';
            echo '<tr><th>Kellner</th><th>Anzahl</th><th>Betrag</th></tr>';
            $summe=0;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . $row['kellnerZahlung'] . '</td><td align="left">' . $row['cnt'] . '</td><td align="right">' . money_format("%i", $row['summe']) . '</td></tr>';
                $summe=$summe+$row['summe'];
            }
            
            echo '<tr><td>Summe</td><td>&nbsp;</td><td>' . money_format("%i", $summe) . ' </td></tr>';
            echo '</table>';
            echo '</div>';


            echo '<div id="Anzahl Aufgenommene Bestellungen" data-role="collapsible" data-collapsed="true">';

            echo '<h2>Kellner Aufgenommene Positionen</h2>';
            $sql = "SELECT kellner, COUNT(*) as anzahl "
                    . "FROM `bestellungen` "
                    . "JOIN positionen ON bestellungen.position=positionen.rowid "
                    . "WHERE bestellungen.zeitKueche!='0000-00-00 00:00:00' "
                    . "AND bestellungen.delete=0 "
                    . "GROUP BY kellner";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            echo '<table>';
            echo '<tr><th>Kellner</th><th>Anzahl</th></tr>';


            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . $row['kellner'] . '</td><td align="right">' . $row['anzahl'] . '</td></tr>';
            }
            echo '</table>';
            echo '</div>';


            echo '<div id="Umsatz pro Tisch" data-role="collapsible" data-collapsed="true">';
            echo '<h2>Umsatz pro Tisch</h2>';
            $sql = "SELECT SUM( positionen.betrag ) as summe , tische.tischname "
                    . "FROM `bestellungen` "
                    . "JOIN positionen ON bestellungen.position=positionen.rowid "
                    . "JOIN tische ON tische.tischnummer = bestellungen.tischnummer "
                    . "WHERE bestellungen.zeitKueche != '0000-00-00 00:00:00' "
                    . "AND bestellungen.delete =0 "
                    . "GROUP BY bestellungen.tischnummer "
                    . "ORDER BY tische.tischname ASC";

            $result = mysqli_query($conn, $sql);
            echo '<table data-mode="columntoggle"><thead><th>Tisch#</th><th>Umsatz</th></thead><tbody>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . $row['tischname'] . '</td><td align="right">' . money_format("%i", $row['summe']) . '</td></tr>';
            }
            echo '</tbody></table>';
            echo '</div>';


            echo '<div id="durchschnittliche wartezeit je Position" data-role="collapsible" data-collapsed="true">';
            echo '<h2>Wartezeit je Position</h2>';
            $sql = "SELECT positionen.Positionsname, "
                    . "AVG(TIMESTAMPDIFF(MINUTE, bestellungen.zeitstempel, zeitKueche)) AS avgzeit, "
                    . "MAX(TIMESTAMPDIFF(MINUTE, bestellungen.zeitstempel, zeitKueche)) AS maxzeit,  "
                    . "COUNT(*) as anzahl, FLOOR( UNIX_TIMESTAMP( bestellungen.zeitstempel ) /900 ) AS t "
                    . "FROM bestellungen "
                    . "JOIN positionen ON positionen.rowid = bestellungen.position "
                    . "WHERE `delete` = 0 "
                    . "AND `kueche` = 1 "
                    . "AND zeitKueche != '0000-00-00 00:00:00' "
                    . "GROUP BY bestellungen.position ORDER BY avgzeit DESC";

            $result = mysqli_query($conn, $sql);
            echo '<table>';
            
            echo '<tr><th>Position</th><th>Durchschnitt</th><th>Maximum</th></tr>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . utf8_encode($row['Positionsname']) . '</td><td align="left">' . round($row['avgzeit'],2) . '</td><td>' . $row['maxzeit'] . '</td></tr>';
            }
            echo '</table>';
            echo '</div>';

            echo '<div id="durchschnittliche wartezeit je Position" data-role="collapsible" data-collapsed="true">';
            echo '<h3 class="ui-bar ui-bar-a">Speisen</h3>';
            echo '<table><thead><tr><th>Name</th><th>xBestellt</th></tr></thead><tbody>';
            $result = mysqli_query($conn, "SELECT `positionen`.`Positionsname`, COUNT( * ) as cnt  FROM bestellungen, positionen WHERE positionen.rowid = bestellungen.position AND `delete`=0 GROUP BY bestellungen.position ORDER BY cnt DESC");
            while ($row5 = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . utf8_encode($row5['Positionsname']) . "</td><td align=\"right\">" . $row5['cnt'] . "</td></tr>";
            }
            echo '</tbody></table>';
            echo '</div>';

            echo '</div>';

            echo '<div id="Wartezeit" data-role="collapsible" data-collapsed="true">';

            $result = mysqli_query($conn, "SELECT COUNT(*) as cnt "
                    . "FROM bestellungen "
                    . "WHERE `delete`=0 "
                    . "AND `kueche`=0 "
                    . "AND zeitKueche='0000-00-00 00:00:00'");
            while ($row3 = mysqli_fetch_assoc($result)) {
                //echo "<li>Wartende Bestellungen: " . utf8_encode($row3['cnt']) . "</li>";
                echo '<h3>AKTUELL ' . utf8_encode($row3['cnt']) . ' Positionen wartend</h3>';
            }


            echo '<h3>Wartezeit der AKTUELL offenen Bestellungen</h3>';
            echo '<table><tr><th>Bestellung</th><th>Wartezeit</th></tr>';
            $sql = "SELECT TIMEDIFF(now(),zeitstempel) as zeit, "
                    . "FLOOR( UNIX_TIMESTAMP( zeitstempel ) /120 ) AS t, "
                    . "COUNT( * )  "
                    . "FROM bestellungen "
                    . "WHERE `delete`=0 AND `kueche`=0 "
                    . "AND zeitKueche='0000-00-00 00:00:00' "
                    . "GROUP BY t "
                    . "ORDER BY t DESC LIMIT 10";
            $result = mysqli_query($conn, $sql);

            while ($row4 = mysqli_fetch_assoc($result)) {
//                echo "<tr><td>" . date("H:i", (($row4['t']) * 120)) . "</td><td>" . gmdate("i:s", ($row4['zeit'])) . "</td></tr>";
                echo "<tr><td>" . date("H:i", (($row4['t']) * 120)) . "</td><td>" . $row4['zeit'] . "</td></tr>";
            }
            echo '</table>';



            echo '</div>';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        ?>


    </ul>
    <button style="background-color: red" onclick="resetBestellungen();">Bestellungen zurücksetzen</button>



</div>
</div>

<div data-role="footer">
    <h1></h1>
</div>
