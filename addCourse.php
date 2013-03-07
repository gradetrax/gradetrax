<?php
$TITLE = "Add a new course";
require 'header.php';
?>


<?php


if (isset($_POST['addCourse'])) {
	if (($_POST['course'] != "") && is_numeric($_POST['credits'])) {

	
		$query = "INSERT INTO courses (username, course, credits, grade) VALUES ('" . $_SESSION['username'] . "', '" . $_POST['course'] . "', '" . $_POST['credits'] . "', -1.0)";
		if ($results = mysql_query($query)) {
			echo $_POST['course'] . " added<br><br>";
			?>
			<script type='text/javascript'>
				opener.location.reload(true);
				self.close();
			</script>
			<?php			
		} else {
			echo "query error: " . $query . "<br><br>";
		}
	} else {
		echo "Please enter both a name and the number of credits<br><br>";
	}
}

?>

	Add a course:
	<form method="POST" action="addCourse.php">
		Title: <input type="text" name="course" autofocus/>
		<br>Credits: <input type="text" name="credits" />
		<br><input type="submit" name="addCourse" value="submit">
	</form>

<?php

require 'footer.php';
?>