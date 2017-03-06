<?php 

	session_start();
	require '../Config/setup.php';
	
	if ($_POST && isset($_SESSION['auth']) && $_POST['comment'] && $_POST['pic_id']) {
		$com = htmlentities($_POST['comment']);
		$pic_id = intval(str_replace("c", "", $_POST['pic_id']));

		if (strlen($com) > 500 || strlen(trim($com)) == 0) {
			if (strlen($com) > 0) {
				$_SESSION['flash']['error'] = "Your comment must be less than 500 characters, and shouldn't only contain spaces";
			}
			if (isAjax()) {
				http_response_code(400);
				echo json_encode($_SESSION['flash']);
				unset($_SESSION['flash']);
				die();
			}
			header("Location: ../Views/gallery");
		}

		$com = trim($com);
		$db->prepare("INSERT INTO comments SET comment = ?, pic_id = ?, user_id = ?, username = ?")->execute(array($com, $pic_id, $_SESSION['auth']['user_id'], $_SESSION['auth']['username']));
		$req = $db->prepare("SELECT user_id FROM gallery WHERE pic_id = ?");
		$req->execute(array($pic_id));
		$usr_to_mail = $req->fetch();
		
		if ($usr_to_mail) {
			if ($usr_to_mail['user_id'] != $_SESSION['auth']['user_id']) {
				$req = $db->prepare("SELECT email FROM users WHERE user_id = ?");
				$req->execute(array($usr_to_mail['user_id']));
				$email = $req->fetch();
				$mail = $email['email'];
				mail($mail, "Camagru - picture comment", "Someone commented on one of your pictures, go see what they said about it !\nhttp://localhost:8080/camagru/Views/gallery.php?p=".$_POST['page']."#".$pic_id);
			}
		}
		else {
			$_SESSION['flash']['missing'] = "The picture you were trying to comment was deleted";
			if (isAjax()) {
				http_response_code(400);
				echo json_encode($_SESSION['flash']);
				die();
			}
			header("Location: ../Views/gallery");
		}
		
		if (isAjax()) {
			$ret = array("com" => $com, "pic_id" => $pic_id, "username" => $_SESSION['auth']['username']);
			echo json_encode($ret);
			die();
		}
	}
	else {
		$_SESSION['flash']['error'] = "You must be logged in to comment";
		if (isAjax()) {
			http_response_code(400);
			echo json_encode($_SESSION['flash']);
			unset($_SESSION['flash']);
			die();
		}
		header("Location: ../Views/gallery");
	}
 ?>