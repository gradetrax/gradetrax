<?php
require 'header.php';


if (isset($_POST['submit'])) {

	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre><br><br>";

	if (($_POST['name'] != "")) {
	
		$query = "INSERT INTO assignments (name, courseID, grade, username) VALUES ('" . $_POST['name'] . "', "  . $_POST['courseID'] . ", -1, '" . $_SESSION['username'] . "')";
		
		if (!mysql_query($query)) {
			die("Query failed: " . mysql_error());
		} else {
			?>
			<script language="JavaScript">
				window.location = "grades.php";
			</script>			
			<?php
		}
		
	} else {
		echo "<p class='warning'>Please fill all fields.</p>";
	}
} else if (isset($_POST['submit1'])) {

	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre><br><br>";

?>
	<form method="post" action="">
<br>Category:
	<select name="courseID">
	<?php

		$query = "SELECT * FROM categories WHERE courseID='" . $_POST['courseID'] . "' ORDER BY weight ASC";
		if (!($result = mysql_query($query))) {
			die("Query error: " . mysql_error());
		}
		while ($row = mysql_fetch_array($result)) {
			echo <<<EOT
			<option value="$row[id]">$row[weight]%: $row[name]</option>
EOT;
		}
	?>  
	</select>
<input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
<input type="hidden" name="courseID" value="<?php echo $_POST['courseID']; ?>">


<br><br>
<input type="submit" name="submit" value="Submit">
</form>



<?php
} else {

?>


<p>

</p>

<form method="post" action="">
<br>Name: <input type="text" name="name"/>
<br>Class:
	<select name="courseID">
	<?php

		$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "'";
		if (!($result = mysql_query($query))) {
			die("Query error: " . mysql_error());
		}
		while ($row = mysql_fetch_array($result)) {
			echo <<<EOT
			<option value="$row[id]">$row[course]</option>
EOT;
		}
	?>  
	</select>
<br><br>
<input type="submit" name="submit1" value="Submit">
</form>


<?php

}

require 'footer.php';
?>