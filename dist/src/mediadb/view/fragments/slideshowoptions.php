<!-- Modal SlideShowOptions -->
<div class="modal fade" id="slideshowOptionsDlg" 
	tabindex="-1" 
	role="dialog" 
	aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">Options for your Slideshow</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div class='form-row'>
			<div class=form-group>
      			<label><input type=checkbox name=ss_random_order id=ss_random_order />&nbsp;Random Order</label>
      			</div>
      	</div>
      	<div class='form-row'>
			<div class=form-group>
      			<label><input type=checkbox name=ss_autoplay id=ss_autoplay />&nbsp; Autoplay</label>
      					</div>
      	</div>
      	<div class='form-row'>
			<div class=form-group>
      	
      	<label for=interval>Change interval in seconds:</label>
      		<input type=text name=ss_interval id=ss_interval value=15 />
      	
      	</div>
      	</div>
      	<br><hr><br>
        <button id='ssoptions_okbtn' type=button class='btn btn-primary'>OK</button>
        <button id='ssoptions_cancelbtn' type=button class='btn btn-secondary' data-dismiss="modal" aria-label="Close">Cancel</button>
      </div>
    </div>
  </div>
</div>