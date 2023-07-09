<div class="modal fade" id="carica_referto" tabindex="-1" role="dialog" aria-labelledby="carica_referto_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Completa l'esame</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="../src/check_referto.php">
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="referto">Referto:</label>
                        <textarea class="form-control" id="testo" name="testo" rows="5"></textarea>
                        <input type="hidden" name="id_esame" value="<?php echo $id_esame ?>" >
                        <input type="hidden" name="request" value="crea">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="submit" class="btn btn-primary">Invia</button>
            </div>
            </form>
        </div>
    </div>
</div>