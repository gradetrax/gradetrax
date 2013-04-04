<?php
session_start();
require '../dbConnect.php';
?>

<html>
<head>
<title><?php echo $TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="styles/layout.css">

</head>
<body>

<div class="wrapper">

<div id="header">
	<?php
	if (!isset($_SESSION['username'])) {
		echo <<< EOT
			<a href="index.php" style="float: left">GradeTrax</a>
			<a href="login.php">Log In</a>
			 | 
			<a href="create.php">Create an Account</a>
EOT;

	} else {
		echo '<a href="index.php" style="float: left">GradeTrax</a>';
		echo '<a href="account.php">' . $_SESSION['username'] . '</a>';
		echo <<< EOT
			 | 
			<a href="logout.php">Log Out</a>
EOT;

	}
	?>
</div>
	
	
<div id="main">

<!--
<p class="pageTitle">
	<?php
	if ($TITLE != "Home")
		echo $TITLE;
	?>
</p>
-->

<br>

<?php
// Make sure the user's logged in
if (!isset($_SESSION['username'])) {
	// Make sure we're not on the login page
	$parts = explode('/', $_SERVER["PHP_SELF"]);
    if ($parts[count($parts) - 1] != "login.php" && $parts[count($parts) - 1] != "create.php") {
		echo "Please log in.";
		require 'footer.php';
		die();
	}
}
?>