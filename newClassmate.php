<?php
$TITLE = "Classmates";
require 'header.php';


if(isset($_POST['submit']) && $_POST['friendname'] != "") {
	// Make sure friend's username exists
	$query = "SELECT username FROM students WHERE username='" . $_POST['friendname'] . "'";
	$result = mysql_query($query);
	if (!($row = mysql_fetch_array($result))) { // Entered username does not exist
		echo "That user does not exist! Please go back and try again.";
		require 'footer.php';
		die();
	}
	

	// Gather courses into a string
	if( isset($_POST['courses']) && is_array($_POST['courses']) ) {
		$courses = "";
		foreach($_POST['courses'] as $course) {
			$courses .= $course . ";";
		}
		//echo $courses . "<br>";
	} else {
		echo "The two of you have no mutual courses.";
		require 'footer.php';
		die();
	}
	

	
	// Send request
	$query = "INSERT INTO requests (username, friendname, courses) VALUES ('$_SESSION[username]', '$_POST[friendname]', '$courses')";
	if(!mysql_query($query)) { // Query failed
		echo "Query error: " . mysql_error();
		require 'footer.php';
		die();
	}
	
	?>
	 <script language="JavaScript">
		window.location = "classmates.php";
	 </script>			
	<?php
	
}
	
// Returns all courses for this user
$query = "SELECT * FROM courses WHERE username='" . $_SESSION['username'] . "'";
if (!($result = mysql_query($query))) { // query failed
	echo "Query error: " . mysql_error();
	require 'footer.php';
	die();
}

// Store courses
$courses = array();
while ($row = mysql_fetch_array($result)) {
	$courses[] = $row;
}

// echo "<pre>";
// print_r($courses);
// echo "</pre>";

?>

<h3>Send Classmate Request</h3>
<br>

<form action="" method="post">
<br><strong>Username:</strong> <input type="text" name="friendname" />
<br><strong>Mutual Courses:</strong>
<?php
	foreach($courses as $course) {
		$id = $course['department'] . $course['number'];
		echo "<br><input type='checkbox' name='courses[]' value='$id' style='margin-left: 2em;'> &nbsp $course[course]";
	}
?>
<br><br><input type='submit' name='submit' value='Submit Request' />
</form>


<?php
require 'footer.php';
?>