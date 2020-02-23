<?php
$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$channel->Wallpaper}') no-repeat center center fixed;"
    ."background-size: cover;\"";
    

    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-8">
			<h1><?php print $channel->Name; ?></h1>
		</div>
		<div class="col-4">
			<a href='http://<?php print $channel->Site; ?>' target=_blank><img
				src='/mediadb/ajax/getAsset.php?type=logo&file=<?php print $channel->Logo; ?>&size=S'
				align=right alt='<?php print $channel->Name;?>'></a>
				<small><a href='http://<?php print $channel->Site; ?>' target=_blank><?php print $channel->Site?></a></small>
		</div>
	</div>
<?php
$activeTab = "Details";
include VIEWPATH . 'channels/channel_tabs.php';
?>

	<div class="row">
		<div class="col-8">
			<p><?php print nl2br($channel->Comment); ?></p>
		</div>
		<?php if ($numberOfSets >= 0 ){ ?>
		<div class="col-4">
			<p># Media Sets: <?php print $numberOfSets; ?></p>
		</div>
		<?php }?>
	
	</div><!-- row -->
	<div class='row'>
		<div class="card-group">
				<?php foreach($episodes as $set): 
				    include VIEWPATH."channels/episodes_for_channel.php";
				endforeach;?>
		</div> <!-- card group -->
	</div>
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>

