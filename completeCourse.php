<?php
$TITLE = "Mark a Course Complete";
require 'header.php';

// Make sure an ID was passed
if (!isset($_GET['id'])) {
	echo "Course ID not given.";
	require 'footer.php';
	die();
}

if (isset($_GET['complete'])) {
	
	// Returns all information about the course
	$query = "SELECT * FROM courses WHERE id=" . $_GET['id'];
	if (!($result = mysql_query($query))) { // Query failed
		die("Select query error: " . mysql_error());
	}
	
	$course = mysql_fetch_array($result); // Store course information

	// Calculate grade points
	if ($course['grade'] > 90) $grade = 4;
	elseif ($course['grade'] > 80) $grade = 3;
	elseif ($course['grade'] > 70) $grade = 2;
	elseif ($course['grade'] > 60) $grade = 1;
	else $grade = 0;

	// Inserts course into completed_courses table
	$query = "INSERT INTO completed_courses (username, course, credits, department, number, grade) VALUES ('" . $_SESSION['username'] . "', '" . $course['course'] . "', " . $course['credits'] . ", '" . $course['department'] . "', " . $course['number'] . ", " . $grade . ")";
	if (!mysql_query($query)) { // Query failed
		die("Insert query error: " . mysql_error());
	}
	
	$query = "DELETE FROM courses WHERE id=" . $_GET['id'];
	if (!mysql_query($query)) { // Query failed
		die("Delete query error: " . mysql_error());
	}

	?>
	<script language="JavaScript">
		window.location = "courses.php";
	</script>
	<?php
	
}
?>

Are you sure you want to mark this course completed?
<br>(Action cannot be undone.)
<br><br>
<a href="completeCourse.php?complete=1&id=<?php echo $_GET['id']; ?>" class="mainButton">Mark Complete</a>
<a href="viewCourse.php?id=<?php echo $_GET['id']; ?>" class="mainButton">Cancel</a>


<?php
require 'footer.php';
?>