<?php

// Verifica che tutto sia corretto e in caso contrario invia errori alla pagina precedente tramite GET
if(isset($_POST["submit"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $tipo = $_POST["tipo"];
    
    require_once "Database.php";
    require_once "functions.php";
    $database = new Database();
    $conn = $database->getConnection();

    if($tipo == "utente"){
        // Login dell'utente e check della password
        if(loginUser($conn,$email,$password) === false){
            header("location: ../login.php?error=invalid_credentials");
            exit();
        }
        $row = utenteEmailExists($conn, $email);
        // Inizio della sessione
        session_start();
        $_SESSION["codice_fiscale"] = $row["codice_fiscale"];
    }
    else if($tipo == "operatore"){
        // Login dell'operatore e check della password
        if(loginOperatore($conn,$email,$password) === false){
            header("location: ../login.php?error=invalid_credentials");
            exit();
        }
        $row = operatoreEmailExists($conn, $email);
        // Inizio della sessione
        session_start();
        $_SESSION["id_operatore"] = $row["id"];
    }
    else{
        header("location: ../login.php");
        exit();
    }
    
    header("location: ../profilo.php");
    exit();
}
else {
    header("location: ../login.php");
    exit();
}