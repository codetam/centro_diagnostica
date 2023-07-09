<?php
session_start();
include_once("../src/functions.php");

if (isset($_GET['data_scelta'])) {
    $data_scelta = $_GET['data_scelta'];
    getPrenotazioni($data_scelta, $_SESSION["id_operatore"]);
}
?>