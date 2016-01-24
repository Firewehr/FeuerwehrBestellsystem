<?php
require_once('auth.php');
header("Cache-Control: no-cache");
$Tischnummer = intval($_GET['tischnummer']);
echo '<div id="Getraenke">';
//echo '<h3>Getraenke</h3>';

include_once ("include/db.php");
error_reporting(E_ALL);
echo '<div class="ui-grid-a">';
$sql = "SELECT * FROM positionen WHERE type=2 ORDER BY reihenfolge";
$result = mysqli_query($conn, $sql);
$i = 0;
$Colour = "#FFFFFF";
$fontColour = "";
while ($rowww = mysqli_fetch_assoc($result)) {

    try {
        include_once ("include/db.php");

        if ($i == 0) {
            echo '<div class="ui-block-a">';
            $i++;
        } else {
            echo '<div class="ui-block-b">';
            $i = 0;
        }
        echo '<button class="ui-btn ui-corner-all" onclick="saveBestellung(' . $rowww['rowid'] . ',0,' . $Tischnummer . ');"';

        $sql4 = "SELECT COUNT(*) as cnt FROM bestellungen WHERE tischnummer=" . $Tischnummer . " AND kueche=0 AND `delete`=0 AND position=" . $rowww['rowid'];
        $cnt = "";
        $result4 = mysqli_query($conn, $sql4);
        while ($row4 = mysqli_fetch_assoc($result4)) {
            if ($row4['cnt'] > 0) {
                $Colour = "#F4FA58";
                $cnt = ' (' . $row4['cnt'] . "x)";
            } else {
                $Colour = "#FFFFFF";
            }
        }

        $sql = "SELECT COUNT(*) as cnt FROM `bestellungen` WHERE `delete`=0 AND position=" . $rowww['rowid'];
        echo $sql;
        $result5 = mysqli_query($conn, $sql);


        $text = "";
        while ($row = mysqli_fetch_assoc($result5)) {
            echo 'Anzahl' . $row['cnt'];
            $anzahlBestellt = $row['cnt'];
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


        echo ' style="white-space: normal; !important; color:' . $fontColour . '; height: 80px;background:' . $Colour . ';">';
        echo utf8_encode($rowww['Positionsname']);
        echo ' ' . $cnt . $text;
        echo '</button>';
        echo '</div>';

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
mysqli_close($conn);
echo '</div>';
echo '</div>';
?>
<script>
    $("#Getraenke").on("swipeleft", function () {
        console.log("swipeleft");
        $('#tabSpeisen').click();
    });
    
     $("#Getraenke").on("swiperight", function () {
        console.log("swiperight");
        //#listTische
        $.mobile.changePage('#listTische');
        TischAnsicht();
    });   
</script>'