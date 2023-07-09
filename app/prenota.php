<?php
    include_once("additional_pages/header.php");
    include_once("src/functions.php");

    if(isset($_SESSION["codice_fiscale"])){
?>
<section class="gradient-custom">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <?php
              if(isset($_GET["error"])){
                echo '<h5 class="mb-4 text-danger">' . $_GET["error"] . '</h5>';
              }
            ?>
            <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Prenota un esame</h3>

            <form action="src/check_prenota.php" method="post">
              <div class="row">
                <label for="tipologia" class="form-label">Tipologia</label>
                <div class="col-12">
                  <select class="mb-4 select form-control-lg" id="tipologia" name="tipologia">
                    <option value="0" disabled>Tipologia</option>
                    <option value="ecografia">Ecografia</option>
                    <option value="radiografia">Radiografia</option>
                    <option value="mammografia">Mammografia</option>
                    <option value="ecodoppler">Ecodoppler</option>
                    <option value="tac">TAC</option>
                    <option value="risonanza_magnetica">Risonanza magnetica</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-2 d-flex align-items-center">
                  <div class="form-outline datepicker w-100">
                    <label for="data_esame" class="form-label">Data</label>
                    <input type="text" class="form-control form-control-lg" id="data_esame" name="data_esame" placeholder="GG/MM/AA" required/>
                  </div>
                </div>
              </div>
              <div class="row" id="orario_input" style="display: none;">
                <label for="orario" class="form-label">Orario</label>
                <div class="col-12">
                  <select class="mb-4 select form-control-lg" id="orario" name="orario">
                  </select>
                </div>
              </div>
              <div class="mt-4 pt-2">
                <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">Prenota</button>
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

<?php
    } else {
?>
      <main>
            <section class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center text-white fs-1">
                <h1 style="font-size: 1.5em">Devi accedere per poter visualizzare questa pagina!</h1>
            </section>
        </main>
<?php
    }
?>

<script>
  $(function() {
      $( "#data_esame" ).datepicker({
          dateFormat:"dd/mm/yy",
          onSelect: function(dateText, inst) {
          const orario_input = document.getElementById('orario');
          orario_input.selectedIndex = 0;

          const orario_input_container = document.getElementById('orario_input');
          if (dateText !== '') {
            orario_input_container.style.display = 'block';
            let formattedDate = formatDate(dateText);

            $.ajax({
                    url: 'api/get_orario.php',
                    method: 'GET',
                    data: { data_scelta: formattedDate },
                    success: function(response) {
                        orario_input.innerHTML = response;
                    }
                });
          } 
          else {
            orario_input_container.style.display = 'none';
          }
        }
      });
  });

function formatDate(dateText) {
  let parts = dateText.split('/');
  let formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
  return formattedDate;
}
</script>