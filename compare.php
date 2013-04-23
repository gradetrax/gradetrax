<?php
$TITLE = "Classmates";
require('header.php');

$weightTotal = 0; // To check if the user's total grade percentage is 100

// Returns all categories for this course
$query = "SELECT * FROM categories WHERE department='" . $_GET['department'] . "' AND number=" . $_GET['number'] . " AND username='" . $_GET['name'] . "'";
if (!($cats = mysql_query($query))) // Query failed
	die("Error with query: $query");
	

// Returns all assignments for this course
$query = "SELECT * FROM assignments WHERE courseid=" . $_GET['id'];
if (!($as = mysql_query($query))) // Query failed
	die("Error with query: $query");
$assignments = array();
// Add each assignment to array
while ($row = mysql_fetch_array($as)) {
	$assignments[] = $row;
}


// Print information and edit, update, and delete buttons for each category
while($cat = mysql_fetch_array($cats)) {
	$catAverage = 0;
	$catAssignments = 0;
	
	echo <<<EOT
	<p class="listItem" onclick="show('$cat[name]')">$cat[weight]%: $cat[name]</p>
	<p class="$cat[name]" style="display: none"><br></p>
	<!-- Edit form -->
	<button class="$cat[name]"  id="edit$cat[name]" onClick="show('edit$cat[name]')"style="display: none">Edit Category</button>
	<form action="" method="POST" class="edit$cat[name]" style = "display:none;">
	Category Name<input type="text" name="name" value="$cat[name]" onblur="if (this.value == '') {this.value = '$cat[name]';}"
	onfocus="if (this.value == '$cat[name]') {this.value = '';}" />
	<br>Percentage of Grade<input type="text" name="weight" value="$cat[weight]" onblur="if (this.value == '') {this.value = '$cat[weight]';}"
	onfocus="if (this.value == '$cat[weight]') {this.value = '';}" />
	<input type='hidden' value='$cat[name]' name='category'>
	<br><input type="submit" class="editBox" name="update" value="update" />
	<input type="submit" class="editBox" name="delete" value="delete" />
	<br><br>
	</form>
	<!-- /Edit form -->
	<p class="$cat[name]" style="display: none"><br></p>
	<!-- Assignments -->
	<table class="$cat[name]" style="display: none">
EOT;

	foreach($assignments as $assignment) {
		if ($assignment['categoryID'] == $cat['id']) {
			echo "<tr>";
			echo "<td style='padding-right:20px;'>$assignment[name]</td>";
			if ($assignment['grade'] >= 0) { // Don't print grades for ungraded assignments
				// Increment average counters
				$catAverage += $assignment['grade'];
				$catAssignments++;
				echo "<td>$assignment[grade]</td>";
			}
			echo "</tr>";
		}
	}
	
	// Calculate average
	if ($catAssignments != 0)
		$catAverage = number_format($catAverage / $catAssignments, 2);
	
	echo <<<EOT
	</table>
	<p class="$cat[name]" style="display: none"><strong>Category average: </strong>$catAverage</p>
	<!-- /Assignments -->
	<br>
	<p class="$cat[name]" style="display: none"><br></p>
EOT;

$weightTotal += $cat['weight']; // Add weight to total
}





require 'footer.php';
?>