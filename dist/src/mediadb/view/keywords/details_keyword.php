<?php
/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Displays the episodes and actresses/actors containing a given keyword
 */


include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<div class="row">
	<div class="col-12">
		<h1><?php print $key; ?></h1>
		<h2>Episodes</h2>
		<ul>
			<?php foreach ($episodes as $episode): ?>
				<li><a
				href='showepisode?id=<?php print $episode['ID_Episode'];?>'><?php print $episode['Title'];?></a></li>
			<?php endforeach;?>
		</ul>
		<h2>Actors / Actresses </h2>
		<ul>
			<?php foreach ($actors as $actor): ?>
				<li><a href='showactor?id=<?php print $actor['ID_Actor'];?>'><?php print $actor['Fullname'];?></a></li>
			<?php endforeach;?>
		
		</ul>
	</div>
</div>
</div><!-- container -->

<?php include VIEWPATH.'fragment/js.php'; ?>
</body>
</html>