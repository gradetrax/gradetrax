<?php

$TITLE = "Courses";
require 'header.php';

if (isset($_POST['addCourse'])) {

	if (($_POST['course'] != "") && is_numeric($_POST['credits']) && ($_POST['dept'] != "") && is_numeric($_POST['number'])) {

		$query = "INSERT INTO courses (username, course, credits, department, number) VALUES ('" . $_SESSION['username'] . "', '" . $_POST['course'] . "', " . $_POST['credits'] . ", '" . $_POST['dept'] . "', " . $_POST['number'] . ")";
		if (!mysql_query($query)) {
			echo "query error: " . mysql_error() . "<br><br>";
			echo $query . "<br>";
		} else {
			// Unsetting and redirecting fixes the form not showing up twice in a row
			unset($_POST['addCourse']);
			?>
			<script>
				window.location = "courses.php";
			</script>
			<?php
		}
	} else {
		echo "Please enter a name, department, number, <em>and</em> the number of credits before submitting a course.<br><br>";

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

} else {

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

if (isset($_POST['id'])) {

	$_SESSION['currentCourse'] = $_POST['id'];

	// Run queries
	$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "' AND id=" . $_SESSION['currentCourse'] . " ORDER BY course";
	$query1 = "SELECT * FROM categories WHERE courseID=" . $_SESSION['currentCourse'];
	$results = mysql_query($query);
	$cats = mysql_query($query1);
	
	// Put categories into an array
	$categories = array();
	while ($cat = mysql_fetch_array($cats)) {
		$categories[$cat['name']] = array(
				'id' => $cat['id'],
//				'courseID' => $cat['courseID'],
//				'name' => $cat['name'],
				'weight' => $cat['weight'],
				'assignments' => $cat['assignments']
				);
	}

//	echo "<pre>"; print_r($categories); echo "</pre>";

	// Display course info
	$course = mysql_fetch_array($results);
	echo "<h3>" . $course['course'] . "</h3>";
	echo "<br>";
	echo $course['credits'];
	if ($course['credits'] > 1)
		echo " credits";
	else
		echo " credit";

	echo "<br><br>";
	echo "<h3>Grades</h3>";
	
	// Create array to store category averages
	

	foreach ($categories as $key => $cat) {
		echo "<br><br><strong>$key: " . $cat['weight'] . "%</strong>";
		$query = "SELECT * FROM assignments WHERE categoryID=" . $cat['id'];
		if ($result = mysql_query($query)) {
			$average = 0;
			$counter = 0;
			while ($assignment = mysql_fetch_array($result)) {
				echo "<br>" . $assignment['name'] . ": " . round($assignment['grade'], 2);
				$average += $assignment['grade'];
				$counter += 1;
			}
			echo "<br><em>Average: </em>";
			echo round($average / $counter, 2);
		} else {
			echo "Query error: $query";
		}
	}
	

	// Display options
	echo "<br><br><br>";
	echo '<a href="addAssignment.php">Add a new assignment</a>';
	echo "<br>";
	echo '<a href="addCategory.php">Add a new category</a>';

	echo "<br><br>";
	echo '<a href="removeCourse.php?id=' . $_POST['id'] . '">Remove this course</a>';
	echo "<br>";
	echo '<a href="courses.php">Select another course</a>';


} else {

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


}



require 'footer.php';
?>
