<?php
/* edit_device.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Editor View for devices
 */


include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container">
	<form method="POST" action='<?PHP print INDEX."savedevice"?>'>
		<input type="hidden" name="id" value='<?PHP print $device->ID_Device; ?>' />
		<div class='form-row'>
			<div class='col-sm-12'>
				<div class=form-group>
					<label class="col-form-label" for='name'>Name</label> 
					<input class='form-control' id='name' type="text" name="name" required value='<?PHP print $device->Name; ?>'
						placeholder='Unique Name of the Device' />
				</div><!-- form-group -->
				<div class=form-group>
					<label class="col-form-label" for='path'>Path</label> 
					<input class='form-control' id='path' type="text" name="path" required value='<?PHP print $device->Path; ?>'
						placeholder='Unique Path of the Device' />
				</div><!-- form-group -->
				
				<div class=form-group>
					<label class="col-form-label" for='path'>Display Path</label> 
					<input class='form-control' id='dpath' type="text" name="dpath" required value='<?PHP print $device->DisplayPath; ?>'
						placeholder='Unique Path used for displaying the files' />
				</div><!-- form-group -->
				
				<div class=form-group>
					<label class="col-form-label" for='comment'>Comment</label> 
					<textarea class='form-control' id='comment' name="comment"><?PHP print $device->Comment; ?></textarea>
				</div><!-- form-group -->
				
			</div><!-- col -->
		</div>
		<!-- form-row -->
		<div class='form-row'>
			<input class='form-control btn-primary' type="submit" />
		</div>
		<!-- form-row -->
	</form>
</div><!-- container -->

<?php
include VIEWPATH.'fragments/footer.php';
?>