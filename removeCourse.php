<?php
require 'header.php';

if (isset($_GET['delete'])) {
	$query = "DELETE FROM courses WHERE id={$_GET['id']}";
	//echo $query;

	if (mysql_query($query)) {
		?>
		<script language="JavaScript">
			window.location = "courses.php";
		</script>
		<?php
	} else {
		echo "Error removing course";
	}
}

?>

Are you sure you want to delete this course?
<br>(Action cannot be undone.)
<br>
<br>
<a href="removeCourse.php?delete=1&id=<?php echo $_GET['id']; ?>" class="mainButton">Delete Course</a>
<a href="viewCourse.php?id=<?php echo $_GET['id']; ?>" class="mainButton">Cancel</a>

<?php
require 'footer.php';
?>