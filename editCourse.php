<?php
require 'header.php';

if (!isset($_GET['id'])) {
	echo "Completed course not defined. <a href='gpa.php'>Return to your GPA.</a>";
	require 'footer.php';
	die();
}

// Find all course information
$query = "SELECT * FROM completed_courses WHERE id=$_GET[id]";
if (!($result = mysql_query($query))) { // Query failed
	echo "Query failed: " . mysql_error();
	require 'footer.php';
	die();
}
$course = mysql_fetch_array($result);


// Form submitted
if (isset($_POST['submit'])) {
	// Update course
	$query = "UPDATE completed_courses SET course='$_POST[course]', grade='$_POST[grade]', credits=$_POST[credits], department='$_POST[department]', number=$_POST[number] WHERE id=$_GET[id]";
	if (!mysql_query($query)) { // Query failed
		echo "Query failed: " . mysql_error();
		require 'footer.php';
		die();
	} else { // Redirect
		?>
		<script language="JavaScript">
			window.location = "gpa.php";
		</script>
		<?php
	}
}
?>

<h3>Edit Completed Course</h3>

<form action="" method="post">
<br>Course name: <input type="text" name="course" value="<?php echo $course['course']; ?>">
<br>Department: <input type="text" name="department" value="<?php echo $course['department']; ?>">
<br>Course Number: <input type="text" name="number" value="<?php echo $course['number']; ?>">
<br>Credit hours: <input type="text" name="credits" value="<?php echo $course['credits']; ?>">
<br>Grade:
<select name="grade">
  <option value="4" <?php if($course['grade'] == 4) echo "selected='selected'"; ?>>A</option>
  <option value="3" <?php if($course['grade'] == 3) echo "selected='selected'"; ?>>B</option>
  <option value="2" <?php if($course['grade'] == 2) echo "selected='selected'"; ?>>C</option>
  <option value="1" <?php if($course['grade'] == 1) echo "selected='selected'"; ?>>D</option>
  <option value="0" <?php if($course['grade'] == 0) echo "selected='selected'"; ?>>F</option>
</select>
<br><br><input type="submit" name="submit" value="Save Changes">
</form>

<?php
require 'footer.php';
?>