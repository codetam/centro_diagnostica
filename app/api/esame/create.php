<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestEsame.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestEsame($database->getConnection());
switch($requestMethod) {
	case 'POST':
		if(isset($_POST["terminato"]) && isset($_POST["data"]) && isset($_POST["ora"]) 
			&& isset($_POST["codice_utente"]) && isset($_POST["id_operatore"]) && isset($_POST["tipologia"]) ){
			$rest->createEsame($_POST);
		}
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>