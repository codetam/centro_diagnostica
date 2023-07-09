<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestReferto.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestReferto($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id_esame'])){
            $rest->getReferto($_GET['id_esame']);
        }
		break;
	default:
	    header("HTTP/1.0 405 Method Not Allowed");
	    break;
}
?>