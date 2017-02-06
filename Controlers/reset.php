<?php 
	session_start();
	
	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.php?redirect=logged');
		exit();
	}
	
	if (isset($_GET['id']) && isset($_GET['token'])) {
		
		$id = $_GET['id'];
		$token = $_GET['token'];
		
		require_once '../Config/setup.php';
		
		$req = $db->prepare('SELECT * FROM users WHERE user_id = ? AND reset_token = ? and reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
		$req->execute(array($id, $token));
		$user = $req->fetch();
		
		if ($user) {
			if (!empty($_POST)) {
				if (!empty($_POST['password']) && !strcmp($_POST['password'], $_POST['rpassword'])) {
					$db->prepare('UPDATE users SET password = ? WHERE user_id = ?')->execute(array(hash('whirlpool', $_POST['password']), $id));
					$_SESSION['flash']['success'] = 'Your password was successfully changed, welcome back!';		
					$_SESSION['auth'] = $user;
					header('Location: ../views/home.php');
					exit();
				}
				else
					$_SESSION['flash']['alert'] = "The passwords you entered didn't match";
			}
		}
		else {
			$_SESSION['flash']['alert'] = "This token isn't valid anymore";
			header('Location: ../views/sign_in.php');
			exit();
		}
	}
	else {
		header('Location ../Views/sign_in.php');
		exit();
	}

	require '../Views/header.php';
 ?>

	 <form method="POST">
		<input type="password" name="password" placeholder="new password" required=""><br>
		<input type="password" name="rpassword" placeholder="confirm new password" required=""><br>
		<button type="submit" class="button" name="btn-pwd"> Change password </button>
	</form>

<?php require '../Views/footer.php' ?>
