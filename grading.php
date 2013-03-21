<?php
$TITLE = "Course Grade";
require('header.php');
$weightTotal = 0; // To check if the user's total grade percentage is 100.

if(isset($_POST['submit'])) {

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) {
		
		$query = "INSERT INTO categories (courseid, name, weight) VALUES (" . $_GET['id'] . ", '" . $_POST['name'] . "', " . $_POST['weight'] . ")";
//		echo $query;
		if (!mysql_query($query)) {
			echo "Category insertion failed: $query";
		}
		
	} else {
		echo "<p class='warning'>Please enter a category name and numeric weight.</p><br>";
	}
	
}


$query = "SELECT * FROM courses WHERE id = " . $_GET['id'];
if (!($course = mysql_query($query)))
	die("Error with query: $query");

$course = mysql_fetch_array($course);
if ($_SESSION['username'] != $course['username'])
	die("You don't have permission to access this course!");

$query = "SELECT * FROM categories WHERE courseid=" . $_GET['id'];
if (!($cats = mysql_query($query)))
	die("Error with query: $query");

while($cat = mysql_fetch_array($cats)) {
	echo <<<EOT
	<p class="listItem" onclick="show('$cat[name]')">$cat[weight]%: $cat[name]</p>
	<p class="$cat[name]" style="display: none">lol</p>
EOT;
$weightTotal += $cat['weight'];
}
if ($weightTotal < 100)
	echo "Warning: Your grade percentages do not add up to 100.";
?>


<script>
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
};
</script>

<br><br>

<form action="" method="POST" class="newCat" style = "display:none;">
	Category Name<input type="text" name="name" value="Example: Homework" onblur="if (this.value == '') {this.value = 'Example: Homework';}"
	onfocus="if (this.value == 'Example: Homework') {this.value = '';}" />
	<br>Percentage of Grade<input type="text" name="weight" value="Example: 20" onblur="if (this.value == '') {this.value = 'Example: 20';}"
	onfocus="if (this.value == 'Example: 20') {this.value = '';}" />
	<br><input type="submit" class="editBox" name="submit" value="Submit" />
</form>





<button id="newCat" onClick="show('newCat')">New Category</button>





<?php
require('footer.php');
?>