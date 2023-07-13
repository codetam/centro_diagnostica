<?php

session_start();

// Verifica che tutto sia corretto e in caso contrario invia errori alla pagina precedente tramite GET
if(isset($_SESSION["id_operatore"]) && isset($_GET["id_esame"])){
    $json = file_get_contents('http://localhost/api/esame/delete/' . $_GET["id_esame"]);
    $esame_eliminato = json_decode($json);
    if($esame_eliminato->state == 1){
        header("location: ../additional_pages/esame_eliminato.php");
        exit();
    }
    else if($esame_eliminato->state == 0){
        header("location: ../profilo.php");
        exit();
    }
}
else {
    header("location: ../profilo.php");
    exit();
}