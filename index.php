<?php

$TITLE = "Home";
require 'header.php';


echo "<br>
	  <center>";
if (!empty($_SESSION['username'])) {
?>

		<a href="courses.php" class="mainButton">Courses</a>
		<a href="grades.php" class="mainButton">Assignments</a>
		<a href="gpa.php" class="mainButton">GPA</a>

<?php
} else {

	echo "You should log in";

}

echo "</center>";



require 'footer.php';
?>