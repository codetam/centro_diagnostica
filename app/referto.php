<?php 
    if(!isset($_POST["codice_referto"])){
      include_once("additional_pages/header.php");
      include_once("src/functions.php");
?>

  <main>
    <section class="w-100 vh-100 d-flex flex-column justify-content-center align-items-center text-white fs-1">
      <h1 style="font-size: 1.5em">Devi inserire un codice per poter visualizzare questa pagina!</h1>
    </section>
  </main>
  </body>
  </html>

<?php
      exit();
    }
    $url = 'http://localhost/api/codicetemp/read';

    $codice = $_POST["codice_referto"];
    $dati_da_inviare = array(
      'codice_univoco' => $codice
    );

    $formData = http_build_query($dati_da_inviare);
    // Viene chiamata l'API per verificare la presenza del codice e l'id associato al referto
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);
    $esame = json_decode($response);
    if(isset($esame->state)){
      if($esame->state == 0){
        header("location: additional_pages/codice_scaduto.php");
        exit();
      }
    }
    $id_esame = $esame->id_esame;

    $completato = "No";
    if($esame->terminato == 1){
      $completato = "Si";
    }

    
    include_once("additional_pages/header.php");
    include_once("src/functions.php");

?>
<section class="gradient-custom">
  <div class="container py-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-12 col-lg-9 col-xl-7">
        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
          <div class="card-body p-4 p-md-5">
            
            <?php
              //Info sull'esame
              include_once("additional_pages/info_esame.php");
            ?>
            <hr>
            <div class="row">
              <p><b>Referto</b></p>
              <p> <?php echo '<pre>' . $esame->testo . '<pre>'; ?> </p>
              <div id=imageContainer></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>

<script>
  const idReferto = <?php echo $esame->id_esame; ?>;
  
  // Mostra le immagini associate al referto
  function displayImages() {
  fetch('http://localhost/api/immagine/read/referto/' + idReferto)
    .then(response => response.json())
    .then(data => {
      const images = data;

      const imageContainer = document.getElementById('imageContainer');
      imageContainer.innerHTML = '';

      images.forEach((imageData) => {
        const aElement = document.createElement('a');
        aElement.href = imageData.contenuto;
        aElement.setAttribute('data-lightbox', 'image-gallery');
        aElement.innerText = imageData.nome;
        imageContainer.appendChild(aElement);
        imageContainer.appendChild(document.createElement('br'));
      });

      // Inizializza lightbox
      lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
      });
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

// La funzione viene chiamata quando la pagina ha finito di caricarsi
window.addEventListener('load', displayImages);
</script>

<style>
  pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>