<?php
/* settings.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Displays the settings
 */

include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<div class="row">
	<div class="col-sm-12">
		<h1><?php print $this->pageTitle?></h1>
	</div>
</div>
<div class="row">
	<div class='col-sm-12'>
		<form method="POST" action='<?PHP print INDEX."savesettings"?>'>
		<!-- input type="hidden" name="id" value='< ?PHP print $user->ID_User; ?>' /-->
		
		<div class='form-row'>
			<div class='col-sm-6'>
				<div class=form-group>
					<label class="col-form-label" for='login'>Login</label> 
					<input class='form-control' id='login' type="text" name="login" required value=''
						placeholder='Login-Name des Nutzers' />
				</div><!-- form-group -->				
			</div><!-- col -->
		</div><!-- form-row -->
		
		<div class='form-row'>
			<div class='col-sm-4'>
				<input class='form-control btn-primary' type="submit" />
			</div>
		</div>
		<!-- form-row -->
	</form>
	</div>
	<!-- col -->
</div>
<!-- row -->

</div><!-- container -->
<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
