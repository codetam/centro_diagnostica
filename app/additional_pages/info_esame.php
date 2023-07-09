<!-- Info sull'esame -->
<h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Esame n. <?php echo $id_esame; ?></h3>

            <div class="row">
                <div class="col-sm-6"><p><b>Tipologia</b></p></div>
                    <div class="col-sm-6"><p> <?php echo $esame->tipologia; ?> </p></div>
                <div class="col-sm-6"><p><b>Ora</b></p></div>
                    <div class="col-sm-6"><p> <?php echo substr($esame->ora, 0, 5); ?> </p></div>
                <div class="col-sm-6"><p><b>Codice fiscale</b></p></div>
                    <div class="col-sm-6"><p> <?php echo $esame->codice_utente; ?> </p></div>
                <div class="col-sm-6"><p><b>Completato</b></p></div>
                    <div class="col-sm-6"><p> <?php echo $completato; ?> </p></div>
            </div>