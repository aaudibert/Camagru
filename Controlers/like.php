<?php 
	require_once '../Config/setup.php';
	session_start();

if (isset($_POST) && isset($_POST['pic_id']) && isset($_SESSION)) {
	$user_id = $_SESSION['auth']['user_id'];
	$username = $_SESSION['auth']['username'];
	$pic_id = intval(str_replace("l", "", $_POST['pic_id']));
			
	$req = $db->prepare("SELECT * FROM gallery WHERE pic_id = ?");
	$req->execute(array($pic_id));
	$pic = $req->fetch();
	if (!$pic){
		$_SESSION['flash']['missing'] = "The picture you were trying to like was deleted";
		if (isAjax()) {
			http_response_code(400);
			echo json_encode($_SESSION['flash']);
			die();
		}
		header("Location: ../Views/gallery");
	}

	$req = $db->prepare("SELECT * FROM likes WHERE pic_id = ? AND user_id = ?");
	$req->execute(array($pic_id, $user_id));
	$like = $req->fetch();
	if ($like) {
		$db->prepare('DELETE FROM likes WHERE pic_id = ? AND user_id = ?')->execute(array($pic_id, $user_id));
		if (isAjax()) {
			$ret = array('unlike' => 'true');
			echo json_encode($ret);
			unset($_SESSION['flash']);
			die();
		}
	}
	else {
		$db->prepare("INSERT INTO likes SET pic_id = ?, user_id = ?, username = ?, liked_on = NOW()")->execute(array($pic_id, $user_id, $username));
		if (isAjax()) {
			$ret = array('like' => 'true');
			echo json_encode($ret);
			unset($_SESSION['flash']);
			die();
		}
	}
}
else {
	header("Location: ../Views/gallery.php");
	die();
}

?>