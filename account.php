<?php
$TITLE = "Account Settings";
require 'header.php';

echo "<h3>Account Settings: $_SESSION[username]</h3>";

$query = "SELECT * FROM students WHERE username='{$_SESSION['username']}'";
$result = mysql_fetch_array($result = mysql_query($query));

$fullName = $result['fullName'];
$email = $result['email'];

if (isset($_POST['submit'])) {
	if ($_POST['curPass'] != $result['password']) {
		echo "Current password entry incorrect!";
	} else {
		$email = addSlashes($_POST['email']);
		$fullName = addSlashes($_POST['fullName']);
		$query =   "UPDATE students SET fullName='$fullName', 
					email='$email' 
					WHERE username='{$_SESSION['username']}'";
		if (!mysql_query($query)) {
			echo "query error:" .  mysql_error();
		} else {
			echo "Changes saved.";
		}
	}
} else {

	echo "Enter your current password to save any changes.";

}

/* OnBlur/OnFocus stuff

onblur="if (this.value == '') {this.value = 'Enter your password to save any changes';}"
onfocus="if (this.value == 'Enter your password to save any changes') {this.value = '';}"
					
*/


echo <<<EOT

	<form action="" method="POST" id="accountForm" style="width: 450px;">
	<br><br>Full name: <input type="text" name="fullName" value="$fullName" />
	<br><br>Email: <input type="text" name="email" value="$email" />
	<br><br>Current password: <input type="password" name="curPass" />
	<br><br>New password: <input type="password" name="newPass1" />
	<br><br>Confirm new password: <input type="password" name="newPass2" />
	<br><br><input type="submit" name="submit" value="Submit Changes" />
	</form>
 
 
EOT;




require 'footer.php';
?>