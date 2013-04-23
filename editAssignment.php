<?php
$TITLE = "Edit Assignment";
require 'header.php';

if (!isset($_GET['name']) || !isset($_GET['grade']) || !isset($_GET['course'])) {
	echo "Assignment not defined. <a href='grades.php'>Return to assignments.</a>";
	require 'footer.php';
	die();
}

if (isset($_POST['submit']))
{

	if ($_POST['grade'] < 0) {
		echo "Please enter a positive grade.";
		require 'footer.php';
		die();
	}

	$query = "UPDATE assignments SET name='$_POST[name]', grade='$_POST[grade]' WHERE name='$_GET[name]' AND courseID=$_GET[course]";
	// echo $query;
	if (!mysql_query($query)) {
		echo "Query failed: " . mysql_error();
		require 'footer.php';
		die();
	}

	// Prints POST array for debugging
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	if($_POST['grade']!='')
	{
	// Updates grade from -1 to -2 (incomplete to complete) or -2 to a number (complete to graded).
	$query = "UPDATE assignments SET grade=" . $_POST['grade'] . " WHERE name='$_GET[name]' AND courseID=$_GET[course]";
	if (!mysql_query($query)) { // Query failed
		die("Assignment update error: " . mysql_error());
	}

	if ($_POST['grade'] >= 0) {
		// Returns all graded assignments in the category
		$query = "SELECT grade FROM assignments WHERE categoryID=" . $_GET['category'] . " AND grade>-1";
		if (!($result = mysql_query($query))) { // Query failed
			die("Category grades retrieval error: " . mysql_error());
		}
		$total = 0; // Sum of grades
		$count = 0; // Number of graded assignments
		while ($row = mysql_fetch_array($result)) // Do something for each graded assignment
		{
			// Print graded assignments for debugging
			// echo "<pre>";
			// print_r($row);
			// echo "</pre><br>";
		
			$total += $row['grade'];
			$count += 1;
			
		}
		$categoryAverage = $total / $count;
		// echo $categoryAverage;
		
		// Stores new average of this category
		$query = "UPDATE categories SET grade=" . $categoryAverage . " WHERE id=" . $_GET['category'];
		if (!($result = mysql_query($query))) { // Query failed
			die("Category update error: " . mysql_error());
		}
		
		// Returns all category averages
		$query = "SELECT * FROM categories WHERE courseID=" . $_GET['course'] . " AND grade>-1";
		if (!($result = mysql_query($query))) { // Query failed
			die("Category averaging error: " . mysql_error());
		}
		$total = 0;
		$weightGraded = 0;
		while($row = mysql_fetch_array($result)) { // For each category average retrieved
			$total += $row['grade'] * $row['weight'];
			$weightGraded += $row['weight'];
		}
		$courseAverage = $total / $weightGraded;
		
		// Store newly calculated course average
		$query = "UPDATE courses SET grade=" . $courseAverage . " WHERE id=" . $_GET['course'];
		if (!($result = mysql_query($query))) { // Query failed
			die("Course average updating error: " . mysql_error());
		}
	}
	}
		?>
		<script language="JavaScript">
			window.location = "grades.php";
		</script>
		<?php
}
?>

<h3>Edit Assignment</h3>

<form action="" method="post">
<br>Name: <input type="text" name="name" value="<?php echo $_GET['name']; ?>">
<br>Grade: <input type="text" name="grade" value="<?php echo $_GET['grade']; ?>">
<br><br><input type="submit" name="submit" value="Save Changes">
</form>

<?php
require 'footer.php';
?>