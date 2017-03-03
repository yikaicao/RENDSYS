<?php
session_start();
require_once('./alarmModel.php');
header("Location: ../index.php?alarms=show");

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

echo "This is the receiver page:<br>";

$alarmModel = new AlarmModel();

if(isset($_POST["alarmID"])){
	//Removes selected alarm
	$alarmModel->removeAlarm($_POST["alarmID"]);
}else{
	//Adding a new alarm
	//Y values sometimes doesn't exist for some options
	if(isset($_POST["yVal"])){
		$alarmModel->addAlarm($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['firstname'], $_SESSION['lastname'], $_POST["dCollection"], $_POST["dataType"], $_POST["alarmType"], $_POST["xVal"], $_POST["yVal"], $_POST["phoneNumber"], $_POST["carrierName"]);
	}else{
		$alarmModel->addAlarm($_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['firstname'], $_SESSION['lastname'], $_POST["dCollection"], $_POST["dataType"], $_POST["alarmType"], $_POST["xVal"], null, $_POST["phoneNumber"], $_POST["carrierName"]);
	}
}


//For debugging
$alarmModel->printColumns();
$alarmModel->printDBEntries();



echo "<a href='../index.php?alarms=show'>GO BACK</a>";

exit; //Leave page to the header location
?>