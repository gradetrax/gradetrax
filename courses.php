<?php

$TITLE = "Courses";
require 'header.php';

if (isset($_POST['addCourse'])) {

	if (($_POST['course'] != "") && is_numeric($_POST['credits']) && ($_POST['dept'] != "") && is_numeric($_POST['number'])) { // Did they submit the right things?

		$exists = FALSE; // Tracks whether the new course is a duplicate
		
		$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "'";
		//echo $query . "<br>";
		if (!($results = mysql_query($query))) {
			echo "Query error";
		} else {
			while($row = mysql_fetch_array($results)) {
			//	echo $row['course'] . "<br>";
				if($row['course']==$_POST['course']) { // New course is a duplicate
					echo "Course name already exists<br><br>";
					$exists = TRUE;
				}
			}
		}
	
		if (!$exists) { // Insert new course
			$query = "INSERT INTO courses (username, course, credits, department, number) VALUES ('" . $_SESSION['username'] . "', '" . $_POST['course'] . "', " . $_POST['credits'] . ", '" . $_POST['dept'] . "', " . $_POST['number'] . ")";
			if (mysql_query($query)) {

			// Unsetting and redirecting fixes the form not showing up twice in a row
				unset($_POST['addCourse']);
				?>
				<script>
					window.location = "courses.php";
				</script>
				<?php
			}
		} else { // Display form with remembered values
			echo "Please enter a unique name, department, number, <em>and</em> the number of credits before submitting a course.<br><br>";

			if ($_POST['dept'] == "Example: PHYS")
				$_POST['dept'] = "";
			if ($_POST['number'] == "Example: 1710")
				$_POST['number'] = "";

			echo <<< EOT
				<form method="POST" action="" class="editBox" id="accountForm">
				<br>Course Title: <input type="text" name="course" value="$_POST[course]" autofocus />
				<br><br>Credits: <input type="text" name="credits" value="3" />
				<br><br>Department: <input type="text" name="dept" class="editBox" value="$_POST[dept]" />
				<br><br>Number: <input type="text" name="number" class="editBox" value="$_POST[number]"  />
				<br><br><input type="submit" name="addCourse" value="submit">
			</form>
EOT;

		}

	}
		
} else { // Display form with default values

	echo <<< EOT
	<form method="POST" action="" class="editBox" id="accountForm">
		<br>Course Title: <input type="text" name="course" autofocus/>
		<br><br>Credits: <input type="text" name="credits" value="3"/>
		<br><br>Department: <input type="text" name="dept" class="editBox" value="Example: PHYS" onblur="if (this.value == '') {this.value = 'Example: PHYS';}"
		onfocus="if (this.value == 'Example: PHYS') {this.value = '';}"/>
		<br><br>Number: <input type="text" name="number" class="editBox" value="Example: 1710" onblur="if (this.value == '') {this.value = 'Example: 1710';}"
		onfocus="if (this.value == 'Example: 1710') {this.value = '';}" />
		<br><br><input type="submit" name="addCourse" value="submit">
	</form>
EOT;

}

?>


<button id="newCourseButton" onclick="show();" class="mainButton">New Course</button>
<br><br>

<script>
function show() {
	document.getElementById('newCourseButton').style.display='none';
	var elements = document.getElementsByClassName('editBox');
	for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display='block';
    }
};
</script>


<?php


// Form to select a course
$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "' ORDER BY course";
//echo $query . "<br>";
if ($results = mysql_query($query)) {

	$courses = array();
	$row = mysql_fetch_array($results);
	if (is_array($row)) {

		$courses[] = $row;

		while ($row = mysql_fetch_array($results)) {
			$courses[] = $row;
		}
			
		echo "<ul style='padding: 0px'>";
		foreach ($courses as $course) {
			echo <<<EOT
			<li>
				<a href="viewCourse.php?id=$course[id]">$course[course]</a>
			</li>
EOT;
		}
		echo "</ul>";

	} else {
		echo "You have no courses saved.";
	}

}






require 'footer.php';
?>
