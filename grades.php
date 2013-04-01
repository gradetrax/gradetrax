<?php
$TITLE = "Grades";
require 'header.php';


if (isset($_POST['grade'])) { // An assignment was marked graded

	// Prints POST array for debugging
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';
	
	// Updates grade from -1 to -2 (incomplete to complete) or -2 to a number (complete to graded).
	$query = "UPDATE assignments SET grade=" . $_POST['grade'] . " WHERE id=" . $_POST['assignment'];
	if (!mysql_query($query)) { // Query failed
		die("Assignment update error: " . mysql_error());
	}

	if ($_POST['grade'] >= 0) {
		// Returns all graded assignments in the category
		$query = "SELECT grade FROM assignments WHERE categoryID=" . $_POST['categoryID'] . " AND grade>-1";
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
		$query = "UPDATE categories SET grade=" . $categoryAverage . " WHERE id=" . $_POST['categoryID'];
		if (!($result = mysql_query($query))) { // Query failed
			die("Category update error: " . mysql_error());
		}
		
		// Returns all category averages
		$query = "SELECT * FROM categories WHERE courseID=" . $_POST['courseID'] . " AND grade>-1";
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
		$query = "UPDATE courses SET grade=" . $courseAverage . " WHERE id=" . $_POST['courseID'];
		if (!($result = mysql_query($query))) { // Query failed
			die("Course average updating error: " . mysql_error());
		}
	}
}

?>


<script>
// Shows or hides elements where class='type'
function show(type) {
	var elements = document.getElementsByClassName(type);
	for(var i = 0, length = elements.length; i < length; i++) {
		if (elements[i].style.display == 'none')
			elements[i].style.display='block';
		else
			elements[i].style.display='none';
    }
};

// Submits a form named 'formName'
// (For use when a link is prettier than a submit button)
function submitForm(formName) {
	document.forms[formName].submit();
};
</script>



<h3>Grades</h3>

<!-- Incomplete assignments are listed in the database where grade = -1 -->
<br><br><p class="listItem" onclick="show('incomplete');">Incomplete</p>
<?php
// Returns all incomplete assignments
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade=-1";
if (!($result = mysql_query($query))) { // Query failed
	die("Query error: " . mysql_error());
}
echo "<table class='incomplete' style='display:none'>";
	while ($row = mysql_fetch_array($result)) { // Print a table row for each incomplete assignment
	// Returns course which this assignment is for
	$query = "SELECT course FROM courses WHERE id=" . $row['courseID'];
	if (!($result2 = mysql_query($query))) { // Query failed
		die("Query error: " . mysql_error());
	}
	$row2 = mysql_fetch_array($result2); // Store name of course

	// Print the information and a form to mark complete
	echo <<<EOT
		<tr>
		<td style='padding-right: 20px;'>$row2[course]</td>
		<td style='padding-right: 20px;'>$row[name]</td>
		<td><form name='$row[name]' method='post'>
			<input type='hidden' name='grade' value='-2'>
			<input type='hidden' name='assignment' value='$row[id]'>
			<a class="listItem" onclick="submitForm('$row[name]')">Mark Complete</a>
		</form></td>
		</tr>
EOT;
}
echo "</table>";
?>



<!-- Ungraded assignments are listed in the database where grade = -2 -->
<br><br><p class="listItem" onclick="show('tbg');">To Be Graded</p>
<?php
// Returns all completed assignments without a grade
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade=-2";
if (!($result = mysql_query($query))) { // Query failed
	die("Query error: " . mysql_error());
}
echo "<table class='tbg' style='display:none'>";
while ($row = mysql_fetch_array($result)) { // Print a table row for every ungraded assignment
	// Returns course which this assignment is for
	$query = "SELECT * FROM courses WHERE id=" . $row['courseID'];
	if (!($result2 = mysql_query($query))) { // Query failed
		die("Query error: " . mysql_error());
	}
	$row2 = mysql_fetch_array($result2); // Store course

	// Print information and a form to mark graded
	echo <<<EOT
	<tr>
	<td style='padding-right: 20px;'>$row2[course]</td>
	<td style='padding-right: 20px;'>$row[name]</td>
	<td><form name='$row[name]' method='post'>
		<input type='hidden' name='categoryID' value='$row[categoryID]' />
		<input type='hidden' name='courseID' value='$row2[id]' />
		<input type='hidden' name='assignment' value='$row[id]' />
		<a class="listItem" onclick="submitForm('$row[name]')">Mark Graded:</a>
		<input type='text' name='grade' style='width: 2.5em !important' />
	</form></td>
EOT;
}
echo "</table>";
?>




<!-- Graded assignments are listed in the database where grade = [0, 100] -->
<br><br><p class="listItem" onclick="show('graded');">Graded</p>
<?php
// Returns assignments with grades
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade>=0";
if (!($result = mysql_query($query))) { // Query failed
	die("Query error: " . mysql_error());
}
echo "<table class='graded' style='display:none'>";
while ($row = mysql_fetch_array($result)) { // Print each graded assignment
	// Returns name of course the assignment is for
	$query = "SELECT course FROM courses WHERE id=" . $row['courseID'];
	if (!($result2 = mysql_query($query))) { // Query failed
		die("Query error: " . mysql_error());
	}
	$row2 = mysql_fetch_array($result2); // Store name of course

	// Print information
	echo <<<EOT
	<tr>
	<td style='padding-right: 20px;'>$row2[course]</td>
	<td style='padding-right: 20px;'>$row[name]</td>
	<td>$row[grade]%</td>
	</tr>
EOT;
}
echo "</table>";
?>



<br><br>
<a href="addAssignment.php" class="mainButton">New Assignment</a>



<?php
require 'footer.php';
?>