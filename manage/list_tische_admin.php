<?php
//require_once('auth.php');
?>

<br>
<?php
try {
    include ("../include/db.php");


    //Liste alle Tische und ordne sie nach tischnummer
    //$sql = "SELECT * FROM tische WHERE x>0 AND y>0 ORDER BY y,x";
    $sql = "SELECT * FROM tische WHERE x>0 AND y>0 ORDER BY x, y";
    $result = mysqli_query($conn, $sql);
    ?>
    <form>
        <label>Tischname</label>
        <input type="text" id="tischname">
        <label>X</label>
        <input type="text" id ="x">
        <label>Y</label>
        <input type="text" id ="y">
        <button onclick="addTable();">neu anlegen</button>

    </form>
    <table border="1" class="table" id="tischTable">
        <thead><tr><th>Name</th><th>x (Spalte)</th><th>y (Zeile)</th><th>&nbsp;</th><th></th></tr></thead>
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

            echo "<td onclick=\"updateTischname('" . $row['tischname'] . "'," . $row['tischnummer'] . ")\">" . $row['tischname'] . "</td>";
            echo "<td>" . $row['x'] . "</td>";
            echo "<td>" . $row['y'] . '</td>';
            echo '<td>' . '<button onclick="updateXY(' . $row['x'] . ',' . $row['y'] . ',' . $row['tischnummer'] . ')">Koordinaten ändern</button></td>';
            echo '<td>' . '<input type="color" onchange="farbeSpeichern(' . $row['tischnummer'] . ')" id="html5colorpicker' . $row['tischnummer'] . '" onchange="clickColor(0, -1, -1, 5)" value="' . $row['color'] .  '" style="width:85%;">';
            
            //echo '<button onclick="farbeSpeichern(' . $row['tischnummer'] . '")>Farbe speichern</button>';
            
            echo '</td>';
            echo '<td>' . '<button onclick="deleteTable(' . $row['tischnummer'] . ')">löschen</button></td>';
            //echo "<td>" . $row['color'] . "</td>";


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
