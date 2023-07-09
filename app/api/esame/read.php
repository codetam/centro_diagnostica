<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestEsame.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestEsame($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id'])){
            $rest->getEsame($_GET['id']);
        }
        else if(isset($_GET['data_esami'])){
            $rest->getEsamiData($_GET['data_esami']);
        }
        else if(isset($_GET['codice_fiscale'])){
            $rest->getEsamiUtente($_GET['codice_fiscale']);
        }
        else{
            $rest->getEsami();
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>