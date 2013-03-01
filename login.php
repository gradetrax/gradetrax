<?php

$TITLE = "Login";
require 'header.php';


if (isset($_POST['submit'])) {

	$query = "SELECT * FROM students WHERE username='" . $_POST['name'] . "'";
	$results = mysql_query($query);

	if ($row = mysql_fetch_array($results)) {
		if ($row['password'] == $_POST['pass']) {
			$_SESSION['username'] = $row['username'];
			?>
			
			<script language="JavaScript">
					window.location = "index.php";
			</script>
					
			<?php
		} else {
			echo "Invalid password.<br>";
		}
	} else {
		echo "Username does not exist.<br>";
	}

}

?>

<h3>Log In</h3>

<form id="form" action="" method="POST">
Username: <input type="text" name="name" /autofocus>
<br>Password: <input type="password" name="pass">
<br><input type="submit" name="submit" value="submit">

<?php
require 'footer.php';
?>