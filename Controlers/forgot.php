<?php 
	session_start();
	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.php?redirect=logged');
		exit();
	}
	
	require_once '../Config/setup.php';
	
	function str_random($len) {
		$alpha = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		return substr(str_shuffle(str_repeat($alpha, $len)), 0, $len);
	}

	if (isset($_POST['btn-forgot']) && !empty($_POST)) {
		$email = htmlentities($_POST['email']);
		
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
			$$_SESSION['flash']['error'] = "The email you entered is either in the wrong format or unsupported by our site, please use a different email adress";
		$req = $db->prepare("SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL");
		$req->execute(array($email));
		$usr = $req->fetch();
		
		if ($usr && $usr['confirmed_at'] != NULL) {
			$reset_token = str_random(60);
			$_SESSION['flash']['success'] = "Instructions were sent to you by email";
			$db->prepare("UPDATE users SET reset_token = ?, reset_at = NOW() WHERE user_id = ?")->execute(array($reset_token, $usr['user_id']));
			mail($email, "Forgotten password", "To get a new password, please click on the following link within 30 minutes\n\nhttp://localhost:8080/camagru/Controlers/reset.php?id=".$usr['user_id']."&token=".$reset_token);
			header('Location: ../Views/sign_in.php');
			exit();
		}
		else {
			$_SESSION['flash']['error'] = "Your account wasn't validated or you entered an invalid email";
			header('Location: ../Views/sign_in.php');
			exit();
		}
	}
	require '../Views/header.php';
?>
	<form method="POST">
		<input type="emil" name="email" placeholder="email adress" required><br>
		<button type="submit" class="button" name="btn-forgot"> Get new password </button>
	</form>

<?php require '../Views/footer.php' ?>
