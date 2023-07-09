<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="../css/style.css">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    

    <!-- Jquery per il datepicker -->
    <link rel = "stylesheet" href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css">
    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <!-- Libreria Lightbox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <title>Centro di diagnostica</title>
</head>
<body class="vh-100 overflow-auto">
    <!-- Navbar di Bootstrap - OffCanvas -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fs-4" href="../">Centro di Diagnostica</a>
            <!-- Toggle Button -->
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Sidebar -->
            <div class="sidebar offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <!-- Sidebar Header -->
                <div class="offcanvas-header text-white border-bottom">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Sidebar Body -->
                <div class="offcanvas-body d-flex flex-column flex-lg-row p-4 p-lg-0">
                    <ul class="navbar-nav justify-content-center align-items-center fs-5 flex-grow-1 pe-3">
                    <li class="nav-item mx-2-">
                        <a class="nav-link" aria-current="page" href="../">Home</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="../">About</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="../">Services</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="../">Contact</a>
                    </li>
                    </ul>
                <?php
                    if(!isset($_SESSION["codice_fiscale"]) && !isset($_SESSION["id_operatore"])){
                ?>
                    <!-- Login / Signup -->
                    <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3">
                        <a href="../login.php" class="text-white text-decoration-none">Accedi</a>
                        <a href="../signup.php" class="text-white text-decoration-none px-3 py-1 rounded-4"
                        style="background-color: #f94ca4">Registrati</a>
                    </div>
                <?php
                    } else {
                ?>
                    <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3">
                        <a href="../profilo.php" class="text-white text-decoration-none">Profilo</a>
                        <a href="../src/check_logout.php" class="text-white text-decoration-none px-3 py-1 rounded-4"
                        style="background-color: #f94ca4">Log Out</a>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </nav>