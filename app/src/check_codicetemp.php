<?php

include_once('Database.php');
$database = new Database();

// Elimina le righe aggiunte più di 5 minuti fa
$sql = "DELETE FROM CodiciTemporanei WHERE data_creazione <= (NOW() - INTERVAL 5 MINUTE)";
$result = $database->getConnection()->query($sql);

$database->getConnection()->close();
?>