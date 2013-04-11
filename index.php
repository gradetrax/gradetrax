<?php

$TITLE = "Home";
require 'header.php';


echo "<br>
	  <center>
	  <table>";
if (!empty($_SESSION['username'])) {
?>

		<tr>
		<td><a href="courses.php" class="mainButton">Courses</a></td>
		<td><a href="grades.php" class="mainButton">Assignments</a></td>
		<td><a href="gpa.php" class="mainButton">GPA</a></td>
		</tr><tr>
		<td></td>
		<td><center><a href="classmates.php" class="mainButton">Classmates</a></center></td>
		</tr>
		
<?php
} else {

	echo "You should log in";

}

echo "</table>
	  </center>";



require 'footer.php';
?>