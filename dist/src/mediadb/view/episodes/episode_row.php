<!-- episode_row.php -->
<div
	class="list-group-item list-group-item-action flex-column align-items-start transparent">
	<table class='transparent'>
		<col width="120"> <col>
  		<tr>
			<td width=120px rowspan=4>
			<a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
			<img class='tag_listimage' 
				src='<?php if ( isset($set) ) print $set->getPicture(120);?>' align=left></a></td>
			<td width=100%>
				<div class="d-flex w-100 justify-content-between">
					<h5>
						<a
							href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'
							class="mb-1"> 
      					<?php print $set->Title?></a>
      					<?php if ( $set->isWatched() ) {?>
      					    &nbsp; <i class='fas fa-check-circle' style='color:Gold;'></i>
      					<?php } else {?>
      						&nbsp; <i class='far fa-circle' style='color:Grey;'></i>
      					<?php }?>
      					</h5>
				</div>
			</td>
			<td>
				<small>
					<?php print "Pix: ".($msRep->countFiles($set->ID_Episode,2))." / Videos:"
	                   .$msRep->countFiles($set->ID_Episode, 3);//;substr($set->Added,0,10);?>
				</small>
			</td>
			</tr>
			<tr>
				<td>
					<p class="mb-1"><?php print(cutStr($set->Description,120,20));?></p>
				</td>
				<td>
					<?php $set->printRating(); ?>
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
				    }
				    ?>
					</small>
				</td>
				<td>
					<!-- TODO: show channel logo -->
					<a href='<?php print INDEX."showchannel?id={$set->REF_Channel}"; ?>'>	<?php print $set->Channel;?></a>
				</td>
		</tr>
		<tr><td>
		<span style="font-size: 2em;">
      					<?php 
      					if ($msRep->countFiles($set->ID_Episode, 3) > 0){
      					    print "<a href='".INDEX."movies?id={$set->ID_Episode}' alt='Jump to the video'><i class='fas fa-video'></i></a>";
      					    print "&nbsp;&nbsp;";
      					}
      					if ($msRep->countFiles($set->ID_Episode, 2) > 0){
      					    print "<a href='".INDEX."showpix?id={$set->ID_Episode}' alt='Jump to the Pictures'><i class='fas fa-images'></i></a>";
      					    print "&nbsp;&nbsp;";        
      					}
      					print "<a href='".INDEX."showfiles?id={$set->ID_Episode}' alt='Jump to the Files'><i class='fas fa-file-alt'></i></a>";
      					?>
      					</span>
		</td></tr>
	</table>
</div>