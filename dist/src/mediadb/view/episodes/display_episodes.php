	<div class='row'> <?php include VIEWPATH . 'episodes/options_episodes.php';		?>	</div>
	
	<div class="row">
		<div class="col-md-12">
        		<?php
        		  if ( $msLastPage > 0 )
        		      $this->printPagination($msLastPage, $episodeRepository); 
        		  else 
        		      $this->printPagination();?>
            </div>
	</div>

<div class="row">
		<?php
		if ( $display_style == 'card' ){
		    print "<div class='card-group'>";
		    $i = 2;
		} else {
		    print "<div class='col-md-12'>";
		}
		foreach ($episode_list as $set) {
		    
            switch ( $display_style ){
                case 'list':
                    include VIEWPATH.'episodes/episode_row.php';
                    break;
                case 'card':
                    $i = ($i+1) % 3;
                    if ( $i == 0 ){
                        print "</div><br/> <div class='card-group'>";
                    }?>
                    <div class="card">
                    	<a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
                    	<img class="card-img-top" src='<?php print $set->getPicture(300); ?>' alt="<?php print $set->Title; ?>" ></a>
                    	<div class="card-body">
                    	<h4 class="card-title"><?php print $set->Title; $set->printWatched();?></h4>
    					<p class="card-text"><?php  print ($this->cutStr($set->Description,80)); ?></p>
    					<p class="card-text"><a href='<?php print INDEX."showchannel?id={$set->REF_Channel}"?>'><small class="text-muted"><?php print $set->Channel?></a></small></p>
    					<?php 
    					$this->printActors($this->actorRepository->findActorsForEpisode($set->ID_Episode));
    					/*if ( isset($actors)){   
    						print("<p>");   
            					foreach ($actors as $actor){
            					    //TODO: Pill
            					    print ("<span class='badge badge-pill badge-light'>
                                        <a class='actorlink' href='".INDEX."showactor?id={$actor['ID_Actor']}'> {$actor['Fullname']} </a></span>"); 
								} 
							print("</p>"); 
    				    } */?>  
    				</div>
  					</div> <?php 
                    break;
                case 'table':
                case 'plain':
                default:?>
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
                <?php //    include VIEWPATH.'episodes/episode_table.php';
            }//switch
        }
               
                print "</div>";  ?>
</div>	<!-- row -->

	<div class="row">
		<div class="col-md-12">
        		<?php
        		  if ( $msLastPage > 0 )
        		      $this->printPagination($msLastPage, $episodeRepository); 
        		  else 
        		      $this->printPagination();?>
            </div>
	</div>
	