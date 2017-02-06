<?php 
	session_start();
	
	if (!$_SESSION['auth'] || !isset($_SESSION['auth'])) {
		header('Location: ../Views/sign_in.php?redirect=unsigned');
		exit();
	}
	
	require('../Views/header.php');
?>

	<form method="POST" action="../Controlers/account.php">
		<input type="password" name="password" placeholder="password" required=""><br>
		<input type="password" name="rpassword" placeholder="confirm password" required=""><br>
		<button type="submit" class="button" name="btn-pwd"> Change password </button>
	</form>

<?php require '../Views/footer.php' ?>