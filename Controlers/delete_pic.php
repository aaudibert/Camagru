<?php 

session_start();

require_once('../Config/setup.php');

if ($_POST['img'] && $_SESSION['auth']) {
	$img = $_POST['img'];
	$req = $db->prepare("SELECT * FROM gallery WHERE pic_id = ?");
	$req->execute(array((int)$img));
	$tmp = $req->fetchAll();
	if (count($tmp) == 1) {
		$db->prepare("DELETE FROM gallery WHERE pic_id = ?")->execute(array($img));
	}

}
else {
	header('Location: ../Views/home.php');
}

 ?>