<?php
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

		while ($row = mysql_fetch_array($results)) {
			$courses[] = $row;
		}
			
		echo "<ul style='padding: 0px'>";
		foreach ($courses as $course) {
				//echo $course["course"];
				//echo $course["credits"];
				$total=$total+($course["credits"]*$course["grade"]);
				$credits=$credits+$course["credits"];
		}
		
		$completedGPA=number_format($total/$credits,2);
		
			echo "Your GPA on past course work is " . $completedGPA;
	} else {
		echo "You have no completed courses saved.";
	}

}
?>




<br><br>
<a href="finishedCourses.php" class="mainButton">Add Completed Course</a>


<?php
require 'footer.php';
?>