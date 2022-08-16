<?php
require_once('auth.php');
?>
<a style="background-color:rgba(158, 158, 150,0.3)" href="#" data-theme="b" onclick="tisch();" class="ui-btn ui-icon-arrow-l ui-btn-icon-left">Zurück zur Speisekarte</a>

<?php
$Tischnummer = intval($_GET['tischnummer']);
try {
    include_once ("include/db.php");
    $sql = "SELECT `bestellungen`.`tischnummer`,"
            . "bestellungen.kellner,"
            . "bestellungen.kellnerZahlung,"
            . "bestellungen.timestampBezahlung, "
            . "bestellungen.timestampBezahlung, "
            . "bestellungen.Zusatzinfo, "
            . "`positionen`.`Betrag` as betrag, "
            . "`positionen`.`Positionsname`, "
            . "`bestellungen`.`zeitKueche`,"
            . "`bestellungen`.`position`, "
            . "`positionen`.`rowid`, `bestellungen`.`zeitstempel`, "
            . "`bestellungen`.`rowid`,`bestellungen`.`delete`,"
            . "`bestellungen`.`kueche` AS kuechef "
            . "FROM `bestellungen` "
            . "JOIN positionen ON `positionen`.`rowid`=`bestellungen`.`position` "
            . "WHERE `bestellungen`.`tischnummer`=" . $Tischnummer . ' '
            . 'AND `bestellungen`.`delete`=0 '
            . 'ORDER BY bestellungen.zeitstempel '
            . 'DESC LIMIT 30';
    $result1 = mysqli_query($conn, $sql);
    $Summe = 0;
    ?>

    <?php
    //echo '<table data-role="table" class="ui-responsive"><tbody>';
    while ($row = mysqli_fetch_assoc($result1)) {
        
        $Colour = "";
        if ($row['zeitKueche'] == '0000-00-00 00:00:00') {
            $color = "rgba(255, 255, 0,0.1)";
            $Colour = "yellow";
            $class = "ui-icon-delete ui-btn-icon-right";
            $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
        } elseif ($row['timestampBezahlung'] == '0000-00-00 00:00:00') {
            $color = "rgba(240, 14, 0,0.5)";
            $class = "ui-icon-delete ui-btn-icon-right";
            $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
            $Colour = "orange";
        } else {
            $color = "rgba(51, 204, 51,0.1)";
            $class = "";
            $onClick = "";
            $Colour = "lightgray";
        }

        echo '<div style="border:1px solid black;background-color:' . $color . '">';
        echo '<h2>' . utf8_encode($row['Positionsname']) . '</h2>';


        $timestamp = strtotime($row['zeitstempel']);

        echo 'Bestellt: ' . date("H:i", $timestamp) . ' (' . $row['kellner'] . ')';
        if ($row['zeitKueche'] !== '0000-00-00 00:00:00') {
            echo '<br>Küche: ' . date("H:i", strtotime($row['zeitKueche'])) . '<br>';
        }

        if ($row['timestampBezahlung'] == '0000-00-00 00:00:00') {
            ?>

            <div class="ui-grid-a">
                <div class="ui-block-a">
                    <?php
                    echo '<a class="ui-btn ui-icon-info ui-btn-icon-top"  '
                    . 'onclick=\'saveZusatzinfo(prompt("Zusatzinfo '
                    . htmlentities(utf8_encode($row['Positionsname']), ENT_QUOTES) . ':","'
                    . utf8_encode($row['Zusatzinfo']) . '"),' . $row['rowid'] . ');\'>'
                    . utf8_encode($row['Zusatzinfo']) . '&nbsp;</a>';
                    ?>
                </div>

                <div class="ui-block-b">
                    <?php
                    echo '<a href="#" class="' . "ui-btn ui-icon-delete ui-btn-icon-top"
                    . '" onclick="'
                    . 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');' . '">stornieren</a>';
                    ?>
                </div>
            </div>
            <?php
        }
        elseif ($_SESSION['admin'] == 1 ) {
			echo '<a href="#" class="' . "ui-btn ui-icon-delete ui-btn-icon-left ui-shadow-icon ui-btn-b"
            . '" onclick="'
            . 'bestellungBezStorno(' . $row['rowid'] . ',' . $Tischnummer . ');' . '">Zahlung Stornieren</a>';
		}
        
        echo '</div>';



        /*
         * 
          if ($row['zeitKueche'] == '0000-00-00 00:00:00') {
          $color = "rgba(255, 255, 0,0.1)";
          $class = "ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-right";
          $onClick = 'bestellungLoeschen(' . $row['rowid'] . ',' . $Tischnummer . ');';
          //echo '<a href="#" class="' . $class . '" onclick="' . $onClick . '"></a>';
          } elseif ($row['timestampBezahlung'] == '0000-00-00 00:00:00') {
          $color = "rgba(240, 14, 0,0.5)";
          } else {
          $color = "rgba(51, 204, 51,0.1)";
          $class = "";
          $onClick = "";
          echo '<br>Zahlung: ' . date("H:i", strtotime($row['timestampBezahlung']));
          echo ' (' . $row['kellnerZahlung'] . ')';
          }
         * 
         */
        //  echo '</td>';
        // echo '</tr>';
    }
    //echo '</tbody></table>';
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<script>
    $("#bestellungenTable").on("swiperight", function () {
        console.log("swiperight");
        tisch();
    });
</script>
