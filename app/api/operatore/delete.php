<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestOperatore.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestOperatore($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id'])){
            $rest->deleteOperatore($_GET['id']);
        }
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>