<?php
session_start();

/* Verifica che tutto sia corretto e in caso contrario invia errori alla pagina precedente tramite GET */
if(isset($_POST["submit"]) && isset($_SESSION["id_operatore"]) && isset($_POST["request"])){
    $id_esame = $_POST["id_esame"];
    $testo = $_POST["testo"];
    $request = $_POST["request"];
    
    require_once "functions.php";

    if(emptyInputReferto($id_esame, $testo)){
        printError($id_esame);
    }

    $json_response = scriviReferto($id_esame, $testo, $request);
    if( $json_response === false){
        printError($id_esame);
    }
    $response = json_decode($json_response, true);
    
    if(!isset($response["state"])){
        printError($id_esame);
    }

    if($response["state"] == 0){
        printError($id_esame);
    }
    $success_message = "";
    if($request == "crea"){
        $success_message = "Referto%20caricato%20con%20successo.";
    }
    elseif($request == "modifica"){
        $success_message = "Referto%20modificato%20con%20successo.";
    }
    header("location: ../esame.php?id_esame=" . $id_esame . "&success=" .  $success_message);
    exit();
}
else {
    header("location: ../esame.php?id_esame=" . $_POST["id_esame"] . "&error=Errore%20durante%20la%20creazione%20del%20referto.");
    exit();
}

function printError($id_esame){
    header("location: ../esame.php?id_esame=" . $id_esame . "&error=Errore%20durante%20la%20creazione%20del%20referto.");
    exit();
}