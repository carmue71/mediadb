<?php
use mediadb\actor\Episode;

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$actor->Wallpaper}') no-repeat center center fixed;"
    ."background-size: cover;\"";
    
    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<?php 
$activeTab="Episodes";
include VIEWPATH.'actors/actor_tabs.php';
?>
 <div class='row'>
 	<div class='col-sm-12'>
 	<h4>Actor <i><?php print $actor->Fullname?></i> appears in the following sets:</h4>
 	</div><!-- col -->
 </div><!-- row -->
 <div class='row'>
 <?php 
 $target = "listepisodesforactor?id={$actor->ID_Actor}&";
 include VIEWPATH . 'episodes/options_episodes.php'; 
 ?>
</div><!-- row -->
 <div class='row'>
 	
 	<?php
		if ( $this->msStyle == 'card' ){
		    print "<div class='card-group'>";
		    $i = 2;
		} else {
		    print "<div class='col-lg-12'>";
		}  
 	
 	  foreach ($sets as $set ):
 	   
 	      switch ( $this->msStyle){
 	          case 'card':
 	              $i = ($i+1) % 3;
 	              if ( $i == 0 ){
 	                  print "</div><br/><div class='card-group'>";
 	              }?>
 	              <!-- mscard -->
					<div class="card transparent">
						<a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
  							<img class="card-img-top" src='<?php print $set->getPicture(300); ?>' alt="<?php print $set->Title; ?>" ></a>
  							<div class="card-body">
    							<h4 class="card-title"><?php print $set->Title;?></h4>
    							<p class="card-text"><?php  print ($this->cutStr($set->Description,80)); ?></p>
    							<p class="card-text"><a href='<?php print INDEX."showchannel?id={$set->REF_Channel}"?>'>
    								<small class="text-muted"><?php print $set->Channel?></small></a> 
        						<?php if ( $set->isWatched() ) {?>
      								&nbsp; <i class='fas fa-check-circle' style='color:Gold;'></i>
      							<?php } else {?>
      								&nbsp; <i class='far fa-circle' style='color:Grey;'></i>
      							<?php }?></p>
    						</div>
  					</div><?php 
  					break;
 	          case 'list':
 	              print "<br\>";
 	          case 'plain':
 	          default:
 	              ?>
 	              <div class='list-group transparent'>
 	            	  <div class="list-group-item list-group-item-action flex-column align-items-start">
						<table>
							<tr>
								<td>
									<a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
										<img class='tag_listimage' src='<?php if ( isset($set) ) print $set->getPicture(120);?>' align=left></a></td>
								<td width=100%>
									<div class="d-flex w-100 justify-content-between">
										<h5> <a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>' class="mb-1"> <?php print $set->Title?></a></h5>
										<small><?php print "Pix: ".($this->episodeRepository->countFiles($set->ID_Episode,2))." / Videos:"
                                            .$this->episodeRepository->countFiles($set->ID_Episode, 3);//;substr($set->Added,0,10);?> </small>
									</div>
									<p class="mb-1"><?php print(cutStr($set->Description,120,20));?></p>
									<small><?php $actors = $this->repository->findActorsForEpisode($set->ID_Episode);
				                        if ( isset($actors)){
				                            foreach ($actors as $actor): ?>
				    							<span class='badge badge-pill badge-light'>&nbsp;
													<a href='<?php print INDEX."showactor?id={$actor['ID_Actor']}"; ?>'><?php print $actor['Fullname']; ?></a>&nbsp;
												</span>&nbsp;
				    					<?php endforeach; } ?>
									</small>
									<p class="card-text"><a href='<?php print INDEX."showchannel?id={$set->REF_Channel}"?>'>
    								<small class="text-muted"><?php print $set->Channel?></small></a>
									
										<?php if ( $set->isWatched() ) {?>
      								&nbsp; <i class='fas fa-check-circle' style='color:Gold;'></i>
      							<?php } else {?>
      								&nbsp; <i class='far fa-circle' style='color:Grey;'></i>
      							<?php }?></p>
								</td></tr>
						</table>
					</div>
 	              </div> <!-- list group -->
		<?php    }
 	  endforeach;
 	  ?> </div>
 	</div><!-- row -->
	<div class="row">
		<div class="col-md-12">
        		<?php $this->printPagination($this->msLastPage, $this->episodeRepository); ?>
            </div>
	</div>
<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>

