<?php
//This class handles alarm database queries

class AlarmModel{
	private $tableName;
	private $Database;

	public function __construct(){
		$this->tableName = "alarm_Table"; //Make sure this is correct

		$db= 'mysql:dbname=cr3000;host=yikaicao.com';
		$user = 'cs436db';
		$password = 'cs436db';
		try{
		    $this->DataBase = new PDO($db, $user, $password);
		    $this->DataBase->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
		    echo $e->getMessage();
		    exit();
		}
	}

	//Prints out all table column names
	public function printColumns(){
		$db = $this->DataBase;
		$stmt = $db->prepare("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='cr3000' AND `TABLE_NAME`='". $this->tableName . "'");
		$stmt->execute();
		$allRecords = $stmt->fetch(MYSQL_ASSOC);

		echo "Column names: <br>";
		if($allRecords == null){
			echo "No value returned<br>";
		}else{
			while($allRecords != null){
				echo $allRecords[0] . ", ";
				$allRecords = $stmt->fetch(MYSQL_ASSOC);
			}
			echo "<br>";
		}
	}

	//Prints all database rows to this php page
	public function printDBEntries(){
		$db = $this->DataBase;
		$stmt = $db->prepare("SELECT * FROM " . $this->tableName . " ORDER BY create_time");
		$stmt->execute();
		$tempRecord = $stmt->fetch(MYSQL_ASSOC);

		echo "<br>All " . $this->tableName . " entries:<br>";
		if($tempRecord == null){
			echo "No value returned<br>";
		}else{
			while($tempRecord != null){
				echo "<br>";
				foreach($tempRecord as $key => $value){
					if($key !== 'queryString'){
						echo $key . ":" . $value . ", ";
					}
				}
				echo "<br>";
				$tempRecord = $stmt->fetch(MYSQL_ASSOC);
			}
		}
	}

	//Adds alarm to the database
	//The First and last names are going to be from the session login name
	public function addAlarm($create_first, $create_last, $notify_first, $notify_last, $collection, $data_type, $alarm_type, $x, $y, $phone_number, $carrier){
	    $db = $this->DataBase;

	    $stmt = $db->prepare ( "INSERT INTO " . $this->tableName . " (create_first, create_last, notify_first, notify_last, collection, data_type, alarm_type, x, y, phone_number, last_occurence, carrier) values (:create_first, :create_last, :notify_first, :notify_last, :collection, :data_type, :alarm_type, :x, :y, :phone_number, NULL, :carrier)" );
		
		$stmt->bindParam ( 'create_first', $create_first );
		$stmt->bindParam ( 'create_last', $create_last );
		$stmt->bindParam ( 'notify_first', $notify_first );
		$stmt->bindParam ( 'notify_last', $notify_last );
		$stmt->bindParam ( 'collection', $collection );
		$stmt->bindParam ( 'data_type', $data_type );
		$stmt->bindParam ( 'alarm_type', $alarm_type );
		$stmt->bindParam ( 'x', $x );
		$stmt->bindParam ( 'y', $y );
		$stmt->bindParam ( 'phone_number', $phone_number );
		$stmt->bindParam ( 'carrier', $carrier );

		$stmt->execute();
	}

	//Removes specified alarm from the database
	public function removeAlarm($alarmID){
		$db = $this->DataBase;

		$stmt = $db->prepare("DELETE FROM " . $this->tableName . " WHERE alarmID=:alarmID");
		$stmt->bindParam ( 'alarmID', $alarmID );
		$stmt->execute();
	}

	//Will remove this when removeAlarm is implemented
	public function deleteAllAlarms(){
		$db = $this->DataBase;

		$stmt = $db->prepare("DELETE * FROM " . $this->tableName);
		$stmt->execute();
	}

	//Prints alarm data as an html table
	public function printAlarmsToTable(){
		$db = $this->DataBase;
		$stmt = $db->prepare("SELECT * FROM " . $this->tableName . " ORDER BY create_time");
		$stmt->execute();
		$tempRecord = $stmt->fetch(MYSQL_ASSOC);

		$resultText = '';
		if($tempRecord == null){
			//Don't print anything
		}else{
			//Keep repeating for each row
			while($tempRecord != null){
				$resultText .= '<li><img ';
				//Show disabled if no one to notify
				if($tempRecord['notify_first'] == null){
					$resultText .= 'src="./img/toggleDisabled.png" alt="disabled"';
				}else{
					$resultText .= 'src="./img/toggleEnabled.png" alt="enabled"';
				}
				$resultText .= '><b>[';
				
				switch($tempRecord['collection']){
					case 'LunarGreenhouse':
						$resultText .= 'Lunar Greenhouse';
						break;
					default:
						$resultText .= 'Unknown Collection';
				}

				$resultText .= ' → ';
				$resultText .= $tempRecord['data_type'];
				$resultText .= ']<br>';
				switch($tempRecord['alarm_type']){
					case 'IsNotBetween':
						$resultText .= 'Is Not Between [' . $tempRecord['x'] . ', ' . $tempRecord['y'] . ']';
						break;
					case 'ChangesByWithinTime':
						$resultText .= 'Changes By: [' . $tempRecord['x'] . '] Within Time: [' . $tempRecord['y'] . '] minutes';
						break;
					case 'LessThanOrEqual':
						$resultText .= 'Less Than or Equal [' . $tempRecord['x'] . ']';
						break;
					default:
						$resultText .= 'unknown alarm type';
				}

				//Smaller font for lower text
				$resultText .= '</b><br><div style="font-size:80%">';

				$resultText .= '<b>Created By:</b> ' . htmlspecialchars($tempRecord['create_first']) . ' ' . htmlspecialchars($tempRecord['create_last']) . ' &nbsp&nbsp <b>Created On:</b> ' . $tempRecord['create_time'] . ' &nbsp&nbsp <b>Last Occurrence:</b> ' . $tempRecord['last_occurence'] . '<br><b>Notification List:</b> ' . htmlspecialchars($tempRecord['notify_first']) . ' ' . htmlspecialchars($tempRecord['notify_last']);

				
				$resultText .= '</div>';

				//Add remove button that removes selection from data base and refreshes the page
				$resultText .= '<form action="php/alarmReciever.php" method="post"><input type="hidden" name="alarmID" value="' . $tempRecord['alarmID'] . '"> <input type="submit" name="removeButton" id="removeButton" value="Remove"> </form>';

				$resultText .= '</li>';

				$tempRecord = $stmt->fetch(MYSQL_ASSOC);
			}
		}
		//Print out all the text
		echo $resultText;
	}
}

?>