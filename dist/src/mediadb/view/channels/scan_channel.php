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
			<h1>Scanning Channel <?php print($channel->Name)?></h1>
				<?php $this->repository->scan($channel); ?>
		</div>	<!-- col -->
	</div><!-- row -->
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>