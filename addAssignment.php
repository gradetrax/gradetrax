<?php
require 'header.php';


if (isset($_POST['submit'])) {

	// Prints out POST array for debugging
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre><br><br>";

	if (($_POST['name'] != "")) { // The last form was submitted - add asignment
	
		// Insert new assignment
		$query = "INSERT INTO assignments (name, courseID, grade, username, categoryID) VALUES ('" . $_POST['name'] . "', "  . $_POST['courseID'] . ", -1, '" . $_SESSION['username'] . "', " . $_POST['categoryID'] . ")";
		
		if (!mysql_query($query)) { // Query failed
			die("Query failed: " . mysql_error());
		} else { // Query worked - redirect
			unset($_POST);
			?>
			 <script language="JavaScript">
				window.location = "grades.php";
			 </script>			
			<?php
		}
		
	} else {
		echo "<p class='warning'>Please fill all fields.</p>";
	}
} else if (isset($_POST['submit1'])) { // The first form was submitted - display second

	// Prints out POST array for debugging
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre><br><br>";

?>
	<!-- Form for choosing category -->
	<form method="post" action="">
	<br>Category:
	<select name="categoryID"> <!-- Dropdown to select category for new assignment -->
	<?php
		// Returns all categories for the selected course
		$query = "SELECT * FROM categories WHERE courseID='" . $_POST['courseID'] . "' ORDER BY weight ASC";
		if (!($result = mysql_query($query))) { // Query failed
			die("Query error: " . mysql_error());
		}
		while ($row = mysql_fetch_array($result)) { // Print an option in the dropdown for each category
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
} else { // Nothing has been submitted - display first form

?>


<p>

</p>

<form method="post" action="">
<br>Name: <input type="text" name="name"/>
<br>Class:
	<select name="courseID">
	<?php
		// Returns all courses for this user
		$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "'";
		if (!($result = mysql_query($query))) { // Query failed
			die("Query error: " . mysql_error());
		}
		while ($row = mysql_fetch_array($result)) { // Print an option in the dropdown for each class
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