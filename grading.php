<?php
// Handles the "categories" grades page on individual classes.
// Courses > Select Class > Grades > Listed Categories/Percentages.

$TITLE = "Course Grade";
require('header.php');

$weightTotal = 0; // To check if the user's total grade percentage is 100


if(isset($_POST['submit'])) { // If the submit button has been clicked

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) { // The fields were appropriately filled
	
		// Returns name of category where the name is equal to the name entered in the form
		// Just checks to see if that name is already in the table
		$query="SELECT name FROM categories WHERE name='" . $_POST['name'] . "' AND courseID=" . $_GET['id'];
		if(!($result=mysql_query($query)))	// Query failed
		{
			echo "Name selection failed";
		}
		else // Query succeeded
		{
			if ($row = mysql_fetch_array($result)) // The name was returned - name in table
			{
				echo "Category already exists.<br>";
			}
			else // Nothing was returned - name not in table
			{
				// Insert new category
				$query = "INSERT INTO categories (courseid, name, weight, grade) VALUES (" . $_GET['id'] . ", '" . $_POST['name'] . "', " . $_POST['weight'] . ", -1)";
				if (!mysql_query($query)) { // Query failed
					die("Category insertion failed: $query");
				}
			}
		}
		
	} else { // The fields were not appropriately filled
		echo "<p class='warning'>Please enter a category name and numeric weight.</p><br>";
	}
	
}


// Update button after clicking edit
if(isset($_POST['update'])) {

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) { // Fields were filled appropriately
		// Updates a row
		$query = "UPDATE categories SET name='" . $_POST['name'] . "', weight=" . $_POST['weight'] . " WHERE name='" . $_POST['category'] . "'";
		if (!mysql_query($query)) { // Query failed
			echo "Update failed: $query";
			echo "<br>" . mysql_error();
		}
		
	} else { // Fields were not appropriately filled
		echo "Please enter a category name and numeric weight.<br><br>";
	}
	
}


// Delete button after clicking edit
if(isset($_POST['delete'])) {

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) { // Fields were filled appropriately
		// Deletes a category
		$query = "DELETE FROM categories WHERE name='" . $_POST['category'] . "' AND courseID=". $_GET['id'];
		if (!mysql_query($query)) { // Query failed
			echo "Update failed: $query";
			echo "<br>" . mysql_error();
		}
		
	} else { // Fields were not appropriately filled
		echo "Please enter a category name and numeric weight.<br><br>";
	}
	
}




// Returns data for this course
$query = "SELECT * FROM courses WHERE id = " . $_GET['id'];
if (!($course = mysql_query($query))) // Query failed
	die("Error with query: $query");

$course = mysql_fetch_array($course); // Get first row
if ($_SESSION['username'] != $course['username']) // The courseID was not for a course the user created - don't let them edit
	die("You don't have permission to access this course!");

// Returns all categories for this course
$query = "SELECT * FROM categories WHERE courseid=" . $_GET['id'];
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

if (($weightTotal < 100) OR ($weightTotal > 100)) // Weights entered to not sum to 100%
	echo "Warning: Your grade percentages do not add up to 100.";

?>





<script>
// Shows or hides elements where class='type'
function show(type) {
	if (type == 'newCat') {
		document.getElementById('newCat').style.display='none';
	}
	var elements = document.getElementsByClassName(type);
	for(var i = 0, length = elements.length; i < length; i++) {

		if (elements[i].style.display == 'none')
			elements[i].style.display='block';
		else
			elements[i].style.display='none';
    }
	document.getElementById(type).style.display='none';
};
</script>

<br><br>
<!-- Form to create a new category -->
<form action="" method="POST" class="newCat" style = "display:none;">
	Category Name<input type="text" name="name" value="Example: Homework" onblur="if (this.value == '') {this.value = 'Example: Homework';}"
	onfocus="if (this.value == 'Example: Homework') {this.value = '';}" />
	<br>Percentage of Grade<input type="text" name="weight" value="Example: 20" onblur="if (this.value == '') {this.value = 'Example: 20';}"
	onfocus="if (this.value == 'Example: 20') {this.value = '';}" />
	<br><input type="submit" class="editBox" name="submit" value="Submit" />
</form>

<!-- New Category button -->
<button id="newCat" onClick="show('newCat')">New Category</button>





<?php
require('footer.php');
?>