<?php
$TITLE = "View Courses";
require 'header.php';

if (isset($_POST['submit'])) { // Form to edit course has been submitted
	// Updates a row
	$query = "UPDATE courses SET 
			credits=" . $_POST['credits'] . 
			", instructor='" . $_POST['instructor'] . 
			"', department='" . $_POST['dept'] . 
			"', number=" . $_POST['number'] . 
			", location='" . $_POST['location'] . 
			"', course='" . $_POST['course'] . 
			"' WHERE id=" . $_GET['id'];
	//echo $query;
	if (!mysql_query($query)) { // Query failed
		echo "Query error:" . mysql_error();
	}
}

?>


<script>
// Shows or hides elements where class='type'
function show() {
	document.getElementById('origData').style.display='none';
	var elements = document.getElementsByClassName('editBox');
	for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display='block';
    }
};</script>


<?php

// Returns this course
$query = "SELECT * FROM courses WHERE id={$_GET['id']}";

if (!($result = mysql_query($query))) { // Query failed
	echo "Error with query $query";
} else {
	$row = mysql_fetch_array($result); // Store course information
	if ($_SESSION['username'] != $row['username']) { // User did not create the course - don't let them edit
		echo "You do not have permission to access this course!";
	} else {
		
		if ($row['grade'] < 0) // Grade not calculated yet
			$grade = "N/A";
		else // Grade calculated and stored
			$grade = $row['grade'];


		// Print information and form to edit
		echo <<<EOT
			<h3>$row[department] $row[number]: $row[course]</h3>
			
			<div id="origData">
			Current grade: $grade
			<br>Credit hours: $row[credits]
			<br>Instructor: $row[instructor]
			<br>Location: $row[location]
			<br>
			<br><a href="grading.php?id=$_GET[id]" class="mainButton">Grades</a>
			<button onClick="show()">Edit</button>
			</div>
			
			<form action="" method="POST" class="editBox">
			Course title<input type="text" name="course" class="editBox" value="$row[course]" />
			<br>Credit hours<input type="text" name="credits" class="editBox" value="$row[credits]" />
			<br>Instructor<input type="text" name="instructor" class="editBox" value="$row[instructor]"/>
			<br>
			<span class="floatSpan">
				Department
				<br><input type="text" name="dept" class="editBox smallBox" value="$row[department]" />
			</span>
			<span class="floatSpan">
				Number
				<br><input type="text" name="number" class="editBox smallBox" value="$row[number]" />
			</span>
			<br><br><br><br>
			Location
			<br><input type="text" name="location" class="editBox" value="$row[location]" />
			<br><input type="submit" class="editBox" name="submit" value="Submit Changes" />
			</form>
EOT;

		echo '<br><br><a href="removeCourse.php?id=' . $_GET['id'] . '" class="mainButton">Delete Course</a>';
	}

	echo '<br><a href="courses.php" class="mainButton">Return to All Courses</a>';

}


require 'footer.php';
?>