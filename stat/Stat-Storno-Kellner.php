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
	$Requete = "SELECT kellner, COUNT(*) as anzahl FROM `bestellungen` WHERE bestellungen.delete != 0 GROUP BY kellner";
	$Result = mysqli_query($conn, $Requete);
	$kellner=""; $anzahl="";
	while ($row = mysqli_fetch_array ($Result))
	{
	/* Get the data from the query result */
  $anzahl = $row["anzahl"];
  $kellner = $row["kellner"];
   $MyData->addPoints($anzahl,"Anzahl");
   $MyData->addPoints($kellner,"Kellner");
	}
	
 /* Create and populate the pData object */
	
  $MyData->setSerieDescription("Anzahl","Anzahl");

 /* Define the absissa serie */

 $MyData->setAbscissa("Kellner");

 /* Create the pChart object */
 $myPicture = new pImage(300,260,$MyData);

 /* Draw a solid background */
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,300,300,$Settings);

 /* Overlay with a gradient */
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,300,260,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,300,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>100));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,299,259,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the picture title */ 
 $myPicture->setFontProperties(array("FontName"=>"../include/pChart/fonts/Silkscreen.ttf","FontSize"=>6));
 $myPicture->drawText(10,13,"Stornierungen pro Kellner",array("R"=>255,"G"=>255,"B"=>255));

 /* Set the default font properties */ 
 $myPicture->setFontProperties(array("FontName"=>"../include/pChart/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

 /* Enable shadow computing */ 
 $myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>50));

 /* Create the pPie object */ 
 $PieChart = new pPie($myPicture,$MyData);

 /* Draw an AA pie chart */ 
 $PieChart->draw2DRing(160,140,array("WriteValues"=>TRUE,"ValueR"=>255,"ValueG"=>255,"ValueB"=>255,"Border"=>TRUE));

 /* Write the legend box */ 
 $myPicture->setShadow(FALSE);
 $PieChart->drawPieLegend(15,40,array("Alpha"=>20));

 /* Render the picture (choose the best way) */
 $myPicture->Stroke();
?>