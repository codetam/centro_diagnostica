<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestUtente.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestUtente($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['codice_fiscale'])){
            $rest->deleteUtente($_GET['codice_fiscale']);
        }
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>