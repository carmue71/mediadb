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
        ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<h1>Episodes</h1>
		</div>
		<?php  
		  $target = "listepisodes?";
		  include VIEWPATH . 'episodes/options_episodes.php'; 
		?>
	</div>
	<div class='container'>
	<!-- top pagination start-->
	<div class="row">
		<div class="col-md-12">
        		<?php $this->printPagination();?>
            </div>
	</div><!-- top pagination end -->
	
	<div class="row">
		<?php
		if ( $this->msStyle == 'card' ){
		    print "<div class='card-group'>";
		    $i = 2;
		} else {
		    print "<div class='col-md-12'>";
		}
		    
            
                foreach ($params['entries'] as $set) {
                    switch ( $this->msStyle ){
                        case 'list':
                            include VIEWPATH.'episodes/episode_row.php';
                            break;
                        case 'card':
                            $i = ($i+1) % 3;
                            if ( $i == 0 ){
                                print "</div><br/> <div class='card-group'>";
                            }
                            ?>
                            <!-- mscard -->
                            <div class="card">
								<a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
  									<img class="card-img-top" src='<?php print $set->getPicture(300); ?>' alt="<?php print $set->Title; ?>" ></a>
  									<small><?php $set->printRating(); ?></small>
  								<div class="card-body">
    								<h4 class="card-title"><?php print $set->Title; $set->printWatched();?></h4>
    								<p class="card-text"><?php  print ($this->cutStr($set->Description,80)); ?></p>
    								<p class="card-text"><a href='<?php print INDEX."showchannel?id={$set->REF_Channel}"?>'>
    									<small class="text-muted"><?php print $set->Channel?></a></small></p>
									<?php $this->printActors($this->actorRepository->findActorsForEpisode($set->ID_Episode)); ?>  
    							</div><!-- /card-body -->
						  </div><!-- /card -->
                            <?php
                            //include VIEWPATH.'episode/fragment/episodecard.php';
                            break;
                        case 'table':
                        case 'plain':
                        default:
                            ?>
                            <li class="list-group-item list-group-item-action flex-column align-items-start transparent">
                            	<div class="d-flex w-100 justify-content-between">
                            		<h5 class='mb-1'>
                            			<a href='<?php print INDEX;?>showepisode?id=<?php print $set->ID_Episode;?>'><?php print $set->Title;?> </a>
											<?php if ( $set->isWatched() ) {?>
													&nbsp; <i class='fas fa-check-circle' style='color:Gold;'></i>
      										<?php } else {?>
      											&nbsp; <i class='far fa-circle' style='color:Grey;'></i>
      										<?php }?>
										</h5>
									<small>(<a href='<?php print INDEX;?>showchannel?id=<?php print $set->REF_Channel;?>'> <?php print $set->Channel;?> </a>)
										<br><?php $set->printRating(); ?>
									</small>
								</div>
								<p class="mb-1"><?php print nl2br(cutStr($set->Description, 120)) ?></p>
							</li>
							<?php 
                    }//switch
                }
               
                print "</div>";  ?>
	</div>	<!-- row -->
	<div class="row">
		<div class="col-md-12">
        		<?php $this->printPagination();?>
            </div>
	</div>
	</div><!-- container -->
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
