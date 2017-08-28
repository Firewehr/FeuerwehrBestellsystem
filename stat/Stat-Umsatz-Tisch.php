<?php   
 /* CAT:Labels */
 include ("../include/db.php"); /* Datenbank BN+PW  */
 
 $conn = mysqli_connect($hostname, $username, $password, $dbname);

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
 
 /* pChart library inclusions */
 include("../include/pChart/class/pData.class.php");
 include("../include/pChart/class/pDraw.class.php");
 include("../include/pChart/class/pPie.class.php");
 include("../include/pChart/class/pImage.class.php");
 
 /* Create and populate the pData object */
 $MyData = new pData();  
	/* Build the query that will returns the data to graph */
	$Requete = "SELECT SUM( positionen.betrag ) as summe , tische.tischname as tischnr FROM `bestellungen` , positionen, tische WHERE tische.tischnummer = bestellungen.tischnummer AND bestellungen.position = positionen.rowid AND bestellungen.zeitKueche != '0000-00-00 00:00:00' AND bestellungen.delete =0 GROUP BY bestellungen.tischnummer ORDER BY summe DESC";
	$Result = mysqli_query($conn, $Requete);
	$tischnr=""; $summe="";
	while ($row = mysqli_fetch_array ($Result))
	{
	/* Get the data from the query result */
  $summe = $row["summe"];
  $tischnr = $row["tischnr"];
   $MyData->addPoints($summe,"summe");
   $MyData->addPoints($tischnr,"tischnr");
	}
	
 /* Create and populate the pData object */
$MyData->setSerieDescription("summe","summe");
 /* Define the absissa serie */
 $MyData->setAbscissa("tischnr");
 
 /* Create the pChart object */
 $myPicture = new pImage(500,500,$MyData);
 $myPicture->drawGradientArea(0,0,500,500,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
 $myPicture->drawGradientArea(0,0,500,500,DIRECTION_HORIZONTAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>20));
 $myPicture->setFontProperties(array("FontName"=>"../include/pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

 /* Draw the chart scale */ 
 $myPicture->setGraphArea(100,30,480,480);
 $myPicture->drawScale(array("CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10,"Pos"=>SCALE_POS_TOPBOTTOM));

 /* Turn on shadow computing */ 
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Create the per bar palette */
 $Palette = array("0"=>array("R"=>188,"G"=>224,"B"=>46,"Alpha"=>100),
                  "1"=>array("R"=>224,"G"=>100,"B"=>46,"Alpha"=>100),
                  "2"=>array("R"=>224,"G"=>214,"B"=>46,"Alpha"=>100),
                  "3"=>array("R"=>46,"G"=>151,"B"=>224,"Alpha"=>100),
                  "4"=>array("R"=>176,"G"=>46,"B"=>224,"Alpha"=>100),
                  "5"=>array("R"=>224,"G"=>46,"B"=>117,"Alpha"=>100),
                  "6"=>array("R"=>92,"G"=>224,"B"=>46,"Alpha"=>100),
                  "7"=>array("R"=>224,"G"=>176,"B"=>46,"Alpha"=>100));

 /* Draw the chart */ 
 $myPicture->drawBarChart(array("DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>TRUE,"Rounded"=>TRUE,"Surrounding"=>30,"OverrideColors"=>$Palette));

 /* Write the legend */ 
 $myPicture->drawLegend(570,215,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->Stroke();
?>
