<?php

session_start();

$DB_DSN = "mysql:host=localhost";
$DB_USER = "root";
$DB_PASSWORD = "root";
$DB_NAME = "Camagru";

try {
	$db = new PDO($DB_DSN,$DB_USER,$DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NAMED);
	SetupDatabase($db, $DB_NAME);
	SetupGallery($db, $DB_NAME);
	SetupLikes($db, $DB_NAME);
	SetupComments($db, $DB_NAME);
}

catch(PDOException $e) {	
	echo $e->getMessage();
}

function SetupDatabase($db, $DB_NAME) {
	$db->exec("CREATE DATABASE IF NOT EXISTS {$DB_NAME};");
	$db->exec("USE {$DB_NAME};");
	$db->exec("CREATE TABLE IF NOT EXISTS users (
		user_id INT(10) PRIMARY KEY AUTO_INCREMENT,
		username VARCHAR(25) NOT NULL, 
		email VARCHAR(255) NOT NULL,
		password VARCHAR(255) NOT NULL,
		confirmation_token VARCHAR(60) DEFAULT NULL,
		confirmed_at DATETIME DEFAULT NULL,
		reset_token VARCHAR(60) DEFAULT NULL,
		reset_at DATETIME DEFAULT NULL);");
	$db->exec("USE {$DB_NAME};");
}

function SetupGallery($db, $DB_NAME) {
	$db->exec("USE {$DB_NAME};");
	$db->exec("CREATE TABLE IF NOT EXISTS gallery (
		pic_id INT(10) PRIMARY KEY AUTO_INCREMENT,
		username VARCHAR(25) NOT NULL, 
		user_id INT(10) NOT NULL, 
		link VARCHAR(255) NOT NULL,
		taken_on DATETIME DEFAULT NOW());");
	$db->exec("USE {$DB_NAME};");
}

function SetupComments($db, $DB_NAME) {
	$db->exec("USE {$DB_NAME};");
	$db->exec("CREATE TABLE IF NOT EXISTS comments (
		com_id INT(10) PRIMARY KEY AUTO_INCREMENT,
		pic_id INT(10) NOT NULL,
		username VARCHAR(25) NOT NULL, 
		user_id INT(10) NOT NULL, 
		comment VARCHAR(500) NOT NULL,
		posted_on DATETIME DEFAULT NOW());");
	$db->exec("USE {$DB_NAME};");
}

function SetupLikes($db, $DB_NAME) {
	$db->exec("USE {$DB_NAME};");
	$db->exec("CREATE TABLE IF NOT EXISTS likes (
		pic_id INT(10) NOT NULL,
		username VARCHAR(25) NOT NULL, 
		user_id INT(10) NOT NULL,
		liked_on DATETIME DEFAULT NOW());");
	$db->exec("USE {$DB_NAME};");
}

function isAjax() {
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return (TRUE);
	}
	else {
		return (FALSE);
	}
}

?>