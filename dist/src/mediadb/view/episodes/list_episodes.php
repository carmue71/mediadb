<?php

/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Lists the episodes matching the filter
 */

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file=default.png') no-repeat center center fixed;"
        ."background-size: cover;\"";

        include VIEWPATH.'fragments/header.php';
        include VIEWPATH.'fragments/navigation.php';
        $target = "listepisodes?";
        
        ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<h1>Episodes</h1>
		</div>
	</div>
	<div class='container'>
	
	<?php 
	$episode_list = $params['entries'];
	$display_style = $this->msStyle;
	//$episodeRepository = $this->repository;
	$msLastPage = -1;
	include VIEWPATH.'episodes/display_episodes.php'; ?>
	
	</div><!-- container -->
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
