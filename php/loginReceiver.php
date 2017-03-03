<h3>Login Receiver v2.3</h3>
<?php
session_start();
require_once('./loginModel.php');
$lModel = new LoginModel();
$INVITATIONCODE = "GREENHOUSE";

echo "PHP version: " . phpversion() . "<br><br>";

if(isset($_POST['register'])){
	echo "register post<br>";
	if(strcasecmp($_POST['invCode'], $INVITATIONCODE)){
		echo "wrong inv code<br>";
		header( "Location: ../index.php?register=show&invCode=wrong" );
		break;
	}
	$asArray = $lModel->register($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname']);

	if($asArray != NULL){
		echo htmlspecialchars("Successfully registered " . $_POST['email'] . ", id:" . $asArray['id'] . "," . $asArray['firstname'] . "," . $asArray['lastname']) . "<br>";
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['id'] = $asArray['id'];
		$_SESSION['firstname'] = $asArray['firstname'];
		$_SESSION['lastname'] = $asArray['lastname'];
		header("Location: ../index.php");
		echo "Success<br>";
	}else{
		header( "Location: ../index.php?register=show&invalid=yes" );
		echo "Error, email already exists<br>";
	}
}else if(isset($_POST['login'])){
	echo "login post<br>";
	//Needs to check for correct email and password
	$asArray = $lModel->login($_POST['email'], $_POST['password']);
	if($asArray != null){
		echo htmlspecialchars("Successfully logged in " . $_POST['email'] . ", id:" . $asArray['id'] . "," . $asArray['firstname'] . "," . $asArray['lastname']) . "<br>";
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['id'] = $asArray['id'];
		$_SESSION['firstname'] = $asArray['firstname'];
		$_SESSION['lastname'] = $asArray['lastname'];
		header("Location: ../index.php");
		echo "Success";
	}else{
		echo "Incorrect email or password<br>";
		header( "Location: ../index.php?login=show&invalid=yes" );
	}
}else if(isset($_GET['logout'])){
	// remove all session variables
	session_unset();
	// destroy the session 
	session_destroy();
	echo "Logged out<br>";
	header("Location: ../index.php");
}else{
	echo "Unknown post?<br>";
	header("Location: ../index.php?error=unknown");
}

//Command used to make table
//$lModel->runSQLCommand("CREATE TABLE loginTable (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,email varchar(64) NOT NULL default '',hash varchar(255) NOT NULL default '', firstname varchar(64) NOT NULL default '', lastname varchar(64) NOT NULL default '');");
$lModel->printColumns();
$lModel->printDBEntries();
echo "A-OK!";
?>
<hr>
<a href='../index.php'>Exit</a>