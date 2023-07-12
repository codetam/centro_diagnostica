<?php 
    include_once("additional_pages/header.php"); 
?>
        <main>
            <section class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center text-white fs-1">
                <h1 style="font-size: 1.5em">Centro di diagnostica</h1>
                <h3 style="font-size: 0.8em">A un passo da te.</h3>
                <form action="src/check_prenota_homepage.php">
                    <button type="sumbit" class="btn btn-primary btn-lg mt-5">Prenota ora</button>
                </form>
            </section>
        </main>
    </body>
</html> 

<style>
body{
  background: url('../images/bg.jpg') rgba(22,39,128,0.8);
  background-blend-mode: multiply;
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
}
</style>