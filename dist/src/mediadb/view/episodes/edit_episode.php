<?php
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>
<div class="container-fluid">

<?php
$activeTab = "Edit";
include VIEWPATH . 'episodes/episode_tabs.php';
?>
	<form method="POST" action="<?PHP print INDEX; ?>saveepisode">
				<input type="hidden" name="id" value='<?PHP print $ms->ID_Episode; ?>' />
				<div class="form-group row">
					<label class='col-sm-1 col-form-label' for='title'>Title<sup>*</sup>:</label>
					<div class='col-sm-10'>
							<div class="input-group">
								<input class="form-control" required id='title' type="text"
									name="title" size=80 placeholder='The Title of this Media Set'
									value='<?PHP print "{$ms->Title}"; ?>' /> <span
									class="input-group-btn">
									<button class="btn btn-secondary clearbox" type="button"
										id='cleartitle'>&nbsp;</button> <!-- <i class='fas fa-times-circle'> -->
								</span>
						</div>
					</div>
					<div class='col-sm-1'>		
						<!-- disable autofill for now 
						<a class='btn btn-secondary' id='autofill'><i class="fas fa-magic activeItem"></i></a> 
						-->
						<button class='btn btn-primary' type="submit"><i class="fas fa-save"></i> Save</button>
					</div>
					 
				</div><!-- row -->
				<div class="form-group row">
					<label class="col-sm-1 col-form-label" for='description'>Description: </label>
					<div class='col-sm-5'> 
						<textarea class="form-control form-control-sm" id='description'
							name="description" cols="80" rows="4"
							placeholder='Describe the content'><?PHP print $ms->Description; ?></textarea>
					</div><!-- form group title -->
							<label class="col-sm-1 col-form-label" for='keywords'>Keywords:</label>
							<div class="col-sm-5">
							<textarea class="form-control form-control-sm" id='keywords'
								name="keywords" cols=80 rows=4
								placeholder='Comma separated list of tags'> <?PHP print $ms->Keywords; ?></textarea>
							</div>
				</div><!-- row -->
				<div class="form-group row">
					<label class="col-sm-1 col-form-label" for='published'>Published:</label> 
					<div class='col-sm-2'>
						<select	name="published" class="form-control" id='published' required>
						<option value=' '<?PHP if ($ms->Published=="" ) print "selected"; ?>></option>
						<?php
						  if ( ! isset($ms->Published) )
						      $ms->Published = 2018; 
						  for ($i=1990; $i < 2021; $i++):?>
								<option value='<?php print $i; ?>'
								<?PHP if ( $ms->Published==$i ) print "selected"; ?>>
									<?php print $i;?>
								</option>
						<?php endfor;?>
						</select>
					</div><!-- col -->
					<label class="col-sm-1 col-form-label" for='channel'>Channel:</label>
					<div class='col-sm-2'> 
						<select class='form-control' id='channel' required name="ref_channel">
						<?php $selected = !isset($ms->REF_Channel)? " selected" : "";
					       print "<option value='' {$selected}> </option>\n";
                            if (isset($channels)) {
                                foreach ($channels as $entry) {
                                    $sid = $entry['ID_Channel'];
                                    $name = $entry['Name'];
                                    $selected = $sid == $ms->REF_Channel ? " selected" : "";
                                    print "<option value='{$sid}'{$selected}>{$name}</option>\n";
                                }
                            }
                                ?>
						</select>
					</div>
					<label class="col-sm-1 col-form-label" for='link'>Link:</label> 
					<div class='col-sm-2'>
						<input class='form-control' id='link' type="text" name="link" placeholder='Link to orignal source' aria-describedby="Link"
							value='<?PHP print $ms->Link; ?>' />
						<small id='LinkTest'class='form-text text-muted'>
						<a href='<?php print $ms->Link;?>'><?php print $ms->Link; ?></a>
						</small>
					</div><!-- col -->
					<label class="col-sm-1 col-form-label" for='code'>Publisher Code:</label> 
					<div class='col-sm-2'>
						<input class='form-control' id='code' type="text" name="publisherCode" placeholder='Identifier Code' required
							value='<?PHP print $ms->PublisherCode; ?>' />
					</div> <!-- col -->
				</div><!-- row -->
				<div class='form-group row'>
					<label class='col-sm-1 col-form-label' for=comment>Comment</label>
					<div class=col-sm-11>
						<textarea class='form-control form-control-sm' id='comment' name="comment" cols=80 rows="4"
							placeholder='Any other thing you want to add to this set'><?PHP print $ms->Comment; ?></textarea>
					</div><!-- col -->
				</div>	<!-- row -->
				<div class="form-group row">
					<label class="col-sm-1 col-form-label" for='picture'>Picture:</label>
					<div class="col-sm-2">
						<p align=left> <img id='previewPoster' width=200 
							src='<?PHP print $ms->getPicture(); ?>' 
							data-img='<?PHP print $ms->getPicture(); ?>' /></p>
					</div>
					<div class="col-sm-3">
						<input class='form-control' id='picture' type="text" name="picture"	size=70
							value='<?PHP if ( strcmp($ms->Picture,"default/picture06.png" ) <> 0) print $ms->Picture; ?>' />
				        <!-- input type="file" name="picture" size=80 id='originalPoster'/> --> 
						<br> <small id=oldposter>&nbsp;</small>
			                 <!--  <img id=original width=100 src='< ?PHP print $ms->getPicture(); ?>' />-->
					<!-- <button class='btn btn-secondary' id='saveposter'>Save Poster</button> -->
					</div> <!-- col -->
					<label class='col-sm-1 col-form-label' for='wallpaper'>Wallpaper: </label>
					<div class="col-sm-2">
						<p align=left> <img  width=200 src='<?PHP print $ms->getWallpaper(); ?>' /> </p>
					</div>
					<div class="col-sm-3">
						<input class='form-control' id='wallpaper' type="text" name="wallpaper" size=80 value='<?PHP print $ms->Wallpaper; ?>' />
				        <!-- <input type="file" name="wallpaper" size=80 /> -->
					</div><!-- col -->
				</div><!-- row -->
	</form>
	<div class='row'>
		<div class='col-sm-12'>
			<p align="center" id=statusMessage><i>&nbsp;</i></p>
		</div>
	</div>
</div><!-- container -->

<!-- Preview -->
<div class='gal-lightbox' data-imgid=1>
	<div class='gal-content'>
		<img class='gal-image' src='img/large-1.jpg' />
	</div>
	<span class='gal-save-image' onclick="saveImage()"><i class="fas fa-save"></i></span>
	<span class='gal-close-lightbox' onclick="closeModal()">&times;</span>
	
</div>


<?php 
    include VIEWPATH.'fragments/js.php';
    include VIEWPATH.'fragments/modal/confirmdelete.php';
    include VIEWPATH.'fragments/modal/slideshowoptions.php';
?>


<script src="/mediadb/js/mygal.js"></script>
<script type="text/javascript">
	//$('#cleartitle').click( function(){ alert('Hello World'); } );
	$('#cleartitle').click( function(){ $('#title').val('') } );
	$('#oldposter').click(function(){
		var src = $(this).text();
		showLightbox(src, "", 0, 0);
	});
	$('#previewPoster').click(function(){
		var src = $(this).data('img');
		showLightbox(src, "", 0, 0);
	});
	
</script>

<script src="/mediadb/js/togglewatch.js"></script>
</body>
</html>
