<?php
session_start();

require_once '../Config/setup.php';

if (!empty($_POST)) {
	$uname = htmlentities($_POST['in_uname']);
	$pass = htmlentities($_POST['in_password']);
	$pass = hash('whirlpool', $pass);

	$req = $db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND confirmed_at IS NOT NULL");
	$req->execute(array($uname, $uname));
	$usr = $req->fetch();

	if (!strcmp($pass, $usr['password'])) {
		$_SESSION['auth'] = $usr;
		$_SESSION['flash']['redirect'] = 'http://localhost:8080/camagru/Views/home.php';
		$_SESSION['success']['success'] = 'Welcome '.$_SESSION['auth']['username'].'!';
		if (isAjax()) {
			echo json_encode($_SESSION['flash']);
			unset($_SESSION['flash']);
			$_SESSION['success']['success'] = 'Welcome '.$_SESSION['auth']['username'].'!';
			die();
		}
		header('Location: ../Views/home.php');
		exit();
	}
	else {
		$_SESSION['flash']['in_uname'] = "Your account wasn't validated or you entered an invalid username or password";
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