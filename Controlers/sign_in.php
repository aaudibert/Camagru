<?php
session_start();

require_once '../Config/setup.php';

if (isset($_POST['btn-login']) && !empty($_POST)) {
	$uname = htmlentities($_POST['uname']);
	$pass = htmlentities($_POST['password']);
	$pass = hash('whirlpool', $pass);
	
	$req = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? AND confirmed_at IS NOT NULL");
	$req->execute(array($uname, $uname));
	$usr = $req->fetch();
	
	if (!strcmp($pass, $usr['password'])) {
		$_SESSION['auth'] = $usr;
		$_SESSION['flash']['success'] = 'Welcome '.$_SESSION['auth']['username'].'!';
		header('Location: ../Views/home.php');
		exit();
	}
	else {
		$_SESSION['flash']['alert'] = "Your account wasn't validated or you entered an invalid username or password";
		header('Location: ../Views/sign_in.php');
		exit();
	}
}

?>