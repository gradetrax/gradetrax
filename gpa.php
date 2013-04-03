<?php
$TITLE = "GPA";
require 'header.php';
?>

<h3>GPA</h3>

<?php
$total=0;
$credits=0;
$query = "SELECT * FROM completed_courses WHERE username='" . $_SESSION['username']."'" ;
if ($results = mysql_query($query)) {

	$courses = array();
	$row = mysql_fetch_array($results);
	if (is_array($row)) {

		// Add first course
		$courses[] = $row;
		$total=$total+($row["credits"]*$row["grade"]);
		$credits=$credits+$row["credits"];

		while ($row = mysql_fetch_array($results)) {
			$courses[] = $row; // Add the rest of the courses
			
			// Sum hours and credits:
			
			// echo "Course : " . $course["course"] . " Credits : " . $course["credits"] . " Grade : " . $course["grade"]. "<br>";
			$total=$total+($row["credits"]*$row["grade"]);
			$credits=$credits+$row["credits"];
		}

		// Calculate and print GPA
		$completedGPA = number_format($total/$credits,3);
		// echo "Your GPA on past coursework is " . $completedGPA;
		
		$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "' AND grade>-1";
		if ($results = mysql_query($query)) {
			$row = mysql_fetch_array($results);
			if (is_array($row)) {
				// Calculate grade points
				if ($row['grade'] > 90) $grade = 4;
				elseif ($row['grade'] > 80) $grade = 3;
				elseif ($row['grade'] > 70) $grade = 2;
				elseif ($row['grade'] > 60) $grade = 1;
				else $grade = 0;

				// Overall GPA calculations
				$total += $row['credits'] * $grade;
				$credits += $row['credits'];
				
				// Projected semester GPA calculations
				$ipTotal = $row['credits'] * $grade;
				$ipCredits = $row['credits'];
				
				// Loop through each IP course
				while ($row = mysql_fetch_array($results)) {
					// Calculate grade points
					if ($row['grade'] > 90) $grade = 4;
					elseif ($row['grade'] > 80) $grade = 3;
					elseif ($row['grade'] > 70) $grade = 2;
					elseif ($row['grade'] > 60) $grade = 1;
					else $grade = 0;

					// Overall GPA calculations
					$total += $row['credits'] * $grade;
					$credits += $row['credits'];
					
					// Projected semester GPA calculations
					$ipTotal += $row['credits'] * $grade;
					$ipCredits += $row['credits'];
				}
				
				$semesterGPA = number_format($ipTotal / $ipCredits, 3);
				$totalGPA = number_format($total / $credits, 3);
		
			// Display 3 calculated GPAs
			echo "<span id='completedGPA'>Completed Coursework<br>" . $completedGPA . "</span>";
			echo "<span id='semesterGPA'>Projected Semester<br>" . $semesterGPA . "</span>";
			echo "<span id='projectedGPA'>Projected Total<br>" . $totalGPA . "</span>";
			
			echo "<div class='clearfloat'></div>";
			// /Display
		}
	} else {
		echo "IP courses query error: " . mysql_error();
	}
		
		echo "<br><br><br>";

		
		// Start table
		echo "<table border='1' cellspacing='0' cellpadding='5'>";
		echo "<tr>";
		echo "<th>Course Name</th>";
		echo "<th>Credits</th>";
		echo "<th>Grade</th>";
		echo "</tr>";
		// echo "<ul style='padding: 0px'>";
		// Print each course as a row
		foreach ($courses as $course) {
			echo "<tr>";
			echo "<td>".$course['course']."</td>";
			echo "<td>".$course['credits']."</td>";
			if($course['grade']==4)
				$letterGrade='A';
			else if($course['grade']==3)
				$letterGrade='B';
			else if($course['grade']==2)
				$letterGrade='C';
			else if($course['grade']==1)
				$letterGrade='D';
			else if($course['grade']==0)
				$letterGrade='F';
			echo "<td>".$letterGrade."</td>";
			echo "</tr>";
		}
		echo "</table>";

	} else {
		echo "You have no completed courses saved.";
	}

}
?>




<br><br><br><br>
<a href="finishedCourses.php" class="mainButton">Add Completed Course</a>
<br><br><br><br>


<?php
require 'footer.php';
?>