<?php
$TITLE = "GPA";
require 'header.php';
?>

<h3>GPA</h3>


<div class="clearfloat"></div>


<?php
$total=0;
$credits=0;
$totalGPA=-1;
$projectedGPA=-1;
$complete=false;
$query = "SELECT * FROM completed_courses WHERE username='" . $_SESSION['username']."' ORDER BY department, number ASC";
if ($results = mysql_query($query)) {

	$courses = array();
	$row = mysql_fetch_array($results);
	if (is_array($row)) {
		$complete=true;
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
		
			current:
		$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "' AND grade>-1";
		if ($results = mysql_query($query)) {
			$IPcourses = array();
			$row = mysql_fetch_array($results);
			if (is_array($row)) {
				$IPcourses[] = $row;
				
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
					$IPcourses[] = $row;
					
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
			// echo "<span id='completedGPA'>Completed Coursework<br>" . $completedGPA . "</span>";
			// echo "<span id='semesterGPA'>Projected Semester<br>" . $semesterGPA . "</span>";
			// echo "<span id='projectedGPA'>Projected Total<br>" . $totalGPA . "</span>";
			
			// echo "<div class='clearfloat'></div>";
			// /Display
			
			echo "<br><br>";
			if($complete!=false)
			{
			echo "<h3 id='projectedGPA'>Projected Total: $totalGPA</h3>";
			if ($totalGPA >= 2.00)
			    echo "Good Standing<br>";
			else if($totalGPA>-1)
				echo "Academic Probation<br>";
				}
				
				
						
			
			echo "<br><br>";
			
			// Start table
			echo "<span style='float: left; margin-right: 80px'>";
			?>

<a href="courses.php" class="mainButton">Add Incomplete Course</a>
		
<?php
			echo "<h3 id='semesterGPA'>Current Courses: $semesterGPA</h3>";
			if ($semesterGPA==4.00)
			    echo "Eligible for recognition on the President’s List";
			else if ($semesterGPA>=3.50)
			    echo "Eligible for recognition on the Dean’s List";
			else if ($semesterGPA>=2)
			    echo "Good Standing";
			else if ($semesterGPA!=-1)
			    echo "Academic Probation";
			
		
				
			echo "<table border='1' cellspacing='0' cellpadding='5'>";
			echo "<tr>";
			echo "<th>Course Name</th>";
			echo "<th>Credits</th>";
			echo "<th>Grade</th>";
			echo "</tr>";
			// echo "<ul style='padding: 0px'>";
			// Print each course as a row
			foreach ($IPcourses as $course) {
				echo "<tr>";
				echo "<td><a href='grading.php?id=" . $course['id'] . "'>".$course['course']."</a></td>";
				echo "<td>".$course['credits']."</td>";
				echo "<td>".$course['grade']."</td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "</span>";
		} else {
			echo "<span style='float: left; margin-right: 80px'>";
			echo "You have no courses in progress.<br>";
						echo "<span style='float: left; margin-right: 80px'>";
			?>

<a href="courses.php" class="mainButton">Add Incomplete Course</a>
		
<?php
			
			echo "</span>";
			
		}
	} else {
		echo "IP courses query error: " . mysql_error();
	}

	if($complete==true){
		// Start table
	
		echo "<span style='float: left'>";
			?>

<a href="finishedCourses.php" class="mainButton">Add Completed Course</a>
		
<?php
		
		echo "<h3 id='completedGPA'>Completed Courses: $completedGPA</h3>";
		if ($completedGPA>=2.00)
			    echo "Good Standing";
			else if($completedGPA!=-1)
			  echo "Academic Probation";
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
			echo "<td><a href='editCourse.php?id=" . $course['id'] . "'>".$course['course']."</a></td>";
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
		echo "</span>";
}
	} else {
		?>
		You have no completed courses saved.
			<br>
		
		<a href="finishedCourses.php" class="mainButton">Add Completed Course</a>
		<?php
		echo"<br><br>";
		echo "</span>";
		echo"<div class='clearfloat'></div>";
		goto current;
	}

}
?>

<div class='clearfloat'></div>

<br><br><br><br>


<?php
require 'footer.php';
?>