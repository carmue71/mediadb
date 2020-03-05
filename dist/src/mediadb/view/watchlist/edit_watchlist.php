<?php 
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<?php
$activeTab = "Edit";
include VIEWPATH . 'watchlist/wltabs.php';
?>
			<form method="POST" action='<?PHP print INDEX."savewatchlist"?>'>
				<input type="hidden" name="id" value='<?PHP print $wl->ID_WatchList; ?>' />
				<div class='form-row'>
					<div class='col-lg-12'>
						<div class=form-group>
							<label class="col-form-label" for='title'>Title</label> 
							<input class='form-control' id='title' type="text" name="title" size=50 required value='<?PHP print $wl->Title; ?>'
								placeholder='Unique Name for the studio' />
						</div><!-- form-group -->
					</div> <!-- col -->
				</div><!-- form-row -->
				<div class='form-row'>
					<div class='col-sm-12'>
					<div class=form-group>
						<label class="col-form-label" for='description'>Description</label> 
							<textarea class='form-control' id='description' name="description"><?PHP print $wl->Description; ?></textarea>
						</div><!-- form-group -->
					</div> <!-- col -->
					</div><!-- row -->
				<div class='form-row'>
					<input class='form-control  btn-primary' type="submit" />
				</div>
				<!-- form-row -->
			</form>	
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
