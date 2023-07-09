<?php
    include_once("additional_pages/header.php");
    include_once("src/functions.php");
    // Viene controllato se l'operatore è loggato e se è stata fatta una richiesta GET con l'ID dell'esame
    if(!isset($_SESSION["id_operatore"]) || !isset($_GET["id_esame"])){
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
    // Inizializzazione oggetto esame
    $id_esame = $_GET["id_esame"];
    $json = file_get_contents('http://localhost/api/esame/read/' . $id_esame);
    $esame = json_decode($json);

    // Viene controllato se l'esame è assegnato all'operatore in questione
    if($_SESSION["id_operatore"] != $esame->id_operatore){
?>

  <main>
    <section class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center text-white fs-1">
      <h1 style="font-size: 1.5em">L'esame deve essere gestito da un altro operatore!</h1>
    </section>
  </main>
  </body>
</html>

<?php
      exit();
    }
    $completato = "No";
    if($esame->terminato == 1){
      $completato = "Si";
    }

?>
<section class="gradient-custom">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            <?php
              // Controllo sull'errore o successo post referto
              if(isset($_GET["error"])){
                echo '<h5 class="mb-4 text-danger">' . $_GET["error"] . '</h5>';
              }
              else if(isset($_GET["success"])){
                echo '<h5 class="mb-4 text-success">' . $_GET["success"] . '</h5>';
              }

              //Info sull'esame
              include_once("additional_pages/info_esame.php");

              if($completato == "No"){
                include_once("modals/carica_referto.php");
                // Button per caricare il modal
                echo '<button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#carica_referto">Completa</button>';
              }
              else {
                $json = file_get_contents('http://localhost/api/referto/read/' . $id_esame);
                $referto = json_decode($json);
            ?>
            <hr>
            <div class="row">
              <p><b>Referto</b></p>
              <p> <?php echo $referto->testo ?> </p>
              <div id=imageContainer></div>
            </div>
            <br>
            <div class="row">
              <?php include_once("modals/modifica_referto.php"); ?>
              <!-- Button per caricare il modal -->
              <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modifica_referto">Modifica referto</button>
            </div>
            <br>
            <div class="row">
              <button id="aggiungiImmagineButton" type="button" class="btn btn-warning btn-lg" >Aggiungi immagine</button>
            </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>

<script>
  let idEsame = <?php echo $id_esame; ?>;

  document.getElementById('aggiungiImmagineButton').addEventListener('click', function() {
    // Create an input element of type 'file'
    let input = document.createElement('input');
    input.type = 'file';

    input.click();

    // Handle the file selection
    input.addEventListener('change', function() {
      let file = input.files[0];
      if (file) {
        let reader = new FileReader();

        // Read the file content
        reader.addEventListener('load', function() {
          let fileContent = reader.result;

          // Create a FormData object to send the file data
          let formData = new FormData();
          formData.append('id_esame', idEsame);
          formData.append('contenuto', fileContent);
          formData.append('nome', file.name);

          // Send a POST request to the API endpoint
          fetch('http://localhost/api/immagine/create', {
            method: 'POST',
            body: formData
          })
          .then(function(response) {
            // Handle the response from the API
            if (response.ok) {
              // Image upload successful
              let successUrl = 'http://localhost/esame.php?id_esame=' + idEsame + '&success=Immagine%20aggiunta%20con%20successo.';
              window.location.href = successUrl;
            } else {
              // Image upload failed
              let errorUrl = 'http://localhost/esame.php?id_esame=' + idEsame;
              window.location.href = errorUrl;
            }
          })
          .catch(function(error) {
            let errorUrl = 'http://localhost/esame.php?id_esame=' + idEsame;
            window.location.href = errorUrl;
          });
        });

        // Read the file as data URL
        reader.readAsDataURL(file);
      }
    });
  });
  
  function displayImages() {
  fetch('http://localhost/api/immagine/read/referto/' + idEsame)
    .then(response => response.json())
    .then(data => {
      const images = data; // Assuming 'data' is an array of image objects

      const imageContainer = document.getElementById('imageContainer');
      imageContainer.innerHTML = ''; // Clear the container before adding images

      images.forEach((imageData) => {
        const rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        const colDiv = document.createElement('div');
        colDiv.classList.add('col-sm-10');

        const aElement = document.createElement('a');
        aElement.href = imageData.contenuto;
        aElement.setAttribute('data-lightbox', 'image-gallery');
        aElement.innerText = imageData.nome;

        colDiv.appendChild(aElement);
        rowDiv.appendChild(colDiv);

        const deleteDiv = document.createElement('div');
        deleteDiv.classList.add('col-sm-2');

        const deleteButton = document.createElement('button');
        deleteButton.innerText = 'Elimina';
        deleteButton.classList.add('btn', 'btn-danger');
        deleteButton.addEventListener('click', () => {
          deleteImage(imageData.id);
        });

        deleteDiv.appendChild(deleteButton);
        rowDiv.appendChild(deleteDiv);

        imageContainer.appendChild(rowDiv);
        imageContainer.appendChild(document.createElement('br'));
      });

      // Initialize Lightbox
      lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
      });
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function deleteImage(imageId) {
  fetch('http://localhost/api/immagine/delete/' + imageId, {
    method: 'GET'
  })
    .then(response => {
      if (response.ok) {
        let okUrl = 'http://localhost/esame.php?id_esame=' + idEsame + "&success=Immagine%20eliminata%20con%20successo.";
        window.location.href = okUrl;
      } else {
        let errorUrl = 'http://localhost/esame.php?id_esame=' + idEsame + "&error=Eliminazione%20immagine%20fallita.";
        window.location.href = errorUrl;
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

// Run the code when the page finishes loading
window.addEventListener('load', displayImages);
</script>

<style>
  .lightbox-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #f1f1f1;
    border: none;
    color: #333;
    font-size: 16px;
    cursor: pointer;
  }
</style>


