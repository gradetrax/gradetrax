<?php

$TITLE = "Create Account";
require 'header.php';

/*function aes_encrypt($val)
{
	$key = mysql_aes_key('Ralf_S_Engelschall__trainofthoughts');
	$pad_value = 16-(strlen($val) % 16);
	$val = str_pad($val, (16*(floor(strlen($val) / 16)+1)), chr($pad_value));
	return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
}

function aes_decrypt($val)
{
	$key = mysql_aes_key('Ralf_S_Engelschall__trainofthoughts');
	$val = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
	return rtrim($val, "\0..\16");
}

// $decrypted_value = rtrim($decrypted_value, "\0..\16");
*/
if (isset($_POST['submit'])) {

	if ($_POST['name'] == '') {
		echo "Please enter a username<br>";
	} else if ($_POST['pass'] == '') {
		echo "Please enter a password<br>";
	} else if (strpos($_POST['name'], "@") !== false) {
		echo "Please do not include the @ symbol in your username<br>";
		} else if (preg_match("/^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]*([.][a-zA-Z0-9_]+)*[\.]*unt[\.]edu$/", $_POST['email']) <= 0) {
		echo "Please enter a valid UNT email<br>";
	} else {
	//	$pt_pass = $_POST['password'];	// Plain text password = form submitted password
		$query = "INSERT INTO students (username, password, email) VALUES ('" . $_POST['name'] . "', '" . $_POST['pass'] . "', '" . $_POST['email'] . "')";
	//	echo $query;
		if (mysql_query($query)) {
			echo <<<EOT
			Account created.
			<br><br>
			<a href="login.php" class="mainButton">Log In</a>
EOT;
			$accountMade = TRUE;
		} else
			echo "mysql error: " . mysql_error();
	}
	
}

if (!isset($accountMade)) {
	?>

	<h3>Create an Account</h3>

	Note: Your email must end with unt.edu
	<br>(example@my.unt.edu or example@unt.edu)
	<br><br><br>

	<?php
	
	// Define default values
	if (isset($_POST['name']))
		$name = $_POST['name'];
	else
		$name = "";
		
	if (isset($_POST['pass']))
		$pass = $_POST['pass'];
	else
		$pass = "";
		
	if (isset($_POST['email']))
		$email = $_POST['email'];
	else
		$email = "";
	
	
	// Print the form
	echo <<<EOT
	<form id="accountForm" method="POST" action="">
	Username: <input type="text" name="name" value="$name" autofocus />
	<br><br>Password: <input type="password" name="pass" value="$pass" />
	<br><br>School Email: <input type="text" name="email" value="$email" />
	<br><br><input type="submit" name="submit" value="submit">
	</form>
EOT;

}
	
require 'footer.php';
?>
