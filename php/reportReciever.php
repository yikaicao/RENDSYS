<?php
$wantedField = "*";
$startDate  = $_POST["startDate"];
$endDate = $_POST["endDate"];
// $data_select = $_POST["dataType"];
// echo $data_select;

$data_chosen = $_POST["dataChosen"];

//$condition = "2015-08-01 00:00:00";
$con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
// $con = mysql_connect("localhost", "root");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("cr3000", $con);

// mysql_select_db("LunarGreenHouse", $con);
$result = mysql_query("SELECT $wantedField From cr3000_Table
	WHERE TIMESTAMP >= '$startDate' 
	AND TIMESTAMP <= '$endDate'");

while($row = mysql_fetch_array($result))
  {
  //echo "Temperature<br>";
  foreach ($data_chosen as $outputData) {
    echo $outputData;
    echo ": ";
    echo $row[$outputData];
    echo "<br />";
  }
  }

exit;
?> 
