<?php 

session_start();
require_once '../Config/setup.php';

if ($_SESSION['auth'] && isset($_SESSION['auth'])) {
	header('Location: ../Views/home.php?redirect=logged');
	exit();
}

require '../Views/header.php';

?>

	<form method="POST" action="../Controlers/forgot.php" class="form" id="forgot">
		<div><input type="email" name="email" placeholder="email adress" onfocus="this.placeholder=''" onblur="this.placeholder='email adress'" required><br></div>
		<input type="submit" class="button" name="btn-forgot" value="Get a new password"></input>
	</form>

<?php require '../Views/footer.php' ?>