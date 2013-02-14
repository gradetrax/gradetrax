<?php

$TITLE = "Login";
require 'header.php';


if (isset($_POST['submit'])) {

	$query = "SELECT * FROM students WHERE username='" . $_POST['name'] . "'";
	$results = mysql_query($query);

	while ($row = mysql_fetch_array($results)) {
		if ($row['password'] == $_POST['pass']) {
			$_SESSION['username'] = $_POST['name'];
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
		} else {
			echo "Wrong password. <a href='login.php'>Please try again</a>.";
		}
	}

} else {

?>

<h3>Log In</h3>

<form id="form" action="" method="POST">
Username: <input type="text" name="name">
<br>Password: <input type="password" name="pass">
<br><input type="submit" name="submit" value="submit">

<?php
}

require 'footer.php';
?>