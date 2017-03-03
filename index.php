<?php
session_start();
require_once("php/loginModel.php");
$loginModel = new LoginModel();

?>
<!DOCTYPE html>
<html lang="en">
<div id="ajaxlogin"></div>
<head>
    <title>UA-CEAC's Lunar Greenhouse</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link id="pagestyle" rel="stylesheet" type="text/css" href="css/alarms.css">
</head>

<body>
	<div class="banner">
		<a href="http://www.arizona.edu/" target="_blank"><img src="img/bannerAZ.png" alt="The University of Arizona"></a>
    </div>
    
	<div class="header" >
        <img class="header_img" src="img/siteTitle.jpg" alt="UofA - Controlled Environment Agriculture Center | NASA's Steckler Grant :: The Prototype Lunar Greenhouse">        
    </div>
    
	<nav class="navbar navbar-default">
        <div class="navbar-container">
          <div class="navbar-header">
            <a class="navbar-brand" href="./">RENDSYS</a>
          </div>
          <div>
            <ul class="nav navbar-nav">
              <li id="overviewHead" class="active"><a href="./">System Overview</a></li>
              <li id="reportsHead"><a href="index.php?reports=show">Generate Reports</a></li>
              <?php
              if ($loginModel->isSignedIn()) {
                echo "<li id='alarmsHead'><a href='index.php?alarms=show'>Manage Alarms</a></li>";
              }
              else {
                echo "<li id='alarmsHead'><a href='index.php?alarms=show' onclick='alertFunc();return false'>Manage Alarms</a></li>";
              }
              ?>
              <li id="homeHead"><a href="https://cals.arizona.edu/lunargreenhouse/" target="_blank">Project Home</a></li>
              <?php
              if( $loginModel->isSignedIn()){
                  echo "<li id='loginHead'><a href='php/loginReceiver.php?logout=yes'>Logout</a></li>";
                  echo "<li><a>Hello " . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "</a></li>";
              }else{
                  echo "<li id='loginHead'><a href='index.php?login=show'>Login</a></li>";
              }
              ?>
              
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </nav>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
document.getElementById('pagestyle').setAttribute('href', "css/formStyle.css");
</script>
  <!--Added-->
  <?php
  if(isset($_GET['alarms'])){
    require_once("alarms.php");
  }else if(isset($_GET['reports'])){
    require_once("reports.php");
  }else if(isset($_GET['login'])){
    require_once("login.php");
  }else if(isset($_GET['register'])){
    require_once("register.php");
  }else{
    require_once("overview.php");
  }
  ?>
  
  </script>
<script type="text/javascript"> 

function alertFunc(){  

  if ((confirm("You need log-in first!"))) {
    parent.window.location="index.php?login=show"; 

    //return true; 
  };
  //return false;

} 

</script> 

</body>

</html>