<?php
	session_start();

	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.php?redirect=logged');
		exit();
	}

	require '../Views/header.php';
?>

	<form method="POST" action="../Controlers/sign_in.php">
		<input type="text" name="uname" placeholder="username or email" required><br>
		<input type="password" name="password" placeholder="password" required=""><br>
		<a href="../Controlers/forgot.php">Forgot your password ?</a><br>
		<button type="submit" class="button" name="btn-login"> Sign in </button>
	</form>
	<br>
	<form method="POST" action="../Controlers/sign_up.php">
		<input type="text" name="uname" placeholder="username" required><br>
		<input type="text" name="email" placeholder="email adress" required><br>
		<input type="password" name="password" placeholder="password" required=""><br>
		<input type="password" name="rpassword" placeholder="confirm password" required=""><br>
		<button type="submit" class="button" name="btn-signup"> Sign up </button>
	</form>

<?php require '../Views/footer.php' ?>