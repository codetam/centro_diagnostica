<?php

session_start();

if(isset($_SESSION["codice_fiscale"])){
    header("location: ../prenota.php");
    exit();
}
else if(isset($_SESSION["id_operatore"])){
    session_unset();
    session_destroy();
    header("location: ../login.php");
    exit();
}
else{
    header("location: ../login.php");
    exit();
}