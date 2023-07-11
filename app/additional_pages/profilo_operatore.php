<section style="background-color: rgba(0,0,0,0.5);">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
          <?php
            echo '<h2>Profilo - Operatore n. ' . $_SESSION["id_operatore"] . '</h2>';
            // Prende i dati da una chiamata all'API rest via codice fiscale
            $json = file_get_contents('http://localhost/api/operatore/read/' . $_SESSION["id_operatore"]);
            $operatore = json_decode($json);
          ?>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <h5 class="my-3">
                <?php
                    echo $operatore->nome;
                ?>
            </h5>
            <div class="d-flex justify-content-center mb-2">
              <form action="statistiche.php">
                <button type="submit" class="btn btn-primary">Statistiche</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nome</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">
                  <?php
                    echo $operatore->nome . " " . $operatore->cognome;
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
                    echo $operatore->email;
                  ?>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-2 mt-2">
                <h5>Prenotazioni</h5>
              </div>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-lg" id="data_prenotazioni" name="data_prenotazioni" placeholder="GG/MM/AA" required/>
              </div>
            </div>
            <hr>
            <div class="row" id="lista_prenotazioni">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Calendario per la selezione della data nella zona prenotazioni
  $(function() {
      $( "#data_prenotazioni" ).datepicker({
          dateFormat:"dd/mm/yy",
          onSelect: function(dateText, inst) {
          const lista_prenotazioni = document.getElementById('lista_prenotazioni');
          if (dateText !== '') {
            let formattedDate = formatDate(dateText);
            // Make an AJAX request to fetch the updated options
            $.ajax({
                    url: 'api/get_prenotazioni.php',
                    method: 'GET',
                    data: { data_scelta: formattedDate },
                    success: function(response) {
                        // Update the options of the orario select element
                        console.log(response);
                        lista_prenotazioni.innerHTML = response;
                    }
                });
          }
        }
      });
  });

// Cambio del format della data
function formatDate(dateText) {
  let parts = dateText.split('/');
  let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
  return formattedDate;
}
</script>