<!DOCTYPE html>
<html lang="en">

<?php
require_once('php/alarmModel.php');
$alarmModel = new AlarmModel();

if (!$loginModel->isSignedIn()){
	header("Location: ./index.php");
	exit;
}
?>
<script>
document.getElementById('pagestyle').setAttribute('href', "css/alarms.css");

//Make other headers inactive
document.getElementById('overviewHead').setAttribute('class', "inactive");
document.getElementById('reportsHead').setAttribute('class', "inactive");
document.getElementById('loginHead').setAttribute('class', "inactive");
document.getElementById('alarmsHead').setAttribute('class', "active");

</script>

<div class="container">
	<div class="alarms_div">
		<h4>Stored Alarms</h4>
		<ol class="alarm_list" type="1">
			<?php $alarmModel->printAlarmsToTable();?>
		</ol>
	</div>	
<div class="create_alarm_div">
<h4 class="titleWords">Create Alarm</h4>
<form class="alarmForm" action="php/alarmReciever.php" method="post">
	Notification Phone Number<input type="text" name="phoneNumber" id="phoneNumber" pattern="^(\+0?1\s)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$[A-Za-z\s]*" required>
	<br><br>

	Phone Carrier<br>
	<select name="carrierName" id="carrierName">
	<option value="Verizon" selected="selected">Verizon</option>
	<option value="T-Mobile">T-Mobile</option>
	<option value="AT&T">AT&T</option>
	</select>

	<br><br>
	Data Collection<br>
	<select name="dCollection" id="dCollection">
	<option value="LunarGreenhouse" selected="selected">Lunar Greenhouse</option>
	</select>
	<br><br>
	Data to Check<br>
	<select name="dataType" id="dataType">
	<option>Loading...</option>
	</select>
	<br><br>
	Alarm Type <br>
	<select name="alarmType" id="alarmType" onchange="showAlarmExample();">
	<br><br><br>
	<option value="IsNotBetween" selected="selected">IsNotBetween</option>
	<option value="ChangesByWithinTime">ChangesByWithinTime</option>
	<option value="LessThanOrEqual">LessThanOrEqual</option>
	</select>
	<br>
	<div id="rExample"></div> <!--Shows example of the alarm type-->
	<div id="varDiv"></div> <!--Shows X and Y fields-->
	<br>
	<input type="submit" name="createButton" id="createButton" value="Create">
	<br>
</form>

</div>
	</div>

	<script>
	//Gets data names from the CR3000.dat file
	function getDataTypes(){
		var dataText;
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				//Split text by newline, then comma
				var optionHTML = ""
				var values = this.responseText.split('\n');
				values = values[1].split(',');

				//Remove double quotes and parenthesis and add underscore
		        for(i = 0; i < values.length; i++){
		          values[i] = values[i].replace(/["]+/g, '').replace(/[(]+/g, '_').replace(/[)]+/g, '');
		        }

				//Skip first value, which is TIMESTAMP
				for(i = 1; i < values.length; i++){
					if(i == 1){
						optionHTML = optionHTML + '<option value=' + values[i] + ' selected="selected">' + values[i] + '</option>';
					}else{
						optionHTML = optionHTML + '<option value=' + values[i] + '>' + values[i] + '</option>';
					}
				}
				document.getElementById("dataType").innerHTML = optionHTML;
			}
		};
		xhttp.open("GET", "dat/CR3000.dat", true);
		xhttp.send();
	}

	// Displays a text example of the alarm on the webpage. Changes with Alarm Type options
	function showAlarmExample(){
		var optionTable = document.getElementById("alarmType");
		var exampleDiv = document.getElementById("rExample");
		var varDiv = document.getElementById("varDiv");
		var alarmType = optionTable.options[optionTable.selectedIndex].value;

		var xElement = 'X : <input type="number" step=".001" name="xVal" id="xVal" required>';
		var yElement = 'Y : <input type="number" step=".001" name="yVal" id="yVal" required>';

		switch(alarmType){
			case "IsNotBetween":
				exampleDiv.innerHTML = "Is Not Between [X,Y]";
				varDiv.innerHTML = xElement + '<br>' + yElement + '<br>';
				break;
			case "ChangesByWithinTime":
				exampleDiv.innerHTML = "Changes by: [X] Within time: [Y] minutes";
				varDiv.innerHTML = xElement + '<br>' + yElement + '<br>';
				break;
			case "LessThanOrEqual":
				exampleDiv.innerHTML = "Less Than or Equal [X]";
				varDiv.innerHTML = xElement + '<br>';
				break;
			default:
				exampleDiv.innerHTML = "Choose a valid alarm type";
		}
	}
	showAlarmExample();
	getDataTypes();
	</script>
	
