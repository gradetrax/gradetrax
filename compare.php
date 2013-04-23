<?php
$TITLE = "Classmates";
require('header.php');

echo '<div style="float:left; padding-right:30; min-width:300">';


$query = "SELECT grade FROM courses WHERE department='" . $_GET['department'] . "' AND number=" . $_GET['number'] . " AND username='" . $_GET['name'] . "'";
if (!($results = mysql_query($query))) // Query failed
	die("Error with query: $query");
$grade=mysql_fetch_array($results);

if($grade[0]>=0)
  echo "<h3> $_GET[name]:$grade[0] </h3>";
else
   echo "<h3> $_GET[name]:N/A </h3>";
   
   
// Returns all categories for this course
$query = "SELECT * FROM courses WHERE department='" . $_GET['department'] . "' AND number=" . $_GET['number'] . " AND username='" . $_GET['name'] . "'";
if (!($results = mysql_query($query))) // Query failed
	die("Error with query: $query");
$course=mysql_fetch_array($results);	
$query = "SELECT * FROM categories WHERE courseid=" . $course['id'];
if (!($cats = mysql_query($query))) // Query failed
	die("Error with query: $query");
	

// Returns all assignments for this course
$query = "SELECT * FROM assignments WHERE courseid=" . $course['id'];
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


}
echo '</div>';

echo '<div style="float:left">';

$query = "SELECT grade FROM courses WHERE department='" . $_GET['department'] . "' AND number=" . $_GET['number'] . " AND username='" . $_SESSION['username'] . "'";
if (!($results = mysql_query($query))) // Query failed
	die("Error with query: $query");
$grade=mysql_fetch_array($results);
if($grade[0]>=0)
  echo "<h3> $_SESSION[username]:$grade[0] </h3>";
else
   echo "<h3> $_SESSION[username]:N/A </h3>";


// Returns all categories for this course
$query = "SELECT * FROM courses WHERE department='" . $_GET['department'] . "' AND number=" . $_GET['number'] . " AND username='" . $_SESSION['username'] . "'";
if (!($results = mysql_query($query))) // Query failed
	die("Error with query: $query");
$course=mysql_fetch_array($results);	
$query = "SELECT * FROM categories WHERE courseid=" . $course['id'];
if (!($cats = mysql_query($query))) // Query failed
	die("Error with query: $query");
	

// Returns all assignments for this course
$query = "SELECT * FROM assignments WHERE courseid=" . $course['id'];
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


}
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
<?php
echo '</div>';





require 'footer.php';
?>