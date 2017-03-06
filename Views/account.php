<?php 
	session_start();
	
	if (!$_SESSION['auth'] || !isset($_SESSION['auth'])) {
		header('Location: ../Views/sign_in.php?redirect=unsigned');
		exit();
	}
	
	require('../Views/header.php');
?>

	<form method="POST" action="../Controlers/account.php" class="form" id="account">
		<div><input type="password" name="password" placeholder="password" required=""><br></div>
		<div><input type="password" name="rpassword" placeholder="confirm password" required=""><br></div>
		<input type="submit" class="button" name="btn-pwd" value="Change password">
	</form>

<?php require '../Views/footer.php' ?>