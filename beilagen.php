<?php

$position = $_GET['position'];

error_reporting(E_ALL);
include('include/db.php');

$sql2 = "SELECT * FROM `positionen`, beilagen WHERE positionen.rowid=beilagen.position AND positionen.rowid=" . $position;

$result2 = mysqli_query($conn, $sql2);
echo '<table width="100%">';

while ($row2 = mysqli_fetch_assoc($result2)) {
    echo '<tr>';
    echo '<td>';
    echo utf8_encode($row2['name']);
    echo '</td>';
    echo '<td>';
    echo '<a data-role="button"class="ui-btn" onclick="rowid=' . $row2['rowid'] . ';Beilagen+=\' + ' . utf8_encode($row2['name']) . '\';">&nbsp;+&nbsp;</a>';
    echo '</td>';

    echo '</tr>';
}
echo '</table>';
