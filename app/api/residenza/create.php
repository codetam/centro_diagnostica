<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestResidenza.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestResidenza($database->getConnection());
switch($requestMethod) {
	case 'POST':
		if(isset($_POST["codice_utente"]) && isset($_POST["id_luogo"]) && isset($_POST["via"]) && isset($_POST["numero"]) ){
			$rest->createResidenza($_POST);
		}
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>