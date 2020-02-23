<!-- Modal ConfirmDelete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">Confirmation to Delete Files</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <p id='confirmTxt'> Do you really want to delete?</p>
        <br/>
        <input type=checkbox id=physicallyDeleteFiles/>Also delete files from disk<br /><br/>
        <button id='confirmDeleteBtn' type=button class='btn btn-danger'>Confirmed!</button>
        <button id='cancelWithoutConfirmationBtn' type=button class='btn btn-secondary data-dismiss="modal" aria-label="Close" '>Cancel</button>
      </div>
    </div>
  </div>
</div>