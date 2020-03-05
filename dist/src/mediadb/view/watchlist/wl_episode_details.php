<!-- msforstudio.php -->
<div
	class="list-group-item list-group-item-action flex-column align-items-start">
	<table>
		<tr>
			<td>
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
					</h5>
					<small><?php
					print "Pix: ".($this->episodeRepository->countFiles($set->ID_Episode,2))." / Videos:"
	                       .$this->episodeRepository->countFiles($set->ID_Episode, 3);//;substr($set->Added,0,10);?>
					</small>
				</div>
				<p class="mb-1"><?php print(cutStr($set->Description,120,20));?></p>
				<!-- <small>
				?php $actors = $this->actorRepository->findActorsForEpisode($set->ID_Episode);
				if ( isset($actors)){
				    foreach ($actors as $actor): ?>
						<a href='< ?php print INDEX."showactor?id={$actor['ID_Actor']}"; ?>'><?php print $actor['Fullname']; ?></a>    
				    < ?php endforeach; //TODO: add some style
				}
				?> 
				</small>  -->
			</td>
		</tr>
	</table>
</div>

