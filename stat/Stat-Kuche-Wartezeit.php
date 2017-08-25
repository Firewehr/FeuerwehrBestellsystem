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
 include("../include/pChart/class/pImage.class.php");
 
 /* Create and populate the pData object */
 $MyData = new pData();  
	/* Build the query that will returns the data to graph */
	$Requete = "
		SELECT DATE_FORMAT( bestellungen.zeitstempel, '%H:%i' ) AS Bestellzeit, 
		AVG( TIMESTAMPDIFF(MINUTE , bestellungen.zeitstempel, zeitKueche ) ) AS diffzeit, 
		MAX( TIMESTAMPDIFF(MINUTE , bestellungen.zeitstempel, zeitKueche ) ) AS maxzeit, 
		MIN( TIMESTAMPDIFF(MINUTE , bestellungen.zeitstempel, zeitKueche ) ) AS minzeit, 
		COUNT( * ) AS anzahl, FLOOR( UNIX_TIMESTAMP( bestellungen.zeitstempel ) /900 ) AS t
		FROM bestellungen, positionen
		WHERE positionen.rowid = bestellungen.position
		AND `delete` =0
		AND `kueche` =1
		AND TYPE =1
		AND zeitKueche != '0000-00-00 00:00:00'
		GROUP
		BY t
		ORDER BY Bestellzeit ASC";
	$Result = mysqli_query($conn, $Requete);
	$Bestellzeit=""; $diffzeit=""; $maxzeit=""; $minzeit="";
	while ($row = mysqli_fetch_array ($Result))
	{
	/* Get the data from the query result */
		$Bestellzeit = $row["Bestellzeit"];
		$diffzeit = $row["diffzeit"];
		$minzeit = $row["minzeit"];
		$maxzeit = $row["maxzeit"];
		
		$MyData->addPoints($Bestellzeit,"Bestellzeit");
		$MyData->addPoints($diffzeit,"diffzeit");
		$MyData->addPoints($minzeit,"minzeit");
		$MyData->addPoints($maxzeit,"maxzeit");
	}
	
	$MyData->setAxisName(2,"diffzeit");
	$MyData->setSerieDescription("Bestellzeit","Bestellzeit");	
	$MyData->setAbscissa("Bestellzeit");
	$MyData->setSerieTicks("maxzeit",2);
	$colour1= array("R"=>229,"G"=>11,"B"=>11);
	$MyData->setPalette("maxzeit",$colour1);
	$MyData->setSerieWeight("minzeit",2);
 
 /* Create the pChart object */
 $myPicture = new pImage(700,230,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../include/pChart/fonts/Forgotte.ttf","FontSize"=>11));
 $myPicture->drawText(150,35,"Wartezeit Kueche",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../include/pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,650,200);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawLineChart();

 /* Write the chart legend */
 $myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->Stroke();
?>
