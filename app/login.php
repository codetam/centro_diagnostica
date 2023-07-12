<?php
    include_once("additional_pages/header.php");
?>
<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="images/lab_vertical.jpg"
                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="src/check_login.php" method="post">
                <?php
                    if(isset($_GET["error"]) && $_GET["error"]=="invalid_credentials"){
                        echo '<h5 class="text-danger">Credenziali errate!</h5>';
                    }
                ?>
                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h2 mb-0">
                    <img src="images/logo.png" alt="Logo" style="max-height: 40px; max-width: 40px; margin-right: 5px;">
                      Centro di diagnostica</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Accedi al tuo account</h5>

                  <div class="col-md-12 mb-2">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="tipo" id="utente" value="utente" checked required/>
                      <label class="form-check-label" for="utente">Utente</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="tipo" id="operatore" value="operatore" required/>
                      <label class="form-check-label" for="operatore">Operatore</label>
                    </div>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control form-control-lg" />
                    <label class="form-label" for="email">Email</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control form-control-lg" />
                    <label class="form-label" for="password">Password</label>
                  </div>

                  <div class="pt-1 mb-4">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Accedi</button>
                  </div>
                  <p class="mb-5 pb-lg-2" style="color: #393f81;">Non hai un account? <a href="signup.php"
                      style="color: #393f81;">Registrati</a></p>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html> 
