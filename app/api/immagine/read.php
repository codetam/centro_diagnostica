<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestImmagine.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestImmagine($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id'])){
            $rest->getImmagine($_GET['id']);
        }
        else if(isset($_GET['id_esame'])){
            $rest->getImmaginiReferto($_GET['id_esame']);
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>