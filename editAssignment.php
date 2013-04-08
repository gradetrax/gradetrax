<?php
require 'header.php';

if (!isset($_GET['name']) || !isset($_GET['grade']) || !isset($_GET['course'])) {
	echo "Assignment not defined. <a href='grades.php'>Return to assignments.</a>";
}

if (isset($_POST['submit'])) {
	$query = "UPDATE assignments SET name='$_POST[name]', grade='$_POST[grade]' WHERE name='$_GET[name]' AND courseID=$_GET[course]";
	// echo $query;
	if (!mysql_query($query)) {
		echo "Query failed: " . mysql_error();
		require 'footer.php';
		die();
	} else {
		?>
		<script language="JavaScript">
			window.location = "grades.php";
		</script>
		<?php
	}
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