<?php

//require_once('auth.php');
?>

<br>
<?php

try {
    include ("../include/db.php");


    //Liste alle Tische und ordne sie nach tischnummer
    //$sql = "SELECT * FROM tische WHERE x>0 AND y>0 ORDER BY y,x";
    $sql = "SELECT * FROM positionen  WHERE type=2 ORDER BY type, Positionsname";
    $result = mysqli_query($conn, $sql);
    ?>

    <table border="1" class="table" id="tischTable">
        <thead><tr><th>Name</th><th>Betrag</th><th>&nbsp;</th><th></th></tr></thead>
        <?php

        if (mysqli_num_rows($result) > 0) {
            $countx = 1;
            $county = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $x = $row['x'];
                $y = $row['y'];

                $lastx = $x;
                $lasty = $y;

                echo "<tr>";

                //echo "<td onclick=\"updateTischname('" . $row['tischname'] . "'," . $row['tischnummer'] . ")\">" . $row['tischname'] . "</td>";
                echo "<td>" . utf8_encode($row['Positionsname']);
                echo ' <button onclick="editPositionsname(' . $row['rowid'] . ',\'' . utf8_encode($row['Positionsname']) . '\')">Edit</button>';
                echo "</td>";
                echo "<td>" . $row['Betrag'];
                echo ' <button onclick="editBetrag(' . $row['rowid'] . ',\'' . $row['Betrag'] . '\')">Edit</button>';
                echo '</td>';
                echo "<td>";
                echo ' <button onclick="deleteMeal(' . $row['rowid'] . ')">Löschen</button>';
                echo '</td>';
                //echo '<td>' . '<button onclick="updateXY(' . $row['x'] . ',' . $row['y'] . ',' . $row['tischnummer'] . ')">Koordinaten ändern</button></td>';
                //echo "<td>" . $row['reihenfolge'] . "</td>";


                echo "</tr>";
            }
        } else {
            echo "0 results";
        }

        mysqli_close($conn);
        echo '</table>';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>
