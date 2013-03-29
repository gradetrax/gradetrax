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

		$courses[] = $row;
echo "<table border='1'>";
	echo "<tr>";
		echo "<th>Course Name</th>";
		echo "<th>Credits</th>";
		echo "<th>Grade</th>";
		echo "</tr>";
	
		while ($row = mysql_fetch_array($results)) {
			$courses[] = $row;
		}
			
		echo "<ul style='padding: 0px'>";
		foreach ($courses as $course) {
		
		
			
		
		echo "<tr>";
		echo "<td>".$course['course']."</td>";
		echo "<td>".$course['credits']."</td>";
		echo "<td>".$course['grade']."</td>";
		echo "</tr>";
	
			

//			echo "Course : " . $course["course"] . " Credits : " . $course["credits"] . " Grade : " . $course["grade"]. "<br>";
				$total=$total+($course["credits"]*$course["grade"]);
				$credits=$credits+$course["credits"];
		}
			echo "</tr>";
		echo "</table>";
		$completedGPA=number_format($total/$credits,3);
		
			echo "<br><br><br>Your GPA on past course work is " . $completedGPA;
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