<?php
    include_once("additional_pages/header.php");
    include_once("src/functions.php");
    
    if(!isset($_POST["codice_referto"])){
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
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);
    $esame = json_decode($response);
    $id_esame = $esame->id_esame;

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
              //Info sull'esame
              include_once("additional_pages/info_esame.php");
            ?>
            <hr>
            <div class="row">
              <p><b>Referto</b></p>
              <p> <?php echo $esame->testo; ?> </p>
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


