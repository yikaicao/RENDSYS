
<script>
//Make other headers inactive
document.getElementById('overviewHead').setAttribute('class', "inactive");
document.getElementById('reportsHead').setAttribute('class', "inactive");
document.getElementById('loginHead').setAttribute('class', "active");
document.getElementById('alarmsHead').setAttribute('class', "inactive");

document.getElementById('pagestyle').setAttribute('href', "css/login.css");
</script>
<!--Added code (and stylesheet)-->
<div class='centerView'>
<h2>Enter account info</h2>
<?php
if(isset($_GET['invalid'])){
  echo "<p style='color:red'>Incorrect Email or Password</p>";
}
?>
<form action="php/loginReceiver.php" method="post" id="loginInfo">

  Email:
  <br/>
  <input type=email name='email' id='email' required>
  <br>
  Password:
  <br/>
  <input type='password' name='password' id='password' required>
  <br>
  <input type='submit' name='submit' id='submit' value='Login'> <br/>
  <input type='hidden' name='login' value='yes'> <br/>
</form>

<h3><a href='index.php?register=show'>Register</a></h3>

</div>

