<?php 
	session_start();
	
	function login_button() {
		if ($_SESSION['auth'] && isset($_SESSION['auth']))
			echo '<a href="../Controlers/logout.php">Logout</a>';
		else
			echo '<a href="../Views/sign_in.php">Login</a>';
	}
	function act_button() {
		if ($_SESSION['auth'] && isset($_SESSION['auth']))
			echo '<a href="../Views/account.php">Account</a>';
	}
	function home_button() {
		if ($_SESSION['auth'] && isset($_SESSION['auth']))
			echo '<a href="../Views/home.php">Home</a>';
		else
			echo '<a href="../Views/sign_in.php?redirect=unsigned">Home</a>';
	}

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		die($content);
	}
 ?>

 <!DOCTYPE html>
 <html>
	 <head>
	 	<meta charset="utf-8" />
		<script src="../Js/script.js"></script>
		<link rel="stylesheet" href="../Css/header.css" />
	 	<title>Camagru</title>
	 </head>
	 <body>
	 	<ul class="header">
			<li class="eheader" id="header"  ><?php home_button(); ?></li>
			<li class="eheader righth" ><?php login_button(); ?></a></li>
			<li class="eheader righth" ><?php act_button(); ?></a></li>
			<li class="eheader righth" ><a href="../Views/gallery.php">Gallery</a></li>
		</ul>
		<div class="content">
		<?php if (isset($_SESSION['flash'])): ?>
			<?php foreach($_SESSION['flash'] as $type => $msg): ?>
				<?php echo $msg."<br>"; ?>
			<?php endforeach; ?>
		<?php unset($_SESSION['flash']); ?>
		<?php endif; ?>