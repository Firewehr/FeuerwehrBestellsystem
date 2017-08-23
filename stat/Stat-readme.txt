Readme - Stats
abkürzung für Statistik.
Hier kommen alle Diagramme hinein, die später mit "echo '<img src="\stat\Stat-Storno-Kellner.php">';  verwendet werden.
Zum Testen mit dem Browser auf http://example.com/stat/Stat-Storno-Kellner.php gehen.


bei pChart muss daher folgende Pfade geändert werden:
####################################
/* CAT:Labels */
 include ("../include/db.php"); /* Datenbank BN+PW  */

 /* pChart library inclusions */
 include("../include/pChart/class/pData.class.php");
 include("../include/pChart/class/pDraw.class.php");
 include("../include/pChart/class/pPie.class.php");
 include("../include/pChart/class/pImage.class.php");
 
 myPicture->setFontProperties(array("FontName"=>"../include/pChart/fonts/Forgotte.ttf"
 ...
 ####################################
 
 Wenn der SQL Befehl die Tabelle mit einen Namen und darin ein Punkt sich befindet, z.B. tische.tischname dann sollte man diesen Umbenennen mit dem AS-Befehl, ansonsten kann pChart es nicht gerkennen.
 tische.tischname as tischnr 
 
 ####################################
 SELECT SUM( positionen.betrag ) as summe , tische.tischname as tischnr FROM `bestellungen` , positionen, tische WHERE tische.tischnummer = bestellungen.tischnummer AND bestellungen.position = positionen.rowid AND bestellungen.zeitKueche != '0000-00-00 00:00:00' AND bestellungen.delete =0 GROUP BY bestellungen.tischnummer ORDER BY tischnr ASC";
 ####################################
 
 
 Am Schluss sollte der Befehl  $myPicture->Stroke(); ausgeführt werden. Möglich sollte auch der Auto-Befehl sein.
 
 ####################################
  /* Render the picture (choose the best way) */
 $myPicture->Stroke();
 ####################################
 
 Hier ein Beispiel für die Abfrage. Leider muss das addPoints auch in der while-Schleife sein, ansonsten nimmt er nur den letzten Wert.
 
 ####################################
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
#####################################


