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
	if ($_SESSION['username'] != $row['username']) { // User did not create the course - don't let them edit it
		echo "You do not have permission to access this course!";
		echo '<br><br><a href="courses.php" class="mainButton">Return to All Courses</a>';
	} else {
		
		if ($row['grade'] < 0) // Grade not calculated yet
			$grade = "N/A";
		else // Grade calculated and stored
			$grade = $row['grade'];


		// Print information and form to edit
		
			echo "<h3>$row[department] $row[number]: $row[course]</h3>";
			
			echo "<div style='float: left; padding-right:100px'>";
	
	$query = "SELECT grade from courses WHERE department='" . $row['department'] . "' AND number=" . $row['number'];
	if (!($result = mysql_query($query))) {
		echo "Query error: " . mysql_error();
	} else {
		$counter = 0;
		$gradeSum = 0;
		$grades = array();
		while ($row = mysql_fetch_array($result)) {
			if ($row['grade'] > 0) { // Grade calculated and stored
				$grades[] = $row['grade'];
				$counter ++;
				$gradeSum += $row['grade'];
			}
		}
		
		if (!$counter) { // No students enrolled in this course have calculated grades
			goto noGrades;
		}


		sort($grades);

		// Debugging print
		// echo "<pre>";
		// print_r($grades);
		// echo "</pre><br>";
		if($counter>1)
		  echo "Data from " . $counter . " students:<br>";
		else
		  echo "Data from 1 student:<br>";
		// Mean
		$mean = $gradeSum / $counter;
		echo "Mean: $mean<br>";
		
		// Median
		if ($counter % 2) {
			// Take floor of (size of array / 2)
			$median = floor($grades[$counter/2]);
		} else {
			$median = ($grades[$counter/2 - 1] + $grades[$counter/2]) / 2;
		}
		echo "Median: $median<br>";
		
		// Range
		$sml = $grades[0]; 
		rsort($grades); 
		$lrg = $grades[0];
		$range = $lrg - $sml;
		echo "Range: $range<br>";

		
		// Calculate letter grade breakdown
		$letters = array(
						"A" => 0,
						"B" => 0,
						"C" => 0,
						"D" => 0,
						"F" => 0
						);
						
		foreach($grades as $grade) {
			switch($grade) {
				case ($grade >= 90): $letters["A"]++; break;
				case ($grade >= 80): $letters["B"]++; break;
				case ($grade >= 70): $letters["C"]++; break;
				case ($grade >= 60): $letters["D"]++; break;
				default: $letters["F"]++; break;
			}
		}
		
		
		// GRAPH STUFF YEAH
		
		echo <<< EOT
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = google.visualization.arrayToDataTable([
			  ['Grade', 'Students'],
			  ['A',		$letters[A]],
			  ['B',		$letters[B]],
			  ['C',		$letters[C]],
			  ['D',		$letters[D]],
			  ['F',		$letters[F]]
			]);

			var options = {
			  title: 'Total Grades'
			};

			var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		  }
		</script>
		<div id="chart_div" style="width: 400px; height: 400px;"></div>
EOT;
	
	}
	
	noGrades:
	
	echo "</div>";
	echo "<div style='float: left;'>";
	
	$query = "SELECT * FROM courses WHERE id={$_GET['id']}";
	if (!($result = mysql_query($query))) { // Query failed
		echo "Error with query $query";
		die();
	}
	$row = mysql_fetch_array($result); // Store course information
			
		echo <<<EOT
			
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
		echo '<br><a href="completeCourse.php?id=' . $_GET['id'] . '" class="mainButton">Mark Complete</a>';
	

	echo '<br><br><a href="courses.php" class="mainButton">Return to All Courses</a>';

	echo "</div>";

	
	}
	
}
echo "</div>";

require 'footer.php';
?>