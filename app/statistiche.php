<?php
    include_once("additional_pages/header.php");
    include_once("src/functions.php");
    // Viene controllato se l'operatore è loggato e se è stata fatta una richiesta GET con l'ID dell'esame
    if(!isset($_SESSION["id_operatore"])){
?>

  <main>
    <section class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center text-white fs-1">
      <h1 style="font-size: 1.5em">Devi accedere per poter visualizzare questa pagina!</h1>
    </section>
  </main>
  </body>
  </html>

<?php
      exit();
    }
    $json = file_get_contents('http://localhost/api/statistiche/read/num_esami');
    $num_esami = json_decode($json);
    $json = file_get_contents('http://localhost/api/statistiche/read/num_sesso');
    $info_utenti = json_decode($json);

    $num_prenotati = 0;
    $num_terminati = 0;
    foreach($num_esami as $esame){ 
        $num_prenotati += $esame->num_non_terminati;
        $num_terminati += $esame->num_terminati;
    }
?>
<section>
    <h3 class="text-center mt-4 mb-4 text-light">Statistiche</h3>
<div class="row">
    <div class="col-1"></div>
    <div class="col-lg-6">
    <!-- Numero esami per tipologia -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h5 class="my-8">Numero esami per tipologia</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <p class="mb-0"><b>Tipologia</b></p>
                </div>
                <div class="col-sm-3">
                  <p class="mb-0"><b>Prenotati</b></p>
                </div>
                <div class="col-sm-3">
                  <p class="mb-0"><b>Terminati</b></p>
                </div>
              </div>
              <hr>
            <?php 
              foreach($num_esami as $esame){ 
            ?>
            <div class="row">
                <div class="col-sm-6">
                  <p class="mb-0">
                    <?php echo ucfirst($esame->tipologia); ?> 
                  </p>
                </div>
                <div class="col-sm-3">
                  <p class="mb-0">
                    <?php echo $esame->num_non_terminati; ?>
                  </p>
                </div>
                <div class="col-sm-3">
                  <p class="mb-0">
                    <?php echo $esame->num_terminati; ?>
                  </p>
                </div>
            </div>
            <hr>
            <?php 
              } 
            ?>
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4">
                    <canvas id="chart_esami"></canvas>
                </div>
                <div class="col-sm-4"></div>
            </div>
          </div>
        </div>
    </div>
    <div class="col-lg-4">
    <!-- Informazioni sugli utenti -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <h5 class="my-8">Info generali sugli utenti</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-3">
                        <p class="mb-0"><b>Donne</b></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><b>Uomini</b></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-0"><b>Numero utenti</b></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><?php echo $info_utenti[0]->num_utenti; ?></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><?php echo $info_utenti[1]->num_utenti; ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-0"><b>Esami prenotati</b></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><?php echo $info_utenti[0]->num_esami_non_terminati; ?></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><?php echo $info_utenti[1]->num_esami_non_terminati; ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-0"><b>Esami terminati</b></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><?php echo $info_utenti[0]->num_esami_terminati; ?></p>
                    </div>
                    <div class="col-sm-3">
                        <p class="mb-0"><?php echo $info_utenti[1]->num_esami_terminati; ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <canvas id="chart_uomini_donne"></canvas>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>
    <div class="col-1"></div>
</div>
</section>
</body>
</html>

<script>
    let num_prenotati = <?php echo $num_prenotati; ?>;
    let num_terminati = <?php echo $num_terminati; ?>;
    
    let num_uomini = <?php echo $info_utenti[1]->num_utenti; ?>;
    let num_donne = <?php echo $info_utenti[0]->num_utenti; ?>;

    // Pie chart per gli esami
    let chart_esami_element = document.getElementById('chart_esami').getContext('2d');
    let chart_esami = new Chart(chart_esami_element, {
      type: 'pie',
      data: {
        labels: ['Prenotati', 'Terminati'],
        datasets: [{
          data: [num_prenotati, num_terminati],
          backgroundColor: [
            'rgba(250, 243, 63, 1)', // Giallo
            'rgba(75, 9, 173, 1)' // Viola
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        legend: {
          display: true,
          position: 'bottom'
        }
      }
    });

    // Pie chart per gli utenti
    let chart_utenti_element = document.getElementById('chart_uomini_donne').getContext('2d');
    let chart_utenti = new Chart(chart_utenti_element, {
      type: 'pie',
      data: {
        labels: ['Uomini', 'Donne'],
        datasets: [{
          data: [num_uomini, num_donne],
          backgroundColor: [
            'rgba(54, 162, 235, 1)', // Blu
            'rgba(255, 23, 12, 1)'  // Rosso
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        legend: {
          display: true,
          position: 'bottom'
        }
      }
    });
  </script>


