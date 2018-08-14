<?php
//require_once('../auth.php');
?>
<br>
<?php
try {
    include ("../include/db.php");


    //Liste alle Tische und ordne sie nach tischnummer
    //$sql = "SELECT * FROM tische WHERE x>0 AND y>0 ORDER BY y,x";
    $sql = "SELECT * FROM positionen ORDER BY type, reihenfolge ASC, Positionsname";
    $result = mysqli_query($conn, $sql);
    ?>
    <form>
        <label>Positionsname</label>
        <input type="text" id="positionsname">

        <label>Typ:</label>
        <select name="type" id="type">
            <option value="1">Speise</option>
            <option value="2">Getränk</option>
        </select>

        <label>Betrag</label>
        <input type="text" id ="betrag" placeholder=". statt , für Dezimalzahlen">

        <label>Kapazität</label>
        <input type="text" id ="kapazitaet" placeholder="-1 für Unendlich">        

        <button onclick="addMeal();">neu anlegen</button>

    </form>
    <table border="1" class="table" id="tischTable">
        <thead><tr><th>Reihenfolge</th><th>Positionsname</th><th>Kurzbezeichnung</th><th>Betrag</th></tr></thead>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $countx = 1;
            $county = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                //$x = $row['x'];
                //$y = $row['y'];

                //$lastx = $x;
                //$lasty = $y;

                echo "<tr>";

                //echo "<td onclick=\"updateTischname('" . $row['tischname'] . "'," . $row['tischnummer'] . ")\">" . $row['tischname'] . "</td>";
                echo '<td>';
                echo $row['reihenfolge'];
                echo ' <button onclick="editReihenfolge(' . $row['rowid'] . ',\'' . $row['reihenfolge'] . '\')">Edit</button>';
                echo '</td>';
                echo "<td>" . utf8_encode($row['Positionsname']);
                echo ' <button onclick="editPositionsname(' . $row['rowid'] . ',\'' . utf8_encode($row['Positionsname']) . '\')">Edit</button>';
                echo "</td>";
                echo "<td>" . utf8_encode($row['Kurzbezeichnung']);
                echo ' <button onclick="editKurzbezeichnung(' . $row['rowid'] . ',\'' . utf8_encode($row['Kurzbezeichnung']) . '\')">Edit</button>';
                echo "</td>";
                echo "<td>" . $row['Betrag'];
                echo ' <button onclick="editBetrag(' . $row['rowid'] . ',\'' . $row['Betrag'] . '\')">Edit</button>';
                echo '</td>';
                echo '<td>';
                echo '<td>' . '<input type="color" onchange="farbeSpeiseSpeichern(' . $row['rowid'] . ')" id="html5colorpickerM' . $row['rowid'] . '" onchange="clickColor(0, -1, -1, 5)" value="' . $row['color'] . '" style="width:85%;">';
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
