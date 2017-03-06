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
			echo '<a href="../Views/account.php" title="Change your password here">'.$_SESSION['auth']['username'].'</a>';
	}
	function home_button() {
		if ($_SESSION['auth'] && isset($_SESSION['auth']))
			echo '<a href="../Views/home.php">CAMAGRU</a>';
		else
			echo '<a href="../Views/sign_in.php?redirect=unsigned">CAMAGRU</a>';
	}

	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		die($content);
	}
 ?>

 <!DOCTYPE html>
 <html>
	 <head>
	 	<meta charset="utf-8" />
		<link rel="stylesheet" href="../Css/header.css" />
		<link rel="stylesheet" href="../Css/gallery.css" />
		<link rel="stylesheet" href="../Css/home.css" />
		<link rel="stylesheet" href="../Css/sign_in.css" />
		<link href="https://fonts.googleapis.com/css?family=Exo+2|Fredericka+the+Great|Josefin+Sans|Jura|Pompiere|Roboto+Condensed|Sue+Ellen+Francisco" rel="stylesheet">
	 	<title>Camagru</title>
	 </head>
	 <body>
	 	<ul class="header">
			<li class="eheader" id="header"  ><?php home_button(); ?></li>
			<li class="eheader" ><a href="../Views/gallery.php">Gallery</a></li>
			<li class="eheader" ><?php act_button(); ?></li>
			<li class="eheader" ><?php login_button(); ?></li>
		</ul>
		<div class="content">
		<?php if (isset($_SESSION['flash'])): ?>
			<?php foreach($_SESSION['flash'] as $type => $msg): ?>
				<?php echo "<p class='error_txt flash'>".$msg."</p><br>"; ?>
			<?php endforeach; ?>
		<?php unset($_SESSION['flash']); ?>
		<?php endif; ?>
		<?php if (isset($_SESSION['success'])): ?>
			<?php foreach($_SESSION['success'] as $type => $msg): ?>
				<?php echo "<p class='error_txt flash success'>".$msg."</p><br>"; ?>
			<?php endforeach; ?>
		<?php unset($_SESSION['success']); ?>
		<?php endif; ?>