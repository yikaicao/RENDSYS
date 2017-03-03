<?php
$column = $_POST["header"];
// $column = "Time, Record,Batt_volt_Min,
// 	Batt_volt_TMn, CO2_Room_ppm_Avg, CO2_BLSS_ppm_Avg, Counter_CO2_sec,
// 	Room_C_Avg, Room_RH_Avg, BLSS_WET_C_Avg, BLSS_WET_RH_Avg, BLSS_DRY_C_Avg,
// 	BLSS_DRY_RH_Avg, RH_Control_SetPt_Avg, AirT_CoolSetPt_Diff_Avg, Heating_Control_Avg,
// 	BaroPress_Avg, O2_MASTER_Percent_Avg, DO2A_ppm_Avg, DO2B_ppm_Avg, EC_Tank_A_mS_Avg,
// 	EC_Tank_B_mS_Avg, RunAvgEC_A, RunAvgEC_B, LC_Nut_A_Avg, LC_Nut_B_Avg,
// 	PH_Tank_A_pH_Avg, PH_Tank_B_pH_Avg, Prt8pHB_Tot, RunAvgpH_A, RunAvgpH_B,
// 	LC_Acid_A_Avg, LC_Acid_B_Avg, LAMP_DI_TANK_C_Avg, GLYCOL_HEAT_EX_H2O_TANK_C_Avg,
// 	NUTRIENT_TANK_C_Avg, RunAvgHID_Bulb_Temp, BLSS_Exhaust_C_Avg,
// 	SST_Avg_1, SST_Avg_2, SST_Avg_3, HID_Bulb_Temp_Avg, PAR_Avg,
// 	BLSS_Dew_Point_C_Avg, Dewpoint_and_Coolant_Difference_C_Avg, LAMP_VALVE_OPEN_Percent,
// 	Depth_1, Depth_1_Vol, Depth_2, Depth_2_Vol, Depth_3, Depth_3_Vol, BLSS_MASS_Avg,
// 	CO2_Bottle_Mass_Avg, Heating_Valve_Avg, Cooling_Valve_Avg, laborMeas, floodMeas_Avg, newColumn1, newColumn2, newColumn3,newColumn4,newColumn5,newColumn6,newColumn7";
$newData = $_POST["string"];
//$newData = "\"2015-07-30 17:30:00\",6,12.95,\"2015-07-30 17:28:30\",420.9,502.5,0,27.86,39.33,27.54,39.44,27.62,67.61,39.43,8.54,27.54,92.6,20.76,\"NAN\",6.239,-0.012,2.246,-0.012,2.246,12.69,7.235,-0.756,5.734,0,-0.756,5.734,-0.5,11.34,27.12,0,0,31.87,0,30.53,30.33,27.12,31.88,250.6,12.47,14.65,6,0.171,28.02,0.274,59.33,0.387,89.1,88.7,-0.027,0,7999,1,0,1,2,3,4,5,6,7";

$column_count = 0;
$column_total = 0;
$column_token = strtok($column, ",");
$sql = "";
$colunmns = array($column_token);

// $lastEntry = "";
// $dataType = "Record";
// $alarmType = "isNotBetween";
// $x = 1;
// $y = 8;
// $alarmData = "";

// $lastTime = "";
// $compareData = "";

$con = mysql_connect("yikaicao.com", "cs436db", "cs436db");
//$con = mysql_connect("localhost", "root");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

//mysql_select_db("LunarGreenHouse", $con);
mysql_select_db("cr3000", $con);

//get column numbers
$result = mysql_query("SELECT * FROM status_Table");
if (!$result) {
	die('Error: ' . mysql_error());
	exit;
}
else{
	$column_nums = mysql_num_fields($result);
}

//get total fields of headers
while($column_token !== false){
	$column_total++;
	$column_token = strtok(",");
	$colunmns[$column_total] = $column_token;
}

function normal_inesrt(){
	global $column_count, $column_token, $newData, $con, $colunmns, $column_total;
	$sql = "REPLACE INTO status_Table(";

//get column name;
while($column_count < $column_total){
	$colunmns[$column_count] = str_ireplace("\"", "", $colunmns[$column_count]);
	//$column_token = str_ireplace("\"", "", $column_token);
	if ($column_count == $column_total-1) {
		$sql .=  $colunmns[$column_count] ;
		$sql .= ')';
	}
	else
 		$sql .= $colunmns[$column_count] .",";
	//$column_token = strtok(",");
	$column_count++;
}
$sql .= "VALUES(";

$token = strtok($newData, ",");
$count = 0;

while ($count < $column_total) {
	$token = str_ireplace("\"", "", $token);
	
	if ($count == $column_total-1) {
		$token = str_ireplace(array("\n", "\r"), "", $token);
		$sql .= "'" .$token ."'";
		$sql .= ')';
	}
	else
		$sql .= "'".$token ."',";
	$token = strtok(",");
	$count++;
}

if (!mysql_query($sql,$con))
  {

  die('Error: ' . mysql_error());
  }
echo "1 record added";
}

	//do normal insert;
	echo"<br>insert start<\br>";
	normal_inesrt();	
	//getAlarmData();
	echo"<br>insert ended<\br>";

exit;
?> 
