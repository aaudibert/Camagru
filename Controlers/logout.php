<?php 
	session_start();
	unset($_SESSION['auth']);
	header('Location: ../Views/sign_in.php');
 ?>