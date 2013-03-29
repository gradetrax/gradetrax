<?php
$TITLE = "Grades";
require 'header.php';
?>


<script>
function show(type) {
	var elements = document.getElementsByClassName(type);
	for(var i = 0, length = elements.length; i < length; i++) {
		if (elements[i].style.display == 'none')
			elements[i].style.display='block';
		else
			elements[i].style.display='none';
    }
};
</script>



<h3>Grades</h3>

<!-- Incomplete assignments are listed in the database where grade = -1 -->
<br><br><p class="listItem" onclick="show('incomplete');">Incomplete</p>
<?php
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade=-1";
if (!($result = mysql_query($query))) {
	die("Query error: " . mysql_error());
}
while ($row = mysql_fetch_array($result)) {
	echo '<p class="incomplete" style="display: none">' . $row['name'] . '</p>';
}
?>



<!-- Ungraded assignments are listed in the database where grade = -2 -->
<br><br><p class="listItem" onclick="show('tbg');">To Be Graded</p>
<?php
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade=-2";
if (!($result = mysql_query($query))) {
	die("Query error: " . mysql_error());
}
while ($row = mysql_fetch_array($result)) {
	echo '<p class="tbg" style="display: none">' . $row['name'] . '</p>';
}
?>




<!-- Graded assignments are listed in the database where grade = [0, 100] -->
<br><br><p class="listItem" onclick="show('graded');">Graded</p>
<?php
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade>=0";
if (!($result = mysql_query($query))) {
	die("Query error: " . mysql_error());
}
while ($row = mysql_fetch_array($result)) {
	echo '<p class="graded" style="display: none">' . $row['name'] . '</p>';
}
?>



<br><br>
<a href="addAssignment.php" class="mainButton">New Assignment</a>



<?php
require 'footer.php';
?>