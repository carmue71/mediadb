<!-- msrow.php -->
<div
	class="list-group-item list-group-item-action flex-column align-items-start transparent">
	<table>
		<col width="120"> <col>
  		<tr>
  			<td>
  				<?php print $set['Position']?> 
  			</td>
			<td width=120px>
			<a href='<?php print INDEX."showepisode?id={$set['ID_Episode']}"?>'>
			<img class='tag_listimage' src='<?php print WWW."ajax/getAsset.php?type=poster&size=S&file={$set['Picture']}";?>' 
				align=left></a></td>
			<td width=100%> 
				<div class="d-flex w-100 justify-content-between">
					<h5>
						<a
							href='<?php print INDEX."showepisode?id={$set['ID_Episode']}"?>'
							class="mb-1"> 
      					<?php print $set['Title']?></a>
					</h5>
					
				</div>
				<small class="mb-1"><?php print(cutStr($set['Description'],120,20));?></small>
			</td>
			<td>
			<small><?php
			print "Pix: ".($this->episodeRepository->countFiles($set['ID_Episode'],2))." <br/> Videos:"
	           .$this->episodeRepository->countFiles($set['ID_Episode'], 3); ?>
					</small>
			</td>
			<td> &nbsp;&nbsp;&nbsp; 
				<a href='#' value='<?php print "{$set['ID_Episode']}"; ?>'><i class='fas fa-times-circle fa-2x' 
					style="color:Tomato" onClick="removeFromWatchlist(<?php print "{$wl->ID_WatchList},{$set['ID_Episode']}, {$set['Position']}"?>)"></i></a>
				&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
</div>