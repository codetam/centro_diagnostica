<?php
    include_once("additional_pages/header.php");
?>
<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row justify-content-center align-items-center h-100">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <?php
              if(isset($_GET["error"])){
                echo '<h5 class="mb-4 text-danger">' . $_GET["error"] . '</h5>';
              }
            ?>
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registrazione</h3>

            <form action="src/check_signup.php" method="post">
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="form-outline">
                    <label class="form-label" for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control form-control-lg" placeholder="Nome" required/>
                  </div>
                </div>
                <div class="col-md-6 mb-2">
                  <div class="form-outline">
                    <label class="form-label" for="cognome">Cognome</label>
                    <input type="text" id="cognome" name="cognome" class="form-control form-control-lg" placeholder="Cognome" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="form-outline">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email" required/>
                  </div>
                </div>
                <div class="col-md-6 mb-2">
                  <div class="form-outline">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="Password" required/>
                  </div>
                </div>
              </div>
              <div class="row">
              <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="telefono">Telefono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control form-control-lg" placeholder="Telefono" required/>
                  </div>
                </div>
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="codice_fiscale">Codice fiscale</label>
                    <input type="text" id="codice_fiscale" name="codice_fiscale" class="form-control form-control-lg" placeholder="Codice fiscale" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2">
                  <h6 class="mb-2 pb-1">Sesso: </h6>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sesso" id="maschio" value="M" checked required/>
                    <label class="form-check-label" for="maschio">Maschio</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="sesso" id="femmina" value="F" required/>
                    <label class="form-check-label" for="femmina">Femmina</label>
                  </div>
                </div>
                <div class="col-md-6 mb-2 d-flex align-items-center">
                  <div class="form-outline datepicker w-100">
                    <label for="data_nascita" class="form-label">Data di nascita (gg/mm/aa)</label>
                    <input type="text" class="form-control form-control-lg" id="data_nascita" name="data_nascita" placeholder="GG/MM/AA" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="citta_nascita">Città (nascita)</label>
                    <input type="text" id="citta_nascita" name="citta_nascita" class="form-control form-control-lg" placeholder="Città (nascita)" required/>
                  </div>
                </div>
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="provincia_nascita">Provincia (nascita)</label>
                    <input type="text" id="provincia_nascita" name="provincia_nascita" class="form-control form-control-lg" placeholder="Provincia (nascita)" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="citta_residenza">Città (residenza)</label>
                    <input type="text" id="citta_residenza" name="citta_residenza" class="form-control form-control-lg" placeholder="Città (residenza)" required/>
                  </div>
                </div>
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="provincia_residenza">Provincia (residenza)</label>
                    <input type="text" id="provincia_residenza" name="provincia_residenza" class="form-control form-control-lg" placeholder="Provincia (nascita)" required/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="via_residenza">Via (residenza)</label>
                    <input type="text" id="via_residenza" name="via_residenza" class="form-control form-control-lg" placeholder="Via" required/>
                  </div>
                </div>
                <div class="col-md-6 mb-2 ">
                  <div class="form-outline">
                    <label class="form-label" for="numero_residenza">Numero civico (residenza)</label>
                    <input type="number" id="numero_residenza" name="numero_residenza" class="form-control form-control-lg" placeholder="Numero civico" required/>
                  </div>
                </div>
              </div>
              <div class="mt-4 pt-2">
                <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">Registrati</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>

<script>
  $(function() {
        $( "#data_nascita" ).datepicker({
           dateFormat:"dd/mm/yy",
        });
    });
</script>