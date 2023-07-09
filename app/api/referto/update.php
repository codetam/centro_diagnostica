<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestReferto.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestReferto($database->getConnection());
switch($requestMethod) {
	case 'POST':
        if(isset($_POST["id_esame"]) && isset($_POST["testo"])){
            $rest->updateReferto($_POST);
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>