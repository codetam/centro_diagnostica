<div class="modal fade" id="modifica_referto" tabindex="-1" role="dialog" aria-labelledby="modifica_referto_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modifica referto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="../src/check_referto.php">
      <div class="modal-body">
        <div class="form-group">
            <div class="form-group">
              <label for="referto">Referto:</label>
              <textarea class="form-control" id="testo" name="testo" rows="5"><?php echo $testo_referto ?></textarea>
              <input type="hidden" name="id_esame" value="<?php echo $id_esame ?>" >
              <input type="hidden" name="request" value="modifica">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit" class="btn btn-primary">Conferma</button>
      </div>
      </form>
    </div>
  </div>
</div>