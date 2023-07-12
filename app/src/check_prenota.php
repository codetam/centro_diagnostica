<?php

session_start();

/* Verifica che tutto sia corretto e in caso contrario invia errori alla pagina precedente tramite GET */
if(isset($_POST["submit"]) && isset($_SESSION["codice_fiscale"])){
    $tipologia = $_POST["tipologia"];
    $orario = $_POST["orario"];
    $codice_utente = $_SESSION["codice_fiscale"];
    // Si cerca il primo operatore che ha il numero di esami minore
    $json_operatori = file_get_contents('http://localhost/api/operatore/read');
    $obj_operatori = json_decode($json_operatori);

    $id_operatore = $obj_operatori[0]->id;
    $min = $obj_operatori[0]->num_esami;
    foreach($obj_operatori as $operatore){
        if($operatore->num_esami < $min){
            $id_operatore = $operatore->id;
            $min = $operatore->num_esami;
        }
    }
    
    $data_esame_str = $_POST["data_esame"]; // La data è ricevuta come "dd/mm/yy"
    $data_formattata = str_replace('/', '-', $data_esame_str);
    $data_esame = date('Y-m-d', strtotime($data_formattata));
    
    require_once "functions.php";

    $json_response = prenotaEsame($tipologia, $data_esame, $orario, $codice_utente, $id_operatore);
    if( $json_response === false){
        header("location: ../prenota.php?error=Errore%20durante%20la%20prenotazione");
        exit();
    }
    $response = json_decode($json_response, true);
    
    if($response["state"] == 0){
        header("location: ../signup.php?error=" . $response["message"]);
        exit();
    }
    header("location: ../additional_pages/prenotazione_successo.php");
    exit();
}
else {
    header("location: ../prenota.php");
    exit();
}