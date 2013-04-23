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
	
	// Insert to classmates
	$query = "INSERT INTO classmates (username, friendname, courses) VALUES ('" . $request['username'] . "', '" . $request['friendname'] . "', '" . $request['courses'] . "')";
	// echo $query . "<br>";
	if (!mysql_query($query)) { // Query error
		die("Insert error: " . mysql_error());
	}
	
	// // Insert to classmates
	// $query = "INSERT INTO classmates (username, friendname, courses) VALUES ('" . $request['friendname'] . "', '" . $request['username'] . "', '" . $request['courses'] . "')";
	// // echo $query . "<br>";
	// if (!mysql_query($query)) { // Query error
		// die("Insert error: " . mysql_error());
	// }
	
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
		$query = "SELECT * FROM courses WHERE id=" . $course;
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