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



if(isset($_POST['update'])) {

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) {
		
		$query = "UPDATE categories SET name='" . $_POST['name'] . "', weight=" . $_POST['weight'] . " WHERE name='" . $_POST['category'] . "'";
//		echo $query;
		if (!mysql_query($query)) {
			echo "Update failed: $query";
			echo "<br>" . mysql_error();
		}
		
	} else {
		echo "Please enter a category name and numeric weight.<br><br>";
	}
	
}



if(isset($_POST['delete'])) {

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) {
		
		$query = "DELETE FROM categories WHERE name='" . $_POST['category'] . "' AND courseID=". $_GET['id'];
//		echo $query;
		if (!mysql_query($query)) {
			echo "Update failed: $query";
			echo "<br>" . mysql_error();
		}
		
	} else {
		echo "Please enter a category name and numeric weight.<br><br>";
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
	<button class="$cat[name]"  id="edit$cat[name]" onClick="show('edit$cat[name]')"style="display: none">edit </button>
	
	<form action="" method="POST" class="edit$cat[name]" style = "display:none;">
	Category Name<input type="text" name="name" value="$cat[name]" onblur="if (this.value == '') {this.value = '$cat[name]';}"
	onfocus="if (this.value == '$cat[name]') {this.value = '';}" />
	<br>Percentage of Grade<input type="text" name="weight" value="$cat[weight]" onblur="if (this.value == '') {this.value = '$cat[weight]';}"
	onfocus="if (this.value == '$cat[weight]') {this.value = '';}" />
	<input type='hidden' value='$cat[name]' name='category'>
	<br><input type="submit" class="editBox" name="update" value="update" />
	<br><input type="submit" class="editBox" name="delete" value="delete" />
	</form>
	

	
	
	
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