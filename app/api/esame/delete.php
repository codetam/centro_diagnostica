<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestEsame.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestEsame($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id'])){
            $rest->deleteEsame($_GET['id']);
        }
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>