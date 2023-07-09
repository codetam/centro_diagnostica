<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestLuogo.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestLuogo($database->getConnection());
switch($requestMethod) {
	case 'POST':
        if(isset($_POST["id"]) && isset($_POST["citta"]) && isset($_POST["provincia"])){
            $rest->updateLuogo($_POST);
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>