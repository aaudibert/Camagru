<?php 
	session_start();
	require_once '../Config/setup.php';

	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.php?redirect=logged');
		exit();
	}
	
	function str_random($len) {
		$alpha = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		return substr(str_shuffle(str_repeat($alpha, $len)), 0, $len);
	}

	if (!empty($_POST)) {
		$email = htmlentities($_POST['email']);
		
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			$$_SESSION['flash']['email'] = "The email you entered is either in the wrong format or unsupported by our site, please use a different email adress";
		}
		$req = $db->prepare("SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL");
		$req->execute(array($email));
		$usr = $req->fetch();
		
		if ($usr && $usr['confirmed_at'] != NULL) {
			$reset_token = str_random(60);
			$_SESSION['success']['success'] = "Instructions were sent to you by email";
			$db->prepare("UPDATE users SET reset_token = ?, reset_at = NOW() WHERE user_id = ?")->execute(array($reset_token, $usr['user_id']));
			mail($email, "Forgotten password", "To get a new password, please click on the following link within 30 minutes\n\nhttp://localhost:8080/camagru/Controlers/reset.php?id=".$usr['user_id']."&token=".$reset_token);
			$_SESSION['flash']['redirect'] = 'http://localhost:8080/camagru/Views/home.php';
			if (isAjax()) {
				echo json_encode($_SESSION['flash']);
				unset($_SESSION['flash']);
				$_SESSION['success']['success'] = "Instructions were sent to you by email";
				die();
			}
			header('Location: ../Views/sign_in.php');
			exit();
		}
		else {
			$_SESSION['flash']['email'] = "Your account wasn't validated or you entered an invalid email";
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
