

<script>
document.getElementById('pagestyle').setAttribute('href', "css/login.css");
</script>
<div class="centerView">
<h3>Create an account</h3>
<?php
if(isset($_GET['invalid'])){
	echo "<p style='color:red'>Email is already in use</p>";
}else if(isset($_GET['invCode'])){
	echo "<p style='color:red'>Wrong invitation code</p>";
}
?>
<form action="php/loginReceiver.php" method="post" id="registerInfo">

	Email:<br>
	<input type=email name='email' id='email' required>
	<br>
	Password:<br>
	<input type='password' name='password' id='password' required>
	<br>
	First Name:<br>
	<input type='text' autocorrect=off autocapitalize=words name='firstname' id='firstname' required> <br>
	Last Name:<br>
	<input type='text' autocorrect=off autocapitalize=words name='lastname' id='lastname' required> <br>
  Invitation Code:<br>
  <input type='text' autocorrect=off name='invCode' id='invCode' required> <br>
	<input type='submit' name='submitAccount' id='submitAccount'>
	<input type='hidden' name='register' value='yes'>
</form>
</div>
