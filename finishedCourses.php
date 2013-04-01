<?php
$TITLE = "Finished Courses";
require 'header.php';


if (isset($_POST['submit'])) { // Form to create course has been submitted

	if (is_numeric($_POST['credits']) && is_numeric($_POST['number']) && ($_POST['name'] != "") && ($_POST['grade'] != "")) { // Form values were appropriately filled
		// Inserts new completed course
		$query = "INSERT INTO completed_courses (username, department, course, number, credits, grade) VALUES ('" . $_SESSION['username'] . "', '" . $_POST['dept'] . "', '" . $_POST['name'] . "', " . $_POST['number'] . ", '" . $_POST['credits'] . "', " . $_POST['grade'] . ")";
		
		if (!mysql_query($query)) { // Query failed
			die("Query failed: " . mysql_error());
		} else { // Query succeeded
			// Redirect
			?>
			<script language="JavaScript">
					window.location = "gpa.php";
			</script>
			<?php		
		}
		
	} else { // Form values were not appropriately filled
		echo "<p class='warning'>Please fill all fields.</p>";
	}
}

?>


<p>

</p>

<form method="post" action="">
<br>Course name: <input type="text" name="name"/>
<br>Department: <input type="text" name="dept" />
<br>Course number: <input type="text" name="number" />
<br>Credit hours: <input type="text" name="credits" />
<br>Grade:
<select name="grade">
  <option value="4">A</option>
  <option value="3">B</option>
  <option value="2">C</option>
  <option value="1">D</option>
  <option value="0">F</option>
</select>
<br><br>
<input type="submit" name="submit" value="Submit">
</form>




			
		
		


<?php
require 'footer.php';
?>