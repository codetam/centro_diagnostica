<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestResidenza.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestResidenza($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['codice_utente'])){
            $rest->deleteResidenza($_GET['codice_utente']);
        }
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>