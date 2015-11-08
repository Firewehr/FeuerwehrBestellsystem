<?php

require_once('auth.php');
error_reporting(E_ALL);
header("Cache-Control: no-cache, must-revalidate");
$Tischnummer = intval($_GET['tischnummer']);
include_once ("include/db.php");
?>

<?php

echo '<div id="Speisen">';

$sql1 = "SELECT * FROM positionen WHERE type=1 ORDER BY reihenfolge";
$result1 = mysqli_query($conn, $sql1);
$fontColour = "#000000";
$Colour = "#FFFFFF";
echo '<div class="ui-grid-a">';

include_once ("include/db.php");
$i = 0;
while ($rowww = mysqli_fetch_assoc($result1)) {
    try {

        if ($i == 0) {
            echo '<div class="ui-block-a">';
            $i++;
        } else {
            echo '<div class="ui-block-b">';
            $i = 0;
        }

        echo '<button class="ui-btn ui-corner-all big" onclick="saveBestellung(' . $rowww['rowid'] . ',1,' . $Tischnummer . ');"';


        $sql4 = "SELECT COUNT(*) as cnt FROM bestellungen WHERE tischnummer=" . $Tischnummer . " AND kueche=0 AND `delete`=0 AND position=" . $rowww['rowid'];
        $result4 = mysqli_query($conn, $sql4);
        $text = "";
        $cnt = "";

        while ($row4 = mysqli_fetch_assoc($result4)) {

            if ($row4['cnt'] > 0) {
                $Colour = "yellow";
                $fontColour = "#FF0000";
                $cnt = ' (' . $row4['cnt'] . "x)";
            } else {
                $Colour = "white";
                $fontColour = "#000000";
            }
        }


        $sql5 = "SELECT COUNT(*) as cnt FROM `bestellungen` WHERE `delete`=0 AND position=" . $rowww['rowid'];

        $result5 = mysqli_query($conn, $sql5);

        while ($row5 = mysqli_fetch_assoc($result5)) {
            echo 'Anzahl' . $row5['cnt'];
            $anzahlBestellt = $row5['cnt'];
        }

        $maxBestellbar = $rowww['maxBestellbar'];
        if ($maxBestellbar > 0) {

            if (($maxBestellbar - $anzahlBestellt) <= 0) {
                $text = "nicht mehr Verfügbar!";
                $Colour = "red";
            } else if (($maxBestellbar - $anzahlBestellt) < 10) {
                $text = " (nur noch " . ($maxBestellbar - $anzahlBestellt) . 'x verfügbar)';
                $Colour = "orange";
            }
        }
        $fontColour = "#" . $rowww['color'];



        /*
          if ($roww['maxBestellbar'] >= 0 and $roww['maxBestellbar'] < 10) {
          $text = "Nur noch weniger als 10 Stück verfügbar";
          }
          if ($roww['maxBestellbar'] == 0) {
          $Colour = "red";
          }
         * 
         */
        echo ' style="white-space: normal; !important; height: 80px; color:' . $fontColour . ' ; background:' . $Colour . ';">';
        echo utf8_encode($rowww['Positionsname']);
        echo ' ' . $cnt . $text;

        echo '</button>';
        echo '</div>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<?php

echo '</div>';

echo '</div>';
