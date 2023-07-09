<section style="background-color: rgba(0,0,0,0.5);">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
          <?php
            echo '<h2>Profilo - ' . $_SESSION["codice_fiscale"] . '</h2>';
            // Prende i dati da una chiamata all'API rest via codice fiscale
            $json = file_get_contents('http://localhost/api/utente/read/all/' . $_SESSION["codice_fiscale"]);
            $utente = json_decode($json);
            $json = file_get_contents('http://localhost/api/esame/read/utente/' . $_SESSION["codice_fiscale"]);
            $esami = json_decode($json);
          ?>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <!-- Informazioni generali -->
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3">
                <?php
                    echo $utente->nome;
                ?>
            </h5>
            <p class="text-muted mb-4">
              <?php
                echo $utente->citta_residenza . ", " . $utente->provincia_residenza;
              ?>
            </p>
            <div class="d-flex justify-content-center mb-2">
              <form action="prenota.php">
                <button type="submit" class="btn btn-primary">Prenota</button>
              </form>
              <button type="button" class="btn btn-outline-primary ms-1" data-bs-toggle="modal" data-bs-target="#visualizza_referto">Visualizza Referto</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <!-- Informazioni dettagliata -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nome</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">
                  <?php
                    echo $utente->nome . " " . $utente->cognome;
                  ?>
                </p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">
                  <?php
                    echo $utente->email;
                  ?>
                </p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Telefono</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">
                  <?php
                    echo $utente->telefono;
                  ?>
                </p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Indirizzo</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">
                  <?php
                    echo $utente->via . " " . $utente->numero . 
                    ", " . $utente->citta_residenza . ", " . $utente->provincia_residenza;
                  ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>    
    </div>
    <div class="row">
    <!-- Lista esami -->
    <div class="card mb-4">
            <div class="card-body text-center">
              <h5 class="my-3">Esami</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-2">
                  <p class="mb-0"><b>Data e ora</b></p>
                </div>
                <div class="col-sm-2">
                  <p class="mb-0"><b>Tipologia</b></p>
                </div>
                <div class="col-sm-4">
                  <p class="mb-0"><b>Operatore</b></p>
                </div>
                <div class="col-sm-3">
                  <p class="mb-0"><b>Terminato</b></p>
                </div>
              </div>
              <hr>
            <?php 
              foreach($esami as $esame){ 
                $json_info_operatore = file_get_contents('http://localhost/api/operatore/read/' . $esame->id_operatore);
                $info_operatore = json_decode($json_info_operatore);
            ?>
              <div class="row">
                <div class="col-sm-2">
                  <p class="mb-0">
                    <?php echo "<b>" . $esame->data . "</b>" . str_repeat('&nbsp;', 5) .  substr($esame->ora, 0, 5); ?> 
                  </p>
                </div>
                <div class="col-sm-2">
                  <p class="mb-0">
                    <?php echo ucfirst($esame->tipologia); ?>
                  </p>
                </div>
                <div class="col-sm-4">
                  <p class="mb-0">
                    <?php echo $info_operatore->nome . " " . $info_operatore->cognome; ?>
                  </p>
                </div>
                <div class="col-sm-3">
                  <p class="mb-0">
                    <?php 
                      if($esame->terminato){
                        echo "Si";
                      }
                      else {
                        echo "No";
                      }
                    ?>
                  </p>
                </div>
                <div class="col-sm-1">
                  <p class="mb-0">
                    <?php 
                      if($esame->terminato){
                        echo '<button onclick="saveUuidVariable(\'' . $esame->id . '\')" type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#codice_referto">Referto</button>'; 
                      }
                    ?>
                  </p>
                </div>
              </div>
              <hr>
            <?php 
              } 
            ?>
          </div>
        </div>
    </div>
  </div>
</section>

<script>
// Genera il codice univoco per accedere al referto
function saveUuidVariable(id){
  const spazio_codice = document.getElementById('code');

  const params = new URLSearchParams();
  params.append('id_esame', id);

  fetch('http://localhost/api/codicetemp/create', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: params
  })
  .then(response => response.json())
  .then(data => {
    if (data.state === 1) {
      fetch('http://localhost/api/codicetemp/read/' + id)
      .then(response => response.json())
      .then(data => {
        spazio_codice.innerHTML = data.codice_univoco;
      })
      .catch(error => {
        console.error('Error:', error);
      });
    } 
    else {
      console.error('POST request failed:', data.message);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}
</script>
