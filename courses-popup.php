<?php

$TITLE = "Courses";
require 'header.php';

echo <<< EOT
	<a href="addCourse.php" onclick="return popitup('addCourse.php')" class="mainButton">New Course</a>
	<br><br>
EOT;

?>

<!-- script for popup -->
<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	newwindow=window.open(url,'name','height=400,width=550');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
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
					<a href="viewcourse.php?id=$course[id]">$course[course]</a>
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