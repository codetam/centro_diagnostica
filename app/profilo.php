<?php
    include_once("additional_pages/header.php");
    include_once("modals/visualizza_referto.php");
    include_once("modals/codice_referto.php");
    
    if(isset($_SESSION["codice_fiscale"])){
      include_once("additional_pages/profilo_utente.php");
    } 
    else if(isset($_SESSION["id_operatore"])){
      include_once("additional_pages/profilo_operatore.php");
    } 
    else {
?>
      <main>
            <section class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center text-white fs-1">
                <h1 style="font-size: 1.5em">Devi accedere per poter visualizzare questa pagina!</h1>
            </section>
        </main>
<?php
    }
?>
</body>
</html>