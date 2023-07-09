<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestOperatore.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestOperatore($database->getConnection());
switch($requestMethod) {
	case 'POST':
        if(isset($_POST["id"]) && isset($_POST["nome"]) && isset($_POST["cognome"]) 
                && isset($_POST["email"]) && isset($_POST["password"]) ){
            $rest->updateOperatore($_POST);
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>