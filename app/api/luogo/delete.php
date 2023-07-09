<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include_once('RestLuogo.php');
include_once('../../src/Database.php');
$database = new Database();
$rest = new RestLuogo($database->getConnection());
switch($requestMethod) {
	case 'GET':
        if(isset($_GET['id'])){
            $rest->deleteLuogo($_GET['id']);
        }
		break;
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}
?>