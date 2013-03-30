<?php
$TITLE = "Grades";
require 'header.php';

// A Grade Has Been Updated
if (isset($_POST['grade'])) {

	 echo '<pre>';
	 print_r($_POST);
	 echo '</pre>';
	// Updates grade from -2 to a number.
	$query = "UPDATE assignments SET grade=" . $_POST['grade'] . " WHERE id=" . $_POST['assignment'];
	// echo $query;
	if (!mysql_query($query)) {
		die("Query failed: " . mysql_error());
	}
	$query = "SELECT grade FROM assignments WHERE categoryID=" . $_POST['categoryID'] . " AND username='" . $_SESSION['username'] . "'";
	if (!($result = mysql_query($query))) {
		die("Query failed: " . mysql_error());
	}
	while ($row = mysql_fetch_array($result))
	{
		echo "<pre>";
		print_r($row);
		echo "</pre>";
	}
}

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

function submitForm(form) {
	document.forms[form].submit();
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
echo "<table class='incomplete' style='display:none'>";
	while ($row = mysql_fetch_array($result)) {
	$query = "SELECT course FROM courses WHERE id=" . $row['courseID'];
	if (!($result2 = mysql_query($query))) {
		die("Query error: " . mysql_error());
	}
	$row2 = mysql_fetch_array($result2);

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
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade=-2";
if (!($result = mysql_query($query))) {
	die("Query error: " . mysql_error());
}
echo "<table class='tbg' style='display:none'>";
while ($row = mysql_fetch_array($result)) {
	$query = "SELECT course FROM courses WHERE id=" . $row['courseID'];
	if (!($result2 = mysql_query($query))) {
		die("Query error: " . mysql_error());
	}
	$row2 = mysql_fetch_array($result2);
		echo <<<EOT
		<tr>
		<td style='padding-right: 20px;'>$row2[course]</td>
		<td style='padding-right: 20px;'>$row[name]</td>
		<td><form name='$row[name]' method='post'>
			<input type='hidden' name='categoryID' value='$row[categoryID]' />			
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
$query = "SELECT * FROM assignments WHERE username='" . $_SESSION['username'] . "' AND grade>=0";
if (!($result = mysql_query($query))) {
	die("Query error: " . mysql_error());
}
echo "<table class='graded' style='display:none'>";
while ($row = mysql_fetch_array($result)) {
	$query = "SELECT course FROM courses WHERE id=" . $row['courseID'];
	if (!($result2 = mysql_query($query))) {
		die("Query error: " . mysql_error());
	}
	$row2 = mysql_fetch_array($result2);

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