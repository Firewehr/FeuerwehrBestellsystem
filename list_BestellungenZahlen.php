<?php
require_once('auth.php');
?>
<a style="background-color:rgba(158, 158, 150,0.3)" href="#" data-theme="b" onclick="tisch();" class="ui-btn ui-icon-arrow-l ui-btn-icon-left">Zurück zur Speisekarte</a>
<h2>Zahlen:</h2>

<script>
    BetragEinzelnBezahlen = 0;
    rowIDsBezahlt = "";
    rowIDsBezahltAlle = "";
</script>
<div id="BestellungZahlen">
    <script>
        arrayZahlungGetrennt = [];
        arrayZahlung = [];
    </script>
    <?php
    $Tischnummer = intval($_GET['tischnummer']);
    error_reporting(E_ALL);
    echo '<table border="0" width="100%">';
    try {
        include_once ("include/db.php");
        $sql = "SELECT `bestellungen`.`tischnummer`,"
                . "bestellungen.timestampBezahlung,"
                . "`positionen`.`Betrag` as betrag,"
                . "`positionen`.`Positionsname`,"
                . "`bestellungen`.`zeitKueche`,"
                . "`bestellungen`.`position`,"
                . "`positionen`.`rowid`,"
                . "`bestellungen`.`zeitstempel`,"
                . "`bestellungen`.`rowid` as rowidBestellung,"
                . "`bestellungen`.`delete`,"
                . "`bestellungen`.`kueche` AS kuechef "
                . "FROM `bestellungen`, `positionen` "
                . "WHERE  `positionen`.`rowid`=`bestellungen`.`position` "
                . "AND `bestellungen`.`tischnummer`=" . $Tischnummer . ' '
                . 'AND `bestellungen`.`delete`=0 '
                . 'AND `bestellungen`.`kueche`=1 '
                . 'AND `bestellungen`.`timestampBezahlung`="0000-00-00 00:00:00" '
                . 'ORDER BY positionen.Positionsname DESC '
                . 'LIMIT 300';

        $Summe = 0;
        $rowIDsBezahltAlle = "";
        $result = mysqli_query($conn, $sql);
        $count = 0;
        $arrayListe = '[';
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $i++;
            $count++;
            $Colour = "white";

            $Summe+=$row['betrag'];

            if ($row['kuechef'] == 1) {
                $Colour = "yellow";
            }

            $timestamp = strtotime($row['zeitstempel']);

            $rowIDsBezahltAlle = $rowIDsBezahltAlle . $row['rowidBestellung'] . ' OR rowid=';
            $arrayListe = $arrayListe . $row['rowidBestellung'] . ',';
            echo '<tr id="zeile' . $row['rowidBestellung'] . '">';

            echo '<td>';
            echo date("H:i", strtotime($row['zeitKueche']));
            echo '</td>';


            echo '<td>' . utf8_encode($row['Positionsname']) . '</td>';
            echo '<td width="10%">';
            echo '<input type="button" id="plus' . $row['rowidBestellung'] . '" '
            . 'onclick="$(\'#plus' . $row['rowidBestellung'] . '\').hide();'
            . 'BetragEinzelnBezahlen=BetragEinzelnBezahlen+' . $row['betrag'] . ';'
            . '$(\'#zeile' . $row['rowidBestellung'] . '\').css(\'background-color\', \'#66ff66\');'
            . '$(\'#summeZahlung\').text(BetragEinzelnBezahlen.toFixed(2) + \' EUR\');'
            . 'rowIDsBezahltAlle=``;'
            . '$(\'#btnBezahlenGesamt\').hide();$(\'#btnBezahlenGesamtEinzeln\').show();'
            . 'rowIDsBezahlt=rowIDsBezahlt+\'' . $row['rowidBestellung'] . ' OR rowid=\';'
            . ' arrayZahlungGetrennt.push(' . $row['rowidBestellung'] . ')" value="&nbsp;+&nbsp;"/>';
            echo '</td>';

            setlocale(LC_MONETARY, 'de_DE@euro');
            echo '<td wdith="20%" align="right">' . money_format('%i', (float) $row['betrag']) . '</td>';
            echo '</tr>';
        }
        if ($i > 0) {
            $arrayListe = substr($arrayListe, 0, -1);

        }
        $arrayListe = $arrayListe . ']';
        echo '<tr>';

        echo '<td colspan="3">';
        echo '<h2>Gesamt</h2>';
        echo '</td>';

        echo '<td align="right"><h2><div id="summeZahlung">';
        echo money_format('%i', (double) $Summe);
        echo '</div></h2><td></tr>';

        echo '<tr>';
        echo '<td colspan="4">';
        if ($count > 0) {

            echo '<a id="btnBezahlenGesamtEinzeln" style="background-color:rgba(255, 255, 0,0.5)" '
            . 'onclick="'
            . '$(\'#btnBezahlenGesamt\').hide();'
            . 'BestellungBezahlt(arrayZahlungGetrennt,0);" '
            . 'class="ui-btn">Bezahlen</a>';

            echo '<a id="btnBezahlenGesamt" style="background-color:rgba(255, 255, 0,0.5)" '
            . 'onclick="'
            . '$(\'#btnBezahlenGesamt\').hide();'
            . 'BestellungBezahlt(arrayZahlung,0);" '
            . 'class="ui-btn">Bezahlen Gesamt</a>';
        }

        echo '</td></tr>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    echo '</table>';
    echo '<script>'
    . 'rowIDsBezahltAlle="' . $rowIDsBezahltAlle . '";'
    . 'arrayZahlung=' . $arrayListe . ';</script>';
    echo '</div>';
    ?>
    <script>


        $('#btnBezahlenGesamtEinzeln').hide();
        $("#BestellungZahlen").on("swiperight", function () {
            console.log("swiperight");
            //#listTische
            $.mobile.changePage('#listTischBestellungen');
            tisch();
        });

    </script>
