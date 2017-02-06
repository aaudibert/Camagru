<?php 
	session_start();
	
	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.php?redirect=logged');
		exit();
	}
	
	$id = $_GET['id'];
	$token = $_GET['token'];
	
	require_once '../Config/setup.php';
	$req = $db->prepare('SELECT * FROM users WHERE user_id = ?');
	$req->execute(array($id));
	$user = $req->fetch();
	
	if ($user && $user['confirmation_token'] == $token) {
		$db->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE user_id = ?')->execute(array($id));
		$_SESSION['auth'] = $user;
		$_SESSION['flash']['success'] = 'Your account was validated, welcome!';		
		header('Location: ../views/home.php');
		exit();
	}
	else {
		$_SESSION['flash']['alert'] = "This token isn't valid anymore";
		header('Location: ../views/sign_in.php');
		exit();
	}
 ?>