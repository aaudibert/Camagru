<?php
	session_start();

	include_once '../Config/setup.php';

	echo '<div class="user_log"/> Welcome ' . $_SESSION['user'] . ' !</div>'
	if (isset($_SESSION['file']) {
		unset($_SESSION['file']);
	}

	$user_id = $_SESSION['user.session'];
	$stmt = $DB_con->prepare("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id" =>$user_id));
	$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

	if (isset($_GET['uploaded']))
		echo "<img src='". $_SESSION['upload_file'] . "'id='photo'>";

	include "../Views/home.php"
	include 'cute_galerie.php';

?>