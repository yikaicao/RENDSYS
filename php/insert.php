<?php
$column = $_POST["header"];
// $column = "TIMESTAMP, Record,Batt_volt_Min,
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
//$newData = "\"2016-07-30 17:30:00\",6,12.95,\"2015-07-30 17:28:30\",420.9,502.5,0,27.86,39.33,27.54,39.44,27.62,67.61,39.43,8.54,27.54,92.6,20.76,\"NAN\",6.239,-0.012,2.246,-0.012,2.246,12.69,7.235,-0.756,5.734,0,-0.756,5.734,-0.5,11.34,27.12,0,0,31.87,0,30.53,30.33,27.12,31.88,250.6,12.47,14.65,6,0.171,28.02,0.274,59.33,0.387,89.1,88.7,-0.027,0,7999,1,0,1,2,3,4,5,6,7";

$column_count = 0;
$column_total = 0;
$column_token = strtok($column, ",");
$sql = "";
$colunmns = array($column_token);

$lastEntry = "";
$dataType = "Record";
$alarmType = "isNotBetween";
$x = 1;
$y = 8;
$alarmData = "";

$lastTime = "";
$compareData = "";
$cCount = 0;

$phoneNum = "";
$carrier = "";
$con = mysqli_connect("yikaicao.com", "cs436db", "cs436db", "cr3000");
if (mysqli_connect_errno()) {
    echo "Connect failed: %s " . mysqli_connect_error();
    exit();
}//check connection
// $r = mysqli_query($con, "SHOW COLUMNS FROM cr3000_Table");
// if (!$r) {
// 	echo 'Could not run.' . mysqli_error();
// 	exit;
// }
// if (mysqli_num_rows($r)>0) {
// 	while ($row = mysqli_fetch_assoc($r)) {
//         print_r($row);
//         echo "</br>";
//     }
// }// show all fields of server.
//$res = mysqli_query($con, "ALTER TABLE cr3000_Table ADD COLUMN LC_StockA_Avg double (16,3) default '0.000'");

$sql = "SELECT * FROM cr3000_Table";

//get column numbers
if ($result=mysqli_query($con,$sql)) {
	$column_nums = mysqli_num_fields($result);//get all column num;
	//printf("Result set has %d fields\n", $column_nums);

	mysqli_free_result($result);
}

//get total fields of headers
while($column_token !== false){
	$column_total++;
	$column_token = strtok(",");
	$colunmns[$column_total] = $column_token;
}

function normal_inesrt(){
	global $column_count, $column_token, $newData, $con, $colunmns, $column_total;
	$sql = "REPLACE INTO cr3000_Table(";
//get column name;
while($column_count < $column_total){
	$colunmns[$column_count] = str_ireplace("\"", "", $colunmns[$column_count]);
	//$column_token = str_ireplace("\"", "", $column_token);
	
	$newColumn = $colunmns[$column_count];
	$checkSql = "SELECT ".$newColumn." FROM cr3000_Table";
	//echo $checkSql;
	if (!$check=mysqli_query($con,$checkSql)) {
		$newSql = "ALTER TABLE cr3000_Table ADD COLUMN " .$newColumn ." double (16,3) default '0.000'";
		if(!$res = mysqli_query($con,$newSql)){
			echo 'Could not run.' . mysqli_error();
			exit;
		}
	}

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
	if ($count == 0 || $count == 3 || $count == 18) {
		$token = str_ireplace("\"", "", $token);
	}
	
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
//echo "$sql";
if ($res=mysqli_query($con,$sql))
  {	
  	echo "1 record added ";
  }
  else{echo "false here." . mysqli_error($con);} 	
}

function sendMail(){
	global $carrier, $phoneNum, $alarmData, $alarmType, $x, $y, $dataType;
	
	if ($alarmType == "LessThanOrEqual") {
		$msg = "Sean, here is an alarm, $dataType $alarmData which $alarmType $x";
	}
	else
		{$msg = "Sean, here is an alarm $dataType $alarmData which $alarmType $x and $y";}

	$phoneNum = str_ireplace("-", "", $phoneNum);
	
	$domain = "";
	switch($carrier){
			case 'Verizon':
				$domain = "@vtext.com";
			break;
			case 'AT&T':
				$domain = "@txt.att.net";
			break;
			case 'T-Mobile':
				$domain = "@tmomail.net";
			break;
	}
	$mailAd = "$phoneNum" . "$domain";
	mail($mailAd, "Alarm", $msg);
	echo "<br>MESSAGE SENT TO $mailAd</br></br>";
}

function getLatestLineData(){
	global $dataType, $alarmData, $lastTime, $con;
	$lastEntry = mysqli_query($con,"SELECT * From cr3000_Table
  	order by TIMESTAMP desc limit 1");

  	while($row = mysqli_fetch_assoc($lastEntry)){
  		if ($dataType = "RECORD") {
  			$dataType = "Record";
  		}
    $alarmData = $row[$dataType];
    $lastTime = strtotime($row['TIMESTAMP']);
  }
}

function getChangesTimeData(){
	global $dataType, $compareData, $y, $lastTime, $con;
	$compareTime = $lastTime - $y*60;

	$compareTime = date("Y-m-d H:i:s", $compareTime);

	$lastEntry = mysqli_query($con,"SELECT TIMESTAMP, $dataType From cr3000_Table
		WHERE TIMESTAMP = '$compareTime'");

  	while($row = mysqli_fetch_assoc($lastEntry)){
    $compareData = $row[$dataType];
    $time = ($row["TIMESTAMP"]);
    echo "time is : $time</br>";
    echo "compareData is : $compareData</br>";
  }
}

function getAlarmData(){
	global $x, $y, $alarmType, $dataType, $alarmData, $compareData,$con,$carrier,$phoneNum;

	$alarmTable = mysqli_query($con,"SELECT * From alarm_Table");

	while ( $row = mysqli_fetch_assoc($alarmTable)) {
		$alarmType = $row['alarm_type'];
		$dataType = $row['data_type'];
		$carrier = $row['carrier'];
		$phoneNum = $row['phone_number'];

		getLatestLineData();
		switch ($alarmType) {
		case 'IsNotBetween':
			$x = $row['x'];
			$y = $row['y'];
			if($alarmData <= $y && $alarmData >= $x){
				sendMail();			
			}
			else{
				echo "$dataType is normal data</br></br>";
			}
			break;
		case 'LessThanOrEqual':
			$x = $row['x'];

			if ($alarmData <= $x) {
				sendMail();
			}

			else{
				echo "$dataType is normal data</br></br>";
			}
			break;
		case 'ChangesByWithinTime':
			$x = $row['x'];
			$y = $row['y'];

			getChangesTimeData();

			if ($alarmData + $x <= $compareData) {
				sendMail();
			}

			else{
				echo "$dataType is normal data</br></br>";
			}
			break;
		default:
			break;
		}

	}
}

function addColumns(){
	global $column_count, $column_token, $newData, $con, $colunmns,$column_total,$column_nums;


	$addSql = "ALTER TABLE cr3000_Table ADD ";

	$newCoNum = $column_total;
	$newCoCount = $column_nums;

	while ( $newCoCount < $newCoNum) {
		$colunmns[$newCoCount] = str_ireplace("\"", "", $colunmns[$newCoCount]);
	//$column_token = str_ireplace("\"", "", $column_token);
	if ($newCoCount == $newCoNum-1) {
		$addSql .=  $colunmns[$newCoCount] ." double (16,3) " ."default '0.000'";
	}
	else
 		$addSql .= $colunmns[$newCoCount] ." double (16,3) " ."default '0.000'" .", ADD ";
	//$column_token = strtok(",");
		$newCoCount++;
	}
	echo "$addSql</br>";
	if (!$result=mysqli_query($con,$addSql))
  	{

  		die('Error: ' . mysqli_error($con));
  	}
	echo "columns added";
	
}

if ($column_total > $column_nums) {
	//update table;
	addColumns();
	normal_inesrt();
	getAlarmData();
}

else{
	//do normal insert;
	normal_inesrt();	
	getAlarmData();

}
mysqli_close($con);
?> 
