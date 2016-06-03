<?php
require_once('auth.php');
error_reporting(E_ALL);


header("Cache-Control: no-cache, must-revalidate");
$Tischnummer = 999999;
include_once ("include/db.php");
echo '<div data-role="header" data-position="fixed">';
echo '<h1>Direktverkauf</h1>';
echo '<a href="#indexPage" class="ui-btn ui-btn-left ui-icon-arrow-l ui-btn-icon-left">Menü</a>';
echo '</div>';
?>
<!--<div class="ui-content">-->
<div data-role="content" id="BestellungenDirektverkauf">

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

    <div data-role="tabs" id='TischAnzeigenDirektverkauf' title="alert-tab">
            <div data-role="navbar">
            <ul>
                <?php
                echo '<li><a id="tabGetraenkeDirektverkauf" class="ui-btn-active ui-state-persist" href="listGetraenke_direktverkauf.php?tischnummer=' . $Tischnummer . '" data-theme="a" data-ajax="false">Getränke</a></li>';
                echo '<li><a id="tabSpeisenDirektverkauf" class="ui-btn" href="listSpeisen_direktverkauf.php?tischnummer=' . $Tischnummer . '" data-theme="a" data-ajax="false">Speisen</a></li>';
                ?>                
            </ul>
        </div>
    </div>
</div>
<!--</div>-->


<?php
echo '</div>';

echo '</div>';


echo '<div data-role="footer">';
echo '</div>';
