<?php

/* Container.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Builds the start page for mediadb
 */

function count_episodes($pdo, $all=true){
    if ( $all )
        $query = "SELECT COUNT(*) as Number FROM Episode";
    else 
        $query = "SELECT COUNT(*) as Number FROM Episode WHERE Viewed = 0";
    
    $stmt = $pdo->prepare($query);
    if ($stmt->execute()) {
        if ($stmt != null)
            return $stmt->fetch()['Number'];
    }
    return 0;
}

function count_channels($pdo){
    $query = "SELECT COUNT(*) as Number FROM Channel";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute()) {
        if ($stmt != null)
            return $stmt->fetch()['Number'];
    }
    return 0;
}

function count_actors($pdo){
    $query = "SELECT COUNT(*) as Number FROM Actor";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute()) {
        if ($stmt != null)
            return $stmt->fetch()['Number'];
    }
    return 0;
}



$bodymodifier = " id='welcome'";
include (VIEWPATH.'fragments/header.php');
if ( !$showLogin ){
    include (VIEWPATH.'fragments/navigation.php');
    
    $numberOfEpisodes = count_episodes($pdo);
    $unseenEpisodes = count_episodes($pdo, false);
    $numberOfActors = count_actors($pdo);
    $numberOfChannels = count_channels($pdo);
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
		<p class="lead">The databases currently contains <a href='<?php print INDEX; ?>listepisodes?filter=All'><bold> <?php print $numberOfEpisodes; ?></bold> episodes</a>.</p>
		<p>From those are <a href='<?php print INDEX; ?>listepisodes?filter=Unwatched'> <?php print $unseenEpisodes?> unseen</a> yet.<br>
		<?php print $numberOfActors?> <a href='<?php print INDEX;?>listactors'> Actors</a> and <?php print $numberOfChannels;?> different <a href='<?php print INDEX;?>listchannels'>Channels</a> are listed. </p> 
		<?php } ?>
	</div>
</div>
<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>