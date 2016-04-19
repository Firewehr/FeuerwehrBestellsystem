<?php
require_once('auth.php');

include_once('include/db.php');
?>
<div data-role="header">
    <h1>Fr&uuml;hschoppen Bestellsystem</h1>
    <a href="#indexPage" class="ui-btn-left"> Zur&uuml;ck </a>
</div>

<div data-role="content">
    <h1>Meine Offenen Bestellungen:</h1>
<?php
try {
    include_once ("include/db.php");
    ?>

        <?php
        $sql = "SELECT `bestellungen`.`kueche`, `positionen`.`type`,tische.tischname, bestellungen.tischnummer, FLOOR( UNIX_TIMESTAMP(  bestellungen.zeitstempel ) /300 ) AS t, COUNT( * ), kellner FROM bestellungen, positionen,tische WHERE tische.tischnummer=bestellungen.tischnummer AND bestellungen.position=positionen.rowid AND `delete`=0  AND timestampBezahlung='0000-00-00 00:00:00' AND kellner LIKE '" . htmlspecialchars($_SESSION['user']['username']) . "' GROUP BY t, bestellungen.tischnummer ORDER BY bestellungen.zeitstempel, t LIMIT 50";

        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $t = $row['t'];
            $tischnummerselect = $row['tischnummer'];

            if ($row['kueche'] == 1) {
                $Colour = "green";
            } else {
                $Colour = "yellow";
            }

            echo '<input style="background-color:' . $Colour . '" type="button" value="' . $row['tischname'] . '" onclick="Tischnummer=' . $row['tischnummer'] . ';tisch();"/>';
            ?>

            <?php
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>
</div>