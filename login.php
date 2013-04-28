<?php

$TITLE = "Login";
require 'header.php';

$email =false;
if (isset($_POST['submit'])) {
$query = "SELECT * FROM students WHERE username='" . $_POST['name'] . "'";
	for ($i = 0; $i<strlen($_POST['name']); $i++)  { 
      $character = substr($_POST['name'], $i,1);     
	  if($character=='@')
	  {
		$query = "SELECT * FROM students WHERE email='" . $_POST['name'] . "'";
		$email=true;
		}
}
	
	$results = mysql_query($query);

	if ($row = mysql_fetch_array($results)) {
		if ($email == true)
			$query2 = "SELECT AES_DECRYPT(secPass,'" . $eKey . "') FROM students WHERE email='" . $_POST['name'] . "'";
		else
			$query2 = "SELECT AES_DECRYPT(secPass,'" . $eKey . "') FROM students WHERE username='" . $_POST['name'] . "'";
		$eQuery = mysql_query($query2);
		$ePass = mysql_fetch_array($eQuery);
		if ($_POST['pass'] == $ePass["AES_DECRYPT(secPass,'" . $eKey . "')"]) {
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
		if($email)
			echo "Email address does not exist.<br>";
		else
		echo "Username does not exist.<br>";
	}

}

?>

<h3>Log In</h3>

<form id="form" action="" method="POST">
Username or email: <input type="text" name="name" /autofocus>
<br>Password: <input type="password" name="pass">
<br><input type="submit" name="submit" value="submit">

<?php
require 'footer.php';
?>