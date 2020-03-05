<?php

/* Container.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Builds the start page for mediadb
 */

$bodymodifier = " id='welcome'";
include (VIEWPATH.'fragments/header.php');
if ( !$showLogin ){
    include (VIEWPATH.'fragments/navigation.php');
}
?>

<div class="jumbotron">
	<div class="container" >
	<span class="display-3">Welcome to Charly's MediaDB</span>
		<p><?php print VERSION;?> - Please note, that this is still work in progress!</p>
		<p><button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#aboutModal"> About </button></p>
		<?php if (isset($errorMessage) && $errorMessage != ""){ ?>
		    <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php print $errorMessage;?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php }
		if ( $showLogin ){?>
		<form method="POST" action='/mediadb/login.php'>
			<div class='form-row'>
				<div class='col-sm-4'>
				<input class='form-control' type=text id='login' name='login' placeholder='Your Username' required />
				</div>
				<div class='col-sm-4'>
				<input class='form-control' type=password id=password name=password placeholder='Your Password' required />
				</div>
				<div class='col-sm-2'>
				 <button type="submit" class="btn btn-danger">Login</button>
				</div>
			</div>
			</form>
		<?php } else {?>
		<p class="lead">A list of <a href='<?php print INDEX; ?>listepisodes'>episodes</a> can be found here.</p>
		<?php } ?>
	</div>
</div>
<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>