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
                    }
                    include VIEWPATH.'episodes/episode_card.php';
                    break;
                case 'table':
                case 'plain':
                default:
                    include VIEWPATH.'episodes/episode_table.php';
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
	