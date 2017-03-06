<?php 
session_start();

require_once '../Config/setup.php';

	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.html?redirect=logged');
		exit();
	}

function str_random($len) {
	$alpha = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
	return substr(str_shuffle(str_repeat($alpha, $len)), 0, $len);
}


if (!empty($_POST)) {
	$uname = htmlentities($_POST['uname']);
	$mail = htmlentities($_POST['email']);
	$pass = htmlentities($_POST['password']);
	$rpass = htmlentities($_POST['rpassword']);
	$errors = 0;

	if (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $uname))
		$_SESSION['flash']['uname'] = "Your username must only contain letters, numbers or '_' and be at least 3 characters long, please try using another username";
	else {
		$req = $db->prepare("SELECT user_id FROM users WHERE username = ?");
		$req->execute(array($uname));
		if ($req->fetch()) {
			$_SESSION['flash']['taken'] = "The username you entered was already taken, please try using another username";
			$errors++;
		}
	}
	if (strcmp($pass, $rpass) != 0){
		$_SESSION['flash']['rpassword'] = "The passwords you entered didn't match, try again!";
		$errors++;
	}
	// if (strlen($pass) < 6) {
	// 	$_SESSION['flash']['password'] = "The password you entered is too short, it should be at least 6 characters long";
	// 	$errors++;
	// }
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
		$_SESSION['flash']['email'] = "The email you entered is either in the wrong format or unsupported by our site, please use a different email adress";
		$errors++;
	}
	else {
		$req = $db->prepare("SELECT user_id FROM users WHERE email = ?");
		$req->execute(array($mail));
		if ($req->fetch()) {
			$_SESSION['flash']['mtaken'] = "The email adress you entered was already taken, please try using another one";
			$errors++;
		}
	}

	if (!$errors) {
		$req = $db->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
		$token = str_random(60);
		$req->execute(array($uname, hash('whirlpool', $pass), $mail, $token));
		$id = $db->lastInsertId('user_id');
		mail($mail, "Account validation", "To validate your account, please click on the following link\n\nhttp://localhost:8080/camagru/Controlers/confirm.php?id=".$id."&token=".$token);
		$_SESSION['success']['success'] = 'A validation mail has been sent to your adress';
		if (isAjax()) {
			$ret = array('success' => 'A validation mail has been sent to your adress', 'redirect' => 'http://localhost:8080/camagru/Views/sign_in.php');
			echo json_encode($ret);
			unset($_SESSION['flash']);
			die();
		}
		header('Location: ../Views/sign_in.php');
		exit();
	}
	else {
		if (isAjax()) {
			http_response_code(400);
			echo json_encode($_SESSION['flash']);
			unset($_SESSION['flash']);
			die();
		}
		header('Location: ../Views/sign_in.php');
		exit();
	}
}
 ?>
