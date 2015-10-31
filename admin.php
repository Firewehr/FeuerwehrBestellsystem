<?php
require_once('auth.php');
?>
<div data-role="header">
    <h1>Fr&uuml;hschoppen Bestellsystem</h1>
    <a href="#indexPage" class="ui-btn-left"> Zur&uuml;ck </a>
</div>
<div data-role="content">

    <ul data-role="listview" data-inset="true">

        <div id="neuerTisch" data-role="collapsible" data-collapsed="true">
            <h3>Tisch hinzufügen:</h3>

            <form>
                <p>Tischname:<input id="neuerTischName" type="text" value=""></p>
                <p>Tischnummer:<input id="neueTischNummer" type="number" value=""></p>
                <input type="button" onclick="saveNeuerTisch();" value="Speichern">
            </form>

        </div>
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
                    <div><label for="password">Kennwort</label> <input type="password" name="f[password]" id="password" /></div>
                    <div><label for="password_again">Kennwort wiederholen</label> <input type="password" name="f[password_again]" id="password_again" /></div>
                </fieldset>
                <fieldset>
                    <div><input type="button" onclick="BenutzerNeu();" value="anlegen" /></div>
                </fieldset>
            </form>


        </div>

        <div id="Benutzer" data-role="collapsible" data-collapsed="true">
            <h3>Benutzer</h3>

            <?php
            try {
                include_once ("include/db.php");
                echo '<table widht="100%"><tr><th>Benutzer</th></tr>';

                $sql = "SELECT * FROM `users` LIMIT 100";

                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['username'] . '</td';
                    echo '/<tr>';
                }
                echo '</table>';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            ?>

        </div>
        <div id="Speisekarte" data-role="collapsible" data-collapsed="true">
            <h3>Speisekarte</h3>
            <?php
            error_reporting(E_ALL);
            try {
                include_once ("include/db.php");

                $result4 = mysqli_query($conn, "SELECT * FROM positionen");
                echo '<table widht="100%"><tr><th>Position</th><th>Betrag</th><th>Anzahl Bestellung</th><th>Restkapazität</th></tr>';
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

                    echo '<tr><td>';
                    echo utf8_encode($row4['Positionsname']);
                    echo '</td><td>';
                    echo utf8_encode($row4['Betrag']);
                    echo '</td><td>';
                    echo $anzahlBestellt . ' von ' . $maxBestellbar;
                    echo '</td><td>';
                    echo $rest . ' ' . $text;
                    echo '</td></tr>';
                }
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
            echo '<h3 class="ui-bar ui-bar-a">Statistik:</h3>';
            $sql4 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen");
            while ($row4 = mysqli_fetch_assoc($sql4)) {
                echo "<li>Bestellungen Gesamt: " . utf8_encode($row4['cnt']) . "</li>";
            }



            $sql5 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen WHERE `delete`=1");

            while ($row = mysqli_fetch_assoc($sql5)) {
                echo "<li>Stornierte Bestellungen: " . utf8_encode($row['cnt']) . "</li>";
            }

            $result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen WHERE `delete`=0 AND `kueche`=0 AND zeitKueche='0000-00-00 00:00:00'");
            while ($row3 = mysqli_fetch_assoc($result)) {
                echo "<li>Wartende Bestellungen: " . utf8_encode($row3['cnt']) . "</li>";
            }
            echo 'Kellner:';
            $conn = mysqli_connect($hostname, $username, $password, $dbname);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            //echo $row4['rowid'];
            $sql = "SELECT COUNT(*) as cnt, kellnerZahlung,SUM(positionen.Betrag) as summe FROM `bestellungen`,positionen WHERE bestellungen.position=positionen.rowid AND `delete`=0 AND timestampBezahlung!='0000-00-00 00:00:00' GROUP by kellnerZahlung ORDER BY kellnerZahlung";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            echo '<table>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . $row['kellnerZahlung'] . '</td><td>' . $row['cnt'] . '</td><td>' . $row['summe'] . ' €</td></tr>';
            }
            echo '</table>';


            echo '<div id="Anzahl Aufgenommene Bestellungen" data-role="collapsible" data-collapsed="true">';

            echo '<h2>Anzahl Aufgenommene Bestellungen</h2>';
            $sql = "SELECT kellner, COUNT(*) as anzahl FROM `bestellungen`,positionen WHERE bestellungen.position=positionen.rowid AND bestellungen.zeitKueche!='0000-00-00 00:00:00' AND bestellungen.delete=0 GROUP BY kellner";
            //echo $sql;
            $result = mysqli_query($conn, $sql);
            echo '<table>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . $row['kellner'] . '</td><td>' . $row['anzahl'] . '</td></tr>';
            }
            echo '</table>';
            echo '</div>';


            echo '<div id="Umsatz pro Tisch" data-role="collapsible" data-collapsed="true">';
            echo '<h2>Umsatz pro Tisch</h2>';
            $sql = "SELECT SUM( positionen.betrag ) as summe , tische.tischname FROM `bestellungen` , positionen, tische WHERE tische.tischnummer = bestellungen.tischnummer AND bestellungen.position = positionen.rowid AND bestellungen.zeitKueche != '0000-00-00 00:00:00' AND bestellungen.delete =0 GROUP BY bestellungen.tischnummer ORDER BY tische.tischname ASC";

            $result = mysqli_query($conn, $sql);
            echo '<table>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . $row['tischname'] . '</td><td>' . $row['summe'] . '€</td></tr>';
            }
            echo '</table>';
            echo '</div>';


            echo '<div id="durchschnittliche wartezeit je Position" data-role="collapsible" data-collapsed="true">';
            echo '<h2>durchschnittliche wartezeit je Position</h2>';
            $sql = "SELECT positionen.Positionsname, AVG(TIMESTAMPDIFF(MINUTE, bestellungen.zeitstempel, zeitKueche)) AS avgzeit, COUNT(*) as anzahl, FLOOR( UNIX_TIMESTAMP( bestellungen.zeitstempel ) /900 ) AS t FROM bestellungen, positionen WHERE positionen.rowid = bestellungen.position AND `delete` = 0 AND `kueche` = 1 AND zeitKueche != '0000-00-00 00:00:00' GROUP BY bestellungen.position ORDER BY avgzeit DESC";

            $result = mysqli_query($conn, $sql);
            echo '<table>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr><td>' . utf8_encode($row['Positionsname']) . '</td><td>' . $row['avgzeit'] . ' Minuten</td></tr>';
            }
            echo '</table>';
            echo '</div>';

            echo '</div>';

            echo '<div id="Wartezeit" data-role="collapsible" data-collapsed="true">';
            echo '<h3>Wartezeit der offenen Bestellungen AKTUELL</h3>';
            echo '<table><tr><th>Zeitpunkt Bestellungsaufnahme</th><th>Wartezeit Minuten:Sekunden </th></tr>';
            $sql = "SELECT TIMEDIFF(now(),zeitstempel) as zeit, FLOOR( UNIX_TIMESTAMP( zeitstempel ) /120 ) AS t, COUNT( * )  FROM bestellungen WHERE `delete`=0 AND `kueche`=0 AND zeitKueche='0000-00-00 00:00:00' GROUP BY t ORDER BY t DESC LIMIT 10";
            $result = mysqli_query($conn, $sql);

            while ($row4 = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . date("H:i", (($row4['t']) * 120)) . "</td><td>" . gmdate("i:s", ($row4['zeit'])) . "</td></tr>";
            }
            echo '</table>';

            echo '<h3 class="ui-bar ui-bar-a">Wartezeit (2 Minuten Interval) (Aufnahme bis zur Fertigstellung in der Küche)</h3>';
            echo '<table><tr><th>Zeitpunkt Bestellungsaufnahme</th><th>Wartezeit Minuten:Sekunden </th></tr>';
            $result = mysqli_query($conn, "SELECT (zeitKueche-zeitstempel) as zeit, FLOOR( UNIX_TIMESTAMP( zeitstempel ) /360 ) AS t, COUNT( * )  FROM bestellungen, positionen WHERE positionen.rowid = bestellungen.position AND `delete`=0 AND `kueche`=1 AND zeitKueche!='0000-00-00 00:00:00' AND positionen.type=1 GROUP BY t ORDER BY t DESC LIMIT 200");
            while ($row4 = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . date("H:i", (($row4['t']) * 120)) . "</td><td>" . gmdate("i:s", ($row4['zeit'])) . "</td></tr>";
            }
            echo '</table>';


            echo '<h3 class="ui-bar ui-bar-a">Speisen</h3>';
            echo '<table><tr><th>Name</th><th>Anzahl der Bestellungen</th></tr>';
            $result = mysqli_query($conn, "SELECT `positionen`.`Positionsname`, COUNT( * ) as cnt  FROM bestellungen, positionen WHERE positionen.rowid = bestellungen.position AND `delete`=0 GROUP BY bestellungen.position ORDER BY cnt DESC");
            while ($row5 = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . utf8_encode($row5['Positionsname']) . "</td><td>" . $row5['cnt'] . "</td></tr>";
            }
            echo '</table>';
            echo '</div>';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        ?>
    </ul>
</div>

<div data-role="footer">
    <h1></h1>
</div>
