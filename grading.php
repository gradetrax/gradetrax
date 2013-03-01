<?php
require('header.php');

if(isset($_POST['submit'])) {

	if (($_POST['name'] != "") && (is_numeric($_POST['weight']))) {
		
		$query = "INSERT INTO categories (courseid, name, weight) VALUES (" . $_GET['id'] . ", '" . $_POST['name'] . "', " . $_POST['weight'] . ")";
//		echo $query;
		if (!mysql_query($query)) {
			echo "Category insertion failed: $query";
		}
		
	} else {
		echo "Please enter a category name and numeric weight.<br><br>";
	}
	
}


$query = "SELECT * FROM courses WHERE id=" . $_GET['id'];
if (!($course = mysql_query($query)))
	die("Error with query: $query");

$course = mysql_fetch_array($course);
if ($_SESSION['username'] != $course['username'])
	die("You don't have permission to access this course!");

$query = "SELECT * FROM categories WHERE courseid=" . $_GET['id'];
if (!($cats = mysql_query($query)))
	die("Error with query: $query");

while($cat = mysql_fetch_array($cats)) {
	echo $cat['id'] . ": " . $cat['name'] . " at " . $cat['weight'] . "%<br>";
}

?>


<script>
function show() {
	document.getElementById('newCat').style.display='none';
	var elements = document.getElementsByClassName('editBox');
	for(var i = 0, length = elements.length; i < length; i++) {
          elements[i].style.display='block';
    }
};
</script>

<br><br>

<form action="" method="POST" class="editBox">
	Category Name<input type="text" name="name" class="editBox" value="Example: Homework" onblur="if (this.value == '') {this.value = 'Example: Homework';}"
	onfocus="if (this.value == 'Example: Homework') {this.value = '';}" />
	<br>Percentage of Grade<input type="text" name="weight" class="editBox" value="Example: 20" onblur="if (this.value == '') {this.value = 'Example: 20';}"
	onfocus="if (this.value == 'Example: 20') {this.value = '';}" />
	<br><input type="submit" class="editBox" name="submit" value="Submit" />
</form>


<button id="newCat" onClick="show()">New Category</button>



<?php
require('footer.php');
?>