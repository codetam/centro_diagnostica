<?php

// Verifica che tutto sia corretto e in caso contrario invia errori alla pagina precedente tramite GET
if(isset($_POST["submit"])){
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $telefono = $_POST["telefono"];
    $codice_fiscale = $_POST["codice_fiscale"];
    $sesso = $_POST["sesso"];

    $data_nascita_str = $_POST["data_nascita"]; // La data è ricevuta come "dd/mm/yy"
    $data_formattata = str_replace('/', '-', $data_nascita_str);
    $data_nascita = date('Y-m-d', strtotime($data_formattata));

    $citta_nascita = $_POST["citta_nascita"];
    $provincia_nascita = $_POST["provincia_nascita"];
    $citta_residenza = $_POST["citta_residenza"];
    $provincia_residenza = $_POST["provincia_residenza"];
    $via_residenza = $_POST["via_residenza"];
    $numero_residenza = $_POST["numero_residenza"];

    require_once "functions.php";
    // Verifica che i campi non siano vuoti
    if (emptyInputSignup($nome, $cognome, $email, $password, 
                        $telefono, $codice_fiscale, $sesso, $data_nascita, 
                        $citta_nascita, $provincia_nascita, $citta_residenza, 
                        $provincia_residenza, $via_residenza, $numero_residenza ) !== false) {
        header("location: ../signup.php?error=Compilare%20tutti%20i%20campi!");
        exit();
    }
    // Crea un nuovo utente
    $json_response = createUser($nome, $cognome, $email, $password, 
                            $telefono, $codice_fiscale, $sesso, $data_nascita, 
                            $citta_nascita, $provincia_nascita, $citta_residenza, 
                            $provincia_residenza, $via_residenza, $numero_residenza );
    if( $json_response === false){
        header("location: ../signup.php?error=Errore%20durante%20la%20registrazione,%20riprovare");
        exit();
    }
    
    $response = json_decode($json_response, true);
    if($response["state"] == 0){
        header("location: ../signup.php?error=" . $response["message"]);
        exit();
    }
    
    session_start();
    $_SESSION["codice_fiscale"] = $codice_fiscale;
    header("location: ../profilo.php");
    exit();
}
else {
    header("location: ../signup.php");
    exit();
}