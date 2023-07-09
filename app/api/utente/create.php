<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestUtente.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestUtente($database->getConnection());
switch($requestMethod) {
	case 'POST':	
		$rest->createUtente($_POST);
		break;
	default:
	header("HTTP/1.0 405 Method Not Allowed");
	break;
}
?>