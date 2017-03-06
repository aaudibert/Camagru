<?php
	session_start();

	if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
		header('Location: ../Views/home.php?redirect=logged');
		exit();
	}

	require '../Views/header.php';
?>

	<form method="POST" action="../Controlers/sign_in.php" class="form" id="f1">
		<div><input type="text" name="in_uname" placeholder="username or email" onfocus="this.placeholder=''" onblur="this.placeholder='username or email'" required><br></div>
		<div><input type="password" name="in_password" placeholder="password" onfocus="this.placeholder=''" onblur="this.placeholder='password'" required><br></div>
		<input type="submit" class="button" name="btn-login" value="Sign in"><br>
		<label><a class="forgot" href="../Views/forgot.php">Forgot your password ?</a></label>
	</form>
	<form method="POST" action="../Controlers/sign_up.php" class="form">
		<div><input type="text" name="uname" placeholder="username" onfocus="this.placeholder=''" onblur="this.placeholder='username'" required><br></div>
		<div><input type="email" name="email" placeholder="email adress" onfocus="this.placeholder=''" onblur="this.placeholder='email adress'" required><br></div>
		<div><input type="password" name="password" placeholder="password" onfocus="this.placeholder=''" onblur="this.placeholder='password'" required><br></div>
		<div><input type="password" name="rpassword" placeholder="confirm password" onfocus="this.placeholder=''" onblur="this.placeholder='confirm password'" required><br></div>
		<input type="submit" class="button" name="btn-signup" value="Sign up" >
	</form>

<?php require '../Views/footer.php' ?>