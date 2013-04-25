<?php
require 'header.php';

// Get request in question
$query = "SELECT * FROM requests WHERE id=" . $_GET['id'];
if (!($result = mysql_query($query))) { // Query failed
	die("Query error: " . mysql_error);
}
$request = mysql_fetch_array($result); // Store result in array


if (isset($_POST['reject'])) { // Remove request from database
	$query = "DELETE FROM requests WHERE id=" . $_GET['id'];
	if (!mysql_query($query)) { // Query error
		die("Delete error: " . mysql_error());
	}
	?>
	<script language="JavaScript">
		window.location = "classmates.php";
	</script>
	<?php
	
} else if (isset($_POST['accept'])) { // Remove from requests, insert to classmates
	
	// Delete from requests
	$query = "DELETE FROM requests WHERE id=" . $_GET['id'];
	if (!mysql_query($query)) { // Query error
		die("Delete error: " . mysql_error());
	}
	
	
	// Append the courses if they're already friends
	$query = "SELECT * FROM classmates WHERE friendname='" . $request['friendname'] . "'";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result)) { // Entered username is already a classmate
	
	// echo "<pre>";
	// print_r($row);
	// echo "</pre>";
	
		// Compare each one and concatenate to new string
		$newCourses = array();
		$combinedCourses = $row['courses'];
		$courses = explode(";", $row['courses']);
		$newCourses = explode(";", $request['courses']);
		
		foreach($newCourses as $newCourse) {
			if ($newCourse == "")
					break;
			$new = 1;
			foreach($courses as $course) {
				if ($course == "")
					break;
				// echo $newCourse . " vs " . $course . "<br>";
				if ($course == $newCourse) {
					$new = 0;
				}
			}
			if ($new) {
				$combinedCourses .= $newCourse . ";";
			}
		}
		
		echo $combinedCourses . "<br>";
	
		// Update the friendship
		$query = "UPDATE classmates SET courses='" . $combinedCourses . "' WHERE friendname='$request[username]' AND username='$_SESSION[username]'";
		echo $query . "<br>";
		if(!mysql_query($query)) { // Query failed
			echo "Query 1 error: " . mysql_error();
			require 'footer.php';
			die();
		}
		
		// Update the friendship again
		$query = "UPDATE classmates SET courses='" . $combinedCourses . "' WHERE username='$request[username]' AND friendname='$_SESSION[username]'";
		echo $query . "<br>";
		if(!mysql_query($query)) { // Query failed
			echo "Query 2 error: " . mysql_error();
			require 'footer.php';
			die();
		}
		
		// die("<br>Success?");
		
		?>
		<script language="JavaScript">
			window.location = "classmates.php";
		</script>
		<?php
	
	
	}
	
	
	// Insert to classmates if it's a new friend
	$query = "INSERT INTO classmates (username, friendname, courses) VALUES ('" . $request['username'] . "', '" . $request['friendname'] . "', '" . $request['courses'] . "')";
	// echo $query . "<br>";
	if (!mysql_query($query)) { // Query error
		die("Insert error: " . mysql_error());
	}
	
	// Insert to classmates second time
	$query = "INSERT INTO classmates (username, friendname, courses) VALUES ('" . $request['friendname'] . "', '" . $request['username'] . "', '" . $request['courses'] . "')";
	// echo $query . "<br>";
	if (!mysql_query($query)) { // Query error
		die("Insert error: " . mysql_error());
	}
	
	?>
	<script language="JavaScript">
		window.location = "classmates.php";
	</script>
	<?php
}



echo "<h3>Classmate Request from $request[username]</h3>";
?>

<br>
<strong><?php echo $request['username']; ?></strong> would like to share grades in these classes:
<br><br>

<ul>
<?php
	$courses = explode(";", $request['courses']);

	// Print courses
	foreach($courses as $course) {
		// Blank courses show up for some reason
		if ($course == '')
			break;
	
		// Return course name
		preg_match("/([a-zA-Z]+)(\\d+)/", $course, $id);
		$query = "SELECT * FROM courses WHERE department='" . $id[1] . "' AND number=" . $id[2];
		if (!($course = mysql_query($query))) { // Query failed
			echo "Query error: " . mysql_error();
			require 'footer.php';
			die();
		}
		// Print course name
		$course = mysql_fetch_array($course);
		echo "<li style='margin-left: 2.5em;'>$course[course]</li>";
	}
?>
</ul>

<br><br>
<form action="" method="post">
<input type='submit' name='accept' value='Accept' />
<input type='submit' name='reject' value='Reject' />
</form>


<?php
require 'footer.php';
?>