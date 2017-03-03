<?php
//This class handles login database queries

//ALL tables
//CREATE TABLE loginTable (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,email varchar(64) NOT NULL default '',hash varchar(255) NOT NULL default '', firstname varchar(64) NOT NULL default '', lastname varchar(64) NOT NULL default '');

class LoginModel{
	private $tableName;
	private $Database;

	public function __construct(){
		$this->tableName = "loginTable"; //Make sure this is correct

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

	public function runSQLCommand($command){
		$db = $this->DataBase;
		$stmt = $db->prepare($command);
		$stmt->execute();
	}

	public function isSignedIn(){
		if(isset($_SESSION['email'])){
			return True;
		}else{
			return False;
		}
	}

	//Returns the info about the user
	public function register($email, $password, $firstname, $lastname) {
		$db = $this->DataBase;

	    //First make sure email is unique
		$stmt = $db->prepare ( "SELECT id FROM " . $this->tableName . " WHERE email=:email" );
		$stmt->bindParam ( 'email', $email );
		$stmt->execute();

		$currRecord = $stmt->fetch();
		//Return error code if email exists
		if(count($currRecord) > 1){
			return null;
		}

		$hash = password_hash($password, PASSWORD_DEFAULT);

		$stmt = $db->prepare ( "INSERT INTO " . $this->tableName . " ( email, hash, firstname, lastname ) values( :email, :hash, :firstname, :lastname )" );
		$stmt->bindParam ( 'email', $email );
		$stmt->bindParam ( 'hash', $hash );
		$stmt->bindParam ( 'firstname', $firstname );
		$stmt->bindParam ( 'lastname', $lastname );
		try{
			$stmt->execute ();

			//Now get the user ID, fname, lname, publication
			$stmt = $db->prepare ( "SELECT id, firstname, lastname FROM " . $this->tableName . " WHERE email=:email" );
			$stmt->bindParam ( 'email', $email );
			$stmt->execute();

			$tempRecord = $stmt->fetch();
			return $tempRecord;
		}
		catch(PDOException $e){
			$returnValue = $query . "<br>" . $e->getMessage();
			return null;		    
		}

	}
		
	//Returns info of the user or null if doesnt exist
	public function login($email, $password) {
		$db = $this->DataBase;

		//Retrieve hash from database
		$stmt = $db->prepare ( "SELECT * FROM " . $this->tableName . " WHERE email=:email" );
		$stmt->bindParam ( 'email', $email );
		$stmt->execute();

		//Verify the hash value
		$currentRecord = $stmt->fetch ();
		$hash = $currentRecord ['hash'];
		$success = password_verify($password, $hash);
		if($success){
			//Now get the user ID
			$stmt = $db->prepare ( "SELECT id, firstname, lastname FROM " . $this->tableName . " WHERE email=:email" );
			$stmt->bindParam ( 'email', $email );
			$stmt->execute();

			$tempRecord = $stmt->fetch();
			return $tempRecord;
		}else{
			return null;
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
		$stmt = $db->prepare("SELECT * FROM " . $this->tableName . " ORDER BY id");
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

	
}
?>