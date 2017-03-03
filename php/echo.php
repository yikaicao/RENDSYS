<?php
 $string = $_POST["string"];
 // echo "String is: "
 // echo $string;


$con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("cr3000", $con);

$sql="INSERT INTO cr3000_Table (Time)
VALUES(";

$sql .= "'" .$string ."')";
if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "1 record added";

exit;
?>