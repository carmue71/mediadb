<?php
/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Starts the scan of a given device and prints the output
 */


include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<h1>Scanning Device <i><?php print $device->Name; ?></i></h1>
				<?php $this->repository->scan($device); ?>
		</div>	<!-- col -->
	</div><!-- row -->
</div><!-- container -->

<?php
include VIEWPATH.'fragments/footer.php';
?>