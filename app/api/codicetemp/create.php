<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestCodice.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestCodice($database->getConnection());
switch($requestMethod) {
	case 'POST':
		if(isset($_POST["id_esame"])){
			$rest->createCodice($_POST);
		}
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>