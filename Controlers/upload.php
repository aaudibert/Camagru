<?php

session_start();
require_once('../Config/setup.php');

if (!$_SESSION['auth'] || !isset($_SESSION['auth']))
    header('Location: ../Views/sign_in.php?redirect=unsigned');

if ($_POST['img'] && $_POST['filter'] && $_SESSION['auth']) {
    $today = date("Y-m-d@H:i:s");
    $target_file = "../upl/"."{$_SESSION['auth']['username']}"."@"."{$today}".".png";
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if(isset($_POST["submit"]) && $_POST && !empty($_POST['fileToUpload'])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['flash']['format'] = "File is not an image.";
            $uploadOk = 0;
        }
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $_SESSION['flash']['type'] = "Sorry, only JPG, JPEG & PNG files are allowed";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $_SESSION['flash']['error'] = "Sorry, your file was not uploaded";

    }
    else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $req = $db->prepare("INSERT INTO gallery SET user_id = ?, username = ?, link = ?, taken_on = NOW()");
            $req->execute(array($_SESSION['auth']['user_id'], $_SESSION['auth']['username'],$target_file));
            $_SESSION['flash']['success'] = "Your file has been uploaded";
            $_SESSION['upload'] = $target_file;
            header('Location: ../Views/home.php');
        }
        else {
            $_SESSION['flash']['error'] = "Sorry, there was an error uploading your file";
            header('Location: ../Views/home.php');
            exit();
        }
    }
}

?>