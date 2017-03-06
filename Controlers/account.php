<?php 
	session_start();
	require_once '../Config/setup.php';
	
	if (!empty($_POST)) {
		$opass = htmlentities($_POST['password']);
		$pass = hash('whirlpool' ,$opass);
		$rpass = htmlentities($_POST['rpassword']);
		
		if (strcmp($opass, $rpass) != 0) {
			$_SESSION['flash']['rpassword'] = "The passwords you entered didn't match, try again!";
		}
		// if (strlen($pass) < 6) {
		// 	$_SESSION['flash']['password'] = "The password you entered is too short, it should be at least 6 characters long";
		//	}
		
		if (!isset($_SESSION['flash'])) {
			$id = $_SESSION['auth']['user_id'];
			$db->prepare("UPDATE users SET password = ? WHERE user_id = ?")->execute(array($pass, $id));
			$_SESSION['auth']['password'] = $pass;
			$_SESSION['flash']['success'] = "Your password was updated!";
			$_SESSION['flash']['redirect'] = 'http://localhost:8080/camagru/Views/home.php';
			if (isAjax()) {
				echo json_encode($_SESSION['flash']);
				unset($_SESSION['flash']);
				$_SESSION['flash']['success'] = 'Welcome '.$_SESSION['auth']['username'].'!';
				die();
			}
			header('Location: ../Views/home.php');
			exit();
		}
		else {
			if (isAjax()) {
				http_response_code(400);
				echo json_encode($_SESSION['flash']);
				unset($_SESSION['flash']);
				die();
			}
			header('Location: ../Views/account.php');
			exit();
		}
	}
 ?>