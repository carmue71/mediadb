<?php
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container">

<?php
$activeTab = "Edit";
include VIEWPATH . 'actors/actor_tabs.php';
?>

<div class="row">
		<div class='row'>
		<div class='col-sm-12'>
			<form method="POST" action='<?PHP print INDEX."saveactor"?>'>
				<input type="hidden" name="id" value='<?PHP print $actor->ID_Actor; ?>' />
				<?php if ( isset($episodeid) ){?>
				<input type="hidden" name="msid" value='<?PHP print $episodeid; ?>' />
				<?php }?>
				<div class='form-row'>
					<div class='col-sm-6'>
						<div class=form-group>
							<label class="col-form-label" for='fullname'>Fullname</label> 
							<input class='form-control' id='fullname' type="text" name="fullname" size=50 required value='<?PHP print $actor->Fullname; ?>'
								placeholder='Unique Name for the actor' />
						</div><!-- form-group -->
					</div> <!-- col -->
					<div class='col-sm-6'>
						<div class=form-group>
							<label class="col-form-label for='alias'">Aliases</label> 
							<input class='form-control' type="text" name="aliases" size=50 id='alias' value='<?PHP print $actor->Aliases; ?>'
								placeholder='other names of the actor' />
						</div><!-- grp -->
					</div><!-- col -->
				</div><!-- form-row -->
				
				<div class='form-row'>
					<div class="col-sm-2">
					<div class=form-group>
						<label class="col-form-label" for='gender'>Gender</label> 
						<select class='form-control' id='gender' name="gender">
							<option value="F" <?PHP if ($actor->Gender=="F") print "selected"; ?>>Female</option>
							<option value="M" <?PHP if ($actor->Gender=="M") print "selected"; ?>>Male</option>
							<option value="T" <?PHP if ($actor->Gender=="T") print "selected"; ?>>Transgender</option>
							<option value="?" <?PHP if (!isset($actor->Gender) || $actor->Gender=='?' ) print "selected"; ?>>Unknown</option>
						</select>
					</div> <!-- grp -->
					</div><!-- col -->
					<div class='col-sm-4'>
						<div class='form-group'>
							<label class="col-form-label" for='twitter'>Twitter</label> 
							<input class='form-control' id='twitter' type="text" name="twitter" size=40 value='<?PHP print $actor->Twitter; ?>' />
						</div> <!-- grp -->
					</div> <!-- col -->
					<div class='col-sm-6'>
						<div class='form-group'>
							<label class="col-form-label" for='website'>Website</label> 
							<input class='form-control' id='website' type="text" name="website" size=40 value='<?PHP print $actor->Website; ?>' />
						</div><!-- grp -->
					</div><!-- col -->
					</div><!-- row -->
					<div class='form-row'>
					<div class='col-sm-6'>
						<div class='form-group'>
							<label class="col-form-label" for='description'>Description</label>
							<textarea class='form-control' id='description' name="description" cols="70" rows="4"><?PHP print $actor->Description; ?></textarea>
						</div><!-- grp -->
					</div><!-- col -->	
					<div class='col-sm-6'>
						<div class='form-group'>
							<label class="col-form-label" for='moddata'>Data</label>
							<textarea class='form-control' id='moddata' name="moddata" cols="70" rows="4"><?PHP print $actor->Data; ?></textarea>
						</div><!-- grp -->
					</div><!-- col -->
				</div><!-- form-row -->
				<div class='form-row'>
					<div class='col-sm-2'>
						<img width=80px id=previewMugshot src='/mediadb/ajax/getAsset.php?type=mugshot&file=<?php print $actor->Mugshot; if ( isset($actor->Gender) ) print "&gender={$actor->Gender}"; ?>&size=N'
							data-img='/mediadb/ajax/getAsset.php?type=mugshot&file=<?php print $actor->Mugshot; if ( isset($actor->Gender) ) print "&gender={$actor->Gender}"; ?>&size=L' />
					</div><!-- col -->
					<div class='col-sm-4'>
						<div class='form-group'>
							<label class="col-form-label" for='mugshot'>Mug Shot</label> 
							<input class='form-control' id='mugshot' type="text" name="mugshot" value='<?PHP if ( strcmp($actor->Mugshot, "default/mugshot01.png") <>0 ) print $actor->Mugshot; ?>' />
					        <a class='btn' href='#' onClick='addImage("mugshot")'><i class="fas fa-upload">Upload a Mugshot</i> </a>
						</div><!-- grp -->
					</div><!--  col -->
					<div class='col-sm-6'>
						<div class='form-group'>
							<label class="col-form-label" for='keywords'>Keywords</label>
							<textarea class='form-control' id='keywords' name="keywords" cols="70" rows="4"><?PHP print $actor->Keywords; ?></textarea>
						</div><!-- grp -->
					</div><!-- col -->
				</div><!-- form-row -->
				<div class='form-row'>
					<div class='col-sm-2'>
						<img width=120px src='/mediadb/ajax/wallpaper.php?file=<?php print $actor->Wallpaper;?>' />
					</div><!--  col -->
					<div class='col-sm-4'>
					
						<div class='form-group'>
							<label class="col-form-label" for='wallpaper'>Wallpaper</label>					
							<input class='form-control' id='wallpaper' type="text" name="wallpaper" value='<?PHP print $actor->Wallpaper; ?>' />
					<!--  <input type="file" name="wallpaper"/> -->
						</div><!-- grp -->
					</div><!-- col -->
					<div class='col-sm-6'>
						<div class='form-group'>
							<label class="col-form-label" for='sites'>Sites</label>
							<textarea class='form-control' id='sites' name="sites" cols="70" rows="4"><?PHP print $actor->Sites; ?></textarea>
						</div><!-- grp -->
					</div><!-- col -->
				</div><!-- form-row -->
				<div class='form-row'>
					<div class='col-sm-2'>
						<img width=64px src='/mediadb/ajax/getAsset.php?type=thumbnail&file=<?php print $actor->Thumbnail;?>&size=N' />
					</div><!-- col -->
					<div class='col-sm-4'>
						<div class='form-group'>
							<label class="col-form-label" for='thumbnail'>Thumbnail</label> 
							<input class='form-control' id='thumbnail' type="text" name="thumbnail" value='<?PHP print $actor->Thumbnail; ?>' />
					        <!-- <input type="file" name="mugshot"/> -->
						</div><!-- grp -->
					</div><!--  col -->
					
				</div><!-- row -->
				<div class='form-row'>
					<input class='form-control btn-primary' type="submit" />
				</div>
				<!-- form-row -->

			</form>
		</div>		<!-- col -->
		</div>
	</div>	<!-- row -->
</div><!-- container -->

<!-- Preview -->
<div class='gal-lightbox' data-imgid=1>
	<div class='gal-content'>
		<img class='gal-image' src='img/large-1.jpg' />
	</div>
	<span class='gal-close-lightbox' onclick="closeModal()">&times;</span>
</div>


<?php
include VIEWPATH.'fragments/js.php';
include VIEWPATH."fragments/uploadimage.php";
?>

<script src="/mediadb/js/mygal.js"></script>
<script type="text/javascript">
<!--

//-->
function addImage(type){
	$('#uploadimage').modal('show');
	$('#uploadimage').type = type;
}

$("#uploadBtn").click(function(){
	var fData = new FormData($('uploadform')[0]);
    console.log(fData);
    $.ajax({
        url: "/mediadb/ajax/upload_asset.php",
        type: "POST", data: fData, contentType: false, processData: false,
        success: function (data) {
            console.log(data);
            //todo: display the image in the preview.
        }
    })
	$("#uploadimage").modal('hide');
});   

$('#previewMugshot').click(function(){
	var src = $(this).data('img');
	showLightbox(src, "", 0, 0);
});

</script>

</body>
</html>
