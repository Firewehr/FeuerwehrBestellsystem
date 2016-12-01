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
<div data-role="content" id="BestellungenDirektverkauf">

    <div data-role="tabs" id='TischAnzeigenDirektverkauf' title="alert-tab">
            <div data-role="navbar">
            <ul>
                <?php
                echo '<li>'
                . '<a id="tabGetraenkeDirektverkauf" '
                        . 'class="ui-btn-active ui-state-persist" '
                        . 'href="listGetraenke_direktverkauf.php?tischnummer=' . $Tischnummer . '" '
                        . 'data-theme="a" data-ajax="false">Getränke'
                        . '</a></li>';
                echo '<li>'
                . '<a id="tabSpeisenDirektverkauf" class="ui-btn" '
                        . 'href="listSpeisen_direktverkauf.php?tischnummer=' . $Tischnummer . '" '
                        . 'data-theme="a" data-ajax="false">Speisen'
                        . '</a></li>';
                ?>                
            </ul>
        </div>
    </div>
</div>


<?php
/*
echo '</div>';
echo '</div>';*/

echo '<div data-role="footer">';
echo '</div>';
