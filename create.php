<?php

$TITLE = "Create Account";
require 'header.php';

if (isset($_POST['submit'])) {

	if ($_POST['name'] == '') {
		echo "Please enter a username";
	} else if ($_POST['pass'] == '') {
		echo "Please enter a password";
	} else if (preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]*([.][a-zA-Z0-9_]+)*[\.]*unt[\.]edu$/", $_POST['email']) <= 0) {
		echo "Please enter a valid UNT email";
	} else {
		$query = "INSERT INTO students (username, password, email) VALUES ('" . $_POST['name'] . "', '" . $_POST['pass'] . "', '" . $_POST['email'] . "')";
	//	echo $query;
		if (mysql_query($query))
			echo "Account created.";
		else
			echo "mysql error: " . mysql_error();
	}
	
} else {

?>

<h3>Create an Account</h3>

Note: Your email must end with unt.edu
<br>(example@my.unt.edu or example@unt.edu)
<br><br><br>

<form id="accountForm" method="POST" action="">
Username: <input type="text" name="name" />
<br><br>Password: <input type="password" name="pass" />
<br><br>School Email: <input type="text" name="email" />
<br><br><input type="submit" name="submit" value="submit">
</form>

<?php

}

require 'footer.php';
?>