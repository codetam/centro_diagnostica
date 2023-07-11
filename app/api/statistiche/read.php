<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestStatistiche.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestStatistiche($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['num_esami'])){
            $rest->getNumPerTipologia($_GET['num_esami']);
        }
		if(isset($_GET["num_utenti"])){
			$rest->getNumUtenti();
		}
		if(isset($_GET["num_sesso"])){
			$rest->getNumUtentiSesso();
		}
		if(isset($_GET["codice_utente"])){
			$rest->getNumEsamiTerminatiUtente($_GET["codice_utente"]);
		}
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>