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
		
		<br><hr><br>
		<div class="card-group">
				<?php foreach($episodes as $set): ?> 
				<!-- msforchannel.php -->
				<div
				class="list-group-item list-group-item-action flex-column align-items-start transparent">
				<table>
				<tr>
				<td rowspan=3>
				<a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
				<img class='tag_listimage'
        src='<?php if ( isset($set) ) print $set->getPicture(120);?>' align=left></a></td>
        <td width=90%>
        <div class="d-flex w-100 justify-content-between">
        <h5>
        <a
        href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'
			    class="mb-1">
			    <?php print $set->Title?></a>
					</h5>
				</div>
			</td>
			<td width=10%>
				<small><?php	print "Pix: ".($this->episodeRepository->countFiles($set->ID_Episode,2))." / Videos:"
	                       .$this->episodeRepository->countFiles($set->ID_Episode, 3);//;substr($set->Added,0,10);?>
				</small>
			</td>
		</tr>
		<tr>
		<td>		
			<p class="mb-1"><?php print(cutStr($set->Description,120,20));?></p>
			</td>
			<td>
				<?php print $set->printRating();?>
				
			</td>
		</tr>
		<tr>
			<td>
				<small>
					<?php $actors = $this->actorRepository->findActorsForEpisode($set->ID_Episode);
				        if ( isset($actors)){
				            foreach ($actors as $actor): ?>
								<a href='<?php print INDEX."showactor?id={$actor['ID_Actor']}"; ?>'><?php print $actor['Fullname']; ?></a>    
				    		<?php endforeach; //TODO: add some style
				        } ?>
				</small>
			</td>
			<td>
			</td>
		</tr>
	</table>
</div>
				<?php endforeach;?>
		</div> <!-- card group -->
	
	</div>
		<div class="col-4">
			<div class="info-area">
				<h4>Statistics</h4>
				<p class="info-text"># Media Sets: <?php print $numberOfSets; ?></p>
				<br><hr><br>
				<h4>Actions</h4>
				<p  class="info-text">
				<a href='<?php print INDEX."scanchannel?id={$channel->ID_Channel}";?>'	type="button" class="btn btn-info"> <i class="fas fa-search"></i> Scan Channel</a>
				</p>
		</div></div></div>
	</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
