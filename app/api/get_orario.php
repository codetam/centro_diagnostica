<?php
include_once("../src/functions.php");

if (isset($_GET['data_scelta'])) {
    $data_scelta = $_GET['data_scelta'];
    getAvailableOrarioOptions($data_scelta);
}
?>