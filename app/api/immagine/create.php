<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestImmagine.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestImmagine($database->getConnection());
switch($requestMethod) {
	case 'POST':
		if(isset($_POST["id_esame"]) && isset($_POST["contenuto"]) && isset($_POST["nome"])){
			$rest->createImmagine($_POST);
		}
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>