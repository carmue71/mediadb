<?php
$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/wallpaper.php?file=watchlist.jpg') no-repeat center center fixed;"
    ."background-size: cover;\"";
    

    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<h1><i class="fas fa-binoculars"></i> <?php print $wl->Title; ?></h1>
		</div>
	</div>
<?php
$activeTab = "Details";
include VIEWPATH . 'watchlist/wltabs.php';
?>

	<div class="row">
		<div class="col-8">
			<p><?php print nl2br($wl->Description); ?></p>
		</div>
		<?php if ($numberOfSets >= 0 ){ ?>
		<div class="col-4">
			<p># Episodes: <?php print $numberOfSets; ?></p>
		</div>
		<?php }?>
	
	</div><!-- row -->
	<div class='row'>
		<div class='col-sm-12'>
			<div class="list-group">
				<?php foreach($episodes as $set): 
				    include VIEWPATH."watchlist/wl_episode_details.php";
				endforeach;?>
			</div> <!-- list group -->
		</div>		
	</div>
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>