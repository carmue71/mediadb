<?php
/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Displays the episodes and actresses/actors matching a search
 */


include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<h1><?php print $this->pageTitle; ?></h1>
			<h2>Episodes</h2>
			<ul  class="list-group">
			<?php if ( $sets != null ) {foreach($sets as $set):?>
				<li class="list-group-item list-group-item-info"><a href='<?php print INDEX."showepisode?id={$set->ID_Episode}";?>'>
				<?php print $set->Title;?>&nbsp;&nbsp;</a></li>	
			<?php endforeach;}?>
			</ul>
			<h2>Actresses and Actors</h2>
			<ul  class="list-group">
			<?php
			 if ( $actors != null )
			 {
			     foreach($actors as $actor):?>
				<li class="list-group-item list-group-item-dark"><a href='<?php print INDEX."showactor?id={$model->ID_Actor}";?>'>
					<?php print $actor->Fullname;?> </a>&nbsp;&nbsp;</li>
			<?php endforeach;
			}?>
			</ul>
		</div>
	</div>
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
