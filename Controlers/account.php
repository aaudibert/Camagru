<?php 
	session_start();
	if (isset($_POST['btn-pwd']) && !empty($_POST)) {

		require_once '../Config/setup.php';

		$opass = htmlentities($_POST['password']);
		$pass = hash('whirlpool' ,$opass);
		$rpass = htmlentities($_POST['rpassword']);
		
		if (strcmp($opass, $rpass) != 0) {
			$_SESSION['flash']['error'] = "The passwords you entered didn't match, try again!";
			header('Location: ../Views/account.php');
			exit();
		}
		// if (strlen($pass) < 6)
		// 	$_SESSION['flash']['error'] = "The password you entered is too short, it should be at least 6 characters long";

		if (!strcmp($pass, $_SESSION['auth']['password'])) {
			$_SESSION['flash']['error'] = "Your new password is the same as your previous one";
			header('Location: ../Views/account.php');
			exit();
		}
		else {
			$id = $_SESSION['auth']['user_id'];
			$db->prepare("UPDATE users SET password = ? WHERE user_id = ?")->execute(array($pass, $id));
			$_SESSION['auth']['password'] = $pass;
			$_SESSION['flash']['success'] = "Your password was updated!";
			header('Location: ../Views/account.php');
			exit();
		}
	}
 ?>