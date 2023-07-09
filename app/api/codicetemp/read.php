<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestCodice.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestCodice($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id_esame'])){
            $rest->getCodice($_GET['id_esame']);
        }
		break;
	case 'POST':
		if(isset($_POST['codice_univoco'])){
            $rest->getCodiceCode($_POST['codice_univoco']);
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>