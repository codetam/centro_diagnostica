<?php
session_start();

/* Viene eliminata la sessione e l'utente è reindirizzato all'Explore */
session_unset();
session_destroy();

header("location: ../index.php");
exit();