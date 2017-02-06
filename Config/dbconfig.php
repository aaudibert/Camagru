<?php

session_start();

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "root";
$DB_name = "dblogin";

try {
	$DB = new PDO("mysql:host={$DB_host}",$DB_user, $DB_pass);
	$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}

catch(PDOException $e) {
	echo $e->getMessage();
}

include_once '../Class/class.user.php';
$user = new USER($DB_con);

