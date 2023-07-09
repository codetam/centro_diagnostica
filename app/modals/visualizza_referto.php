<div class="modal fade" id="visualizza_referto" tabindex="-1" role="dialog" aria-labelledby="visualizza_referto_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visualizza il referto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../referto.php" method="post">
            <div class="modal-body">
                <p>Inserisci il codice associato al referto.</p> 
                <p>Non hai un codice? Se un esame è stato completato, puoi fare click su "Referto" per ottenere il codice.</p>
                <h5>Codice:</h5>
                    <input type="text" name="codice_referto" class="form-control form-control-lg" placeholder="Codice" required/>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block">Conferma</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
            </form>
        </div>
    </div>
</div>