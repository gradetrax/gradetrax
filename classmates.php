<?php
$TITLE = "Classmates";
require 'header.php';
?>

<h3>Classmates</h3>
<a href='newClassmate.php' class='mainButton'>Send Request</a>
<br><br><br><br>


<div>
<h4>Requests</h4>


<?php

// Returns all listed classmates
$query = "SELECT * FROM requests WHERE friendname='" . $_SESSION['username'] . "'";
if (!($result = mysql_query($query))) { // Query failed
	echo "Query error: " . mysql_error();
	require 'footer.php';
	die();
}

echo "<table cellpadding='5px'>";

// Loop for each friend
while ($friend = mysql_fetch_array($result)) {
	echo "<tr>";
	
	// Print friend's username
	echo "<td style='padding-right: 20px;'><a href='request.php?id=$friend[id]' class='listItem'>$friend[username]</a></td>";
	// Separate shared courses by ; and store in array
	$courses = explode(";", $friend['courses']);

	// Print courses
	foreach($courses as $course) {
		// Blank courses show up for some reason
		if ($course == '')
			break;
	
		// Return course name
		$id = array();
		preg_match("/([a-zA-Z]+)(\\d+)/", $course, $id);
		$query = "SELECT course FROM courses WHERE department='" . $id[1] . "' AND number=" . $id[2];
		if (!($course = mysql_query($query))) { // Query failed
			echo "Query error: " . mysql_error();
			require 'footer.php';
			die();
		}
		// Print course name
		$course = mysql_fetch_array($course);
		echo "<td style='padding-right: 20px;'>$course[course]</td>";
	}

	echo "</tr>";
}

echo "</table>";
?>

</div>

<br><br><br><br>

<div>
<h4>Classmates</h4>


<?php

// Returns all listed classmates
$query = "SELECT * FROM classmates WHERE username='" . $_SESSION['username'] . "'";
if (!($result = mysql_query($query))) { // Query failed
	echo "Query error: " . mysql_error();
	require 'footer.php';
	die();
}

echo "<table cellpadding='5px'>";

// Loop for each friend
while ($friend = mysql_fetch_array($result)) {
	echo "<tr>";
	
	// Print friend's username
	echo "<td style='padding-right: 20px;'><a href='' class='listItem'>$friend[friendname]</a></td>";
	// Separate shared courses by ; and store in array
	$courses = explode(";", $friend['courses']);

	// Print courses
	foreach($courses as $course) {
		// Blank courses show up for some reason
		if ($course == '')
			break;

		// Return course name
		$id=array();
		preg_match("/([a-zA-Z]+)(\\d+)/", $course, $id);
		$query = "SELECT * FROM courses WHERE department='" . $id[1] . "' AND number=" . $id[2];
		if (!($course = mysql_query($query))) { // Query failed
			echo "Query error: " . mysql_error();
			require 'footer.php';
			die();
		}
		// Print course name
		$course = mysql_fetch_array($course);
		echo "<td style='padding-right: 20px;'><a href='compare.php?department=$id[1]&number=$id[2]&name=$friend[friendname]'>$course[course]</a></td>";
	}

	echo "</tr>";
}

echo "</table>";
?>
</div>




<?php
require 'footer.php';
?>