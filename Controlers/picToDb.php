<?php

session_start();

require_once('../Config/setup.php');

if ($_POST['filter'] && $_POST['img'] && $_SESSION['auth']) {
	$img = $_POST['img'];
	$sticker = $_POST['filter'];
	$target_file = $target_dir . basename($_FILES["data"]["name"]);

    if ($_FILES['data']) {
	    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["data"]["tmp_name"]);

        if($check !== false) {
            $uploadOk = 1;
        }
        else {
            $_SESSION['flash']['format'] = "The file you uploaded is not an image, try using another one";
            $uploadOk = 0;
        }

	    if ($imageFileType != "png") {
	        $_SESSION['flash']['type'] = "Sorry, only PNG files are allowed";
	        $uploadOk = 0;
	    }

	    if ($uploadOk == 0) {
			if (isAjax()) {
				http_response_code(400);
				echo json_encode($_SESSION['flash']);
				die();
			}
			header('Location: ../Views/home.php');
	    }
	}
	
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$today = date("Y-m-d@H:i:s");
	$link = "../pic/"."{$_SESSION['auth']['username']}"."@"."{$today}".".png";
	file_put_contents($link, $data);
	
	$source = imagecreatefrompng($sticker);
	$largeur_source = imagesx($source);
	$hauteur_source = imagesy($source);
	imagealphablending($source, true);
	imagesavealpha($source, true);
	
	if ($destination = imagecreatefrompng($link))
	{
		$largeur_destination = imagesx($destination);
		$hauteur_destination = imagesy($destination);
		$destination_x = ($largeur_destination - $largeur_source) / 2;
		$destination_y = ($hauteur_destination - $hauteur_source) / 2;
		imagecopy($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source);
		imagepng($destination, $link);
		imagedestroy($destination);
		imagedestroy($source);
		$req = $db->prepare("INSERT INTO gallery SET user_id = ?, username = ?, link = ?, taken_on = NOW()");
		$req->execute(array($_SESSION['auth']['user_id'], $_SESSION['auth']['username'], $link));
		if (isAjax()) {
			$id = $db->lastInsertId('pic_id');
			$ret = array('id' => $id, 'link' => $link);
			echo json_encode($ret);
			die();
		}
		header('Location: ../Views/home.php');
	}
	else {
		$_SESSION['flash']['error'] = "Something went wrong trying to take your picture, try again!";
		if (isAjax()) {
			http_response_code(400);
			echo json_encode($_SESSION['flash']);
			die();
		}
		header('Location: ../Views/home.php');
	}
}

else {
	if (isAjax()) {
		http_response_code(400);
		die();
	}
	header('Location: ../Views/home.php');
}
?>