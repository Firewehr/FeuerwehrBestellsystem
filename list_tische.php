<?php
require_once('auth.php');
?>

<div data-role="header"  data-position="fixed">
    <a href="#indexPage" class="ui-btn ui-btn-left ui-icon-home ui-btn-icon-left">Menü</a>
    <h1>Tisch&uuml;bersicht</h1>
    <a href="#myOrdersPage" onclick="myOrdersAnsicht();" class="ui-btn-right">Meine Bestellungen</a>
</div>

<div data-role="content">

    <?php
    /*
      try {
      include ("include/db.php");


      //Liste alle Tische und ordne sie nach tischnummer
      $sql = "SELECT * FROM tische ORDER BY tischnummer";
      $result = mysqli_query($conn, $sql);
      ?>

      <div class="ui-grid-d">
      <?php
      if (mysqli_num_rows($result) > 0) {

      while ($row = mysqli_fetch_assoc($result)) {

      $x = substr($row['tischnummer'], -1);
      $x1 = substr($row['tischnummer'], -2);

      if ($x == 1 && $x1 != 1) {
      $char = 'a';
      }
      if ($x == 2) {
      $char = 'b';
      }
      if ($x == 3) {
      $char = 'c';
      }
      if ($x == 4) {
      $char = 'd';
      }
      if ($x == 5) {
      $char = 'e';
      }

      $tischnummerabfrage = $row['tischnummer'];

      $result2 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen WHERE `delete`=0 AND `timestampBezahlung`=\"0000-00-00 00:00:00\"  AND tischnummer=" . $tischnummerabfrage);
      while ($roww = mysqli_fetch_assoc($result2)) {
      //echo $roww['cnt'];

      if ($roww['cnt'] > 0) {
      $Colour = "yellow";
      }

      if ($roww['cnt'] == 0) {
      $Colour = "LightGreen";
      }
      }
      echo '<div class="ui-block-' . $char . '">';



      echo '<button class="ui-btn ui-corner-all big" onclick="Tischnummer=' . $row['tischnummer'] . ';tisch();" style="background:' . $Colour . ';">'; //font-size:13px;
      echo "&nbsp;&nbsp;" . $row['tischname'] . "&nbsp;&nbsp;";
      echo '</button>';
      echo '</div>';
      }
      } else {
      echo "0 results";
      }

      mysqli_close($conn);
      echo '</div>';
      } catch (Exception $e) {
      echo $e->getMessage();
      }

     */


    try {
        include ("include/db.php");


        //Liste alle Tische und ordne sie nach tischnummer
        $sql = "SELECT * FROM tische WHERE x>0 AND y>0 ORDER BY y,x";
        $result = mysqli_query($conn, $sql);
        ?>

        <div class="ui-grid-d">
            <?php
            if (mysqli_num_rows($result) > 0) {
                $countx = 1;
                $county = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    $x = $row['x'];
                    $y = $row['y'];

                    if ($county < $y) {
                        //Wenn Zeile gewechselt werden muss, fuege noch
                        //leere div's ein zum auffüllen
                        for ($countx; $countx < 6; $countx++) {

                            if ($countx == 1) {
                                $char = 'a';
                                //$county++;
                                //$countx = 1;
                            }
                            if ($countx == 2) {
                                $char = 'b';
                            }
                            if ($countx == 3) {
                                $char = 'c';
                            }
                            if ($countx == 4) {
                                $char = 'd';
                            }
                            if ($countx == 5) {
                                $char = 'e';
                            }

                            //Erstellt einen leeren div Block
                            echo '<div class="ui-block-' . $char . '">';
                            echo '<button class="ui-btn ui-corner-all big" style="color:#' . $fontColour . ';background:#FFFFFF;">'; //font-size:13px;
                            //echo $countx; //DEBUG
                            echo '&nbsp;';
                            echo '</button>';
                            echo '</div>';
                        }
                        $county = $y;
                        $countx = 1;
                    }
                    if ($countx < $x) {
                        //Wenn ein Abstand benötigt wird füge
                        //leere Ausgleichsdiv's ein
                        for ($countx; $countx < $x; $countx++) {

                            if ($x == 1) {
                                $char = 'a';
                                //$county++;
                                //$countx = 1;
                            }
                            if ($x == 2) {
                                $char = 'b';
                            }
                            if ($x == 3) {
                                $char = 'c';
                            }
                            if ($x == 4) {
                                $char = 'd';
                            }
                            if ($x == 5) {
                                $char = 'e';
                            }

                            //Erstellt einen leeren div Block
                            echo '<div class="ui-block-' . $char . '">';
                            echo '<button class="ui-btn ui-corner-all big" style="color:#' . $fontColour . ';background:#FFFFFF;">'; //font-size:13px;
                            echo "&nbsp;";
                            echo '</button>';
                            echo '</div>';
                        }
                    } else {

                        if ($x == 1) {
                            $char = 'a';
                            $county++;
                            $countx = 1;
                        }
                        if ($x == 2) {
                            $char = 'b';
                        }
                        if ($x == 3) {
                            $char = 'c';
                        }
                        if ($x == 4) {
                            $char = 'd';
                        }
                        if ($x == 5) {
                            $char = 'e';
                        }
                    }
                    $tischnummerabfrage = $row['tischnummer'];

                    $result2 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM bestellungen WHERE `delete`=0 AND `timestampBezahlung`=\"0000-00-00 00:00:00\"  AND tischnummer=" . $tischnummerabfrage);
                    while ($roww = mysqli_fetch_assoc($result2)) {
                        //echo $roww['cnt'];

                        if ($roww['cnt'] > 0) {
                            $Colour = "#F5F599";
                        }

                        if ($roww['cnt'] == 0) {
                            $Colour = "LightGreen";
                        }
                    }
                    echo '<div class="ui-block-' . $char . '">';

                    if ($row['color'] !== "") {
                        $fontColour = $row['color'];
                    } else {
                        $fontColour = "000000";
                    }
                    echo '<button class="ui-btn ui-corner-all big" onclick="Tischnummer=' . $row['tischnummer'] . ';tisch();" style="color:#' . $fontColour . ';background:' . $Colour . ';">'; //font-size:13px;
                    echo $row['tischname'];
                    //echo $row['x'] . "," . $row['y']; //DEBUG
                    //echo "&nbsp;&nbsp;" . $row['tischname'] . "&nbsp;&nbsp;";

                    echo '</button>';
                    echo '</div>';

                    if ($countx >= 5) {
                        $countx = 1;
                        $county++;
                    } else {
                        $countx++;
                    }
                }
            } else {
                echo "0 results";
            }

            mysqli_close($conn);
            echo '</div>';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        ?>
    </div>
