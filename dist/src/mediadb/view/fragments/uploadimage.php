<!-- Modal Image Upload -->
<div class="modal fade" id="uploadimage" tabindex="-1" role="dialog"
	aria-labelledby="ModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modaltitle">Upload Image</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id='imgPreview' align=center>
					<p>
						<i>No Preview</i>
					</p>
				</div>
				<form id='uploadform'>
					<div class=form-group align=center>
						<input type='file' id='imageFile'></input>
					</div>
					<!-- grp -->
				</form>
				<button id='uploadBtn' type=button class='btn btn-info'>Upload</button>
				<button type=button class='btn btn-secondary' data-dismiss="modal"	aria-label="Close">Cancel</button>
			</div>
		</div>
	</div>
</div>

<script>

$(function(){
	$('#imageFile').change(function(){
		console.log('imageFile changed:');
		console.dir(this.files[0]);
		var myImage = new FileReader();
		myImage.onload = imageReady;
		myImage.readAsDataURL(this.files[0]); 
	});

	//$("#uploadBtn").click(function(){
    //	$("#uploadimage").modal('hide');
	//});   
});

function imageReady(e){
	$('#imgPreview').html("<img class='previewImage' src='"+e.target.result+"' style='{max-width=150px; max-height:150px}'>");
	console.log(e);
}
</script>