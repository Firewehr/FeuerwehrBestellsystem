<?php
require_once('auth.php');
$Tischnummer = $_GET['tischnummer'];

error_reporting(E_ALL);
include_once ("include/db.php");
$sql = "SELECT tischname FROM tische WHERE tischnummer=$Tischnummer";

//$resultttt = mysql_query("SELECT tischname FROM tische WHERE tischnummer=$Tischnummer");
$result = mysqli_query($conn, $sql);

$tischname = "";

while ($row = mysqli_fetch_assoc($result)) {
    $tischname = $row['tischname'];
}

mysqli_close($conn);

echo '<div data-role="header" data-position="fixed">';
echo '<h1 onclick="TischBezahlen();">Tisch ' . $tischname . ' (Zahlen)</h1>'; //"(#" . $Tischnummer . ')
echo '<a href="#listTische" onclick="TischAnsicht();" class="ui-btn ui-btn-left ui-icon-arrow-l ui-btn-icon-left">Tische</a>';
echo '<a onclick="TischAnsichtHistory();" class="ui-btn-right">Historie</a>';
echo '</div>';
?>
<!--<div class="ui-content">-->
<div data-role="content" id="Bestellungen">

    <div data-role="popup" id="popupDialog2" data-overlay-theme="a" data-theme="a" data-dismissible="false" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>Beilagen</h1>
        </div>
            <div role="main" class="ui-content" >

            <div id="popupDialog2Content">

            </div>
            <!--        <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" data-rel="back">Cancel</a>-->
                    <a href="#" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-b" onclick="updateZusatzinfo();" data-transition="flow">OK</a>
                </div>
    </div>

    <div data-role="tabs" id='TischAnzeigen' title="alert-tab">
            <div data-role="navbar">
            <ul>
                <?php
                echo '<li><a id="tabGetraenke" class="ui-btn-active ui-state-persist" href="listGetraenke.php?tischnummer=' . $Tischnummer . '" data-theme="a" data-ajax="false">Getränke</a></li>';
                echo '<li><a id="tabSpeisen" class="ui-btn" href="listSpeisen.php?tischnummer=' . $Tischnummer . '" data-theme="a" data-ajax="false">Speisen</a></li>';
                ?>                
            </ul>
        </div>
    </div>
</div>
<!--</div>-->
<?php
echo '<div data-role="footer">';
echo '</div>';
