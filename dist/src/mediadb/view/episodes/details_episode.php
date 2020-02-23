<?php

/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Shows the details of a given episode
 */

include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<?php
$activeTab = "Details";
include VIEWPATH . 'episode/episode_tabs.php';

if ($this->successMessage != "") {
    ?>
		<div class="alert alert-success" role="alert"> 
			<?php print $this->successMessage; $this->successMessage = "";?>
			<button type="button" class="close" data-dismiss="alert"
			aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<?php

}
if ($this->infoMessageBody != "") {
    ?>
		<div class="alert alert-info" role="alert">
		<h4> 
			<?php print $this->infoMessageHead; $this->infoMessageHead = "";?>
				<button type="button" class="close" data-dismiss="alert"
				aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</h4>
		<hr />
				<?php print $this->infoMessageBody; $this->infoMessageBody = "";?>
		</div>
	<?php

}
?>
<div class="row">
		<div class="col-lg-8">
		<?php  if ($numberOfMovies>0) print "<a href='".INDEX."movies?id={$ms->ID_Episode}'>"; ?> 
  		<img width=800px src='/mediadb/getAsset.php?type=poster&size=XL&file=<?php print $ms->Picture;?>' />
  		<?php  if ($numberOfMovies>0) print "</a>"; ?> 
		</div>
		<div class="col-lg-4">
			<div id="accordion">
				<div class="card transparent">
					<div class="card-header" id="headingOne">
						<h4 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
								aria-controls="collapseOne"><i class="fas fa-file-alt"></i> Description</button></h4>
					</div>

					<div id="collapseOne" class="collapse"
						aria-labelledby="headingOne" data-parent="#accordion">
						<div class="card-body  transparent">
							<p><?php print nl2br($ms->Description);?></p> 
						</div>
					</div>
				</div>

				<div class="card transparent">
					<div class="card-header" id="headingTwo">
						<h4 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse"
								data-target="#collapseTwo" aria-expanded="true"
								aria-controls="collapseTwo"><i class="fas fa-info-circle"></i> Info</button>
						</h4>
					</div>

					<div id="collapseTwo" class="collapse"
						aria-labelledby="headingTwo" data-parent="#accordion">
						<div class="card-body transparent">
							<div class="my-rating-6" value='<?php print $ms->Rating; ?>'> </div><br>
							<p> Publisher Code:<a href='<?php print $ms->Link ?>'> <?php print $ms->PublisherCode ?></a></p>
						</div> <!-- card-body -->
					</div>
				</div>
				<div class="card  transparent">
					<div class="card-header" id="headingActors">
						<h4 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse"
								data-target="#collapseActors" aria-expanded="true"
								aria-controls="collapseActors"><i class="fas fa-female"></i><i class="fas fa-male"></i> Actresses / Actors 
								<span class='badge badge-info'><?php print $numberOfActors;?></span>
							</button>
							<button class="btn btn-secondary" class='btn secondary' id='addActors' title='Link/Unlink Actors' data-id='<?php print $ms->ID_Episode;?>'>
								<i class="fas fa-user-tag"></i>
							</button> 
						</h4>
					</div>

					<div id="collapseActors" class="collapse"
						aria-labelledby="headingActors" data-parent="#accordion">
						<div class="card-body">
							<?php if ( isset($actors)){      
        	                   foreach ($actors as $actor){?>
        	                   <span class='badge badge-pill badge-light'>
        	                   		<a class='actorlink' href='<?php print INDEX;?>showactor?id=<?php print $actor['ID_Actor'];?>'>
        	                        	<?php print $actor['Fullname']?>
									</a>
									<span class='unlinkActor'
										data-actor='<?php print $actor['ID_Actor']?>'
										data-name='<?php print $actor['Fullname']?>'
										data-set='<?php print $ms->ID_Episode?>'> 
										<i class='fas fa-times-circle'></i>
									</span>
								</span>
            					<?php } 
							}?>
						</div>
					</div>
				</div>
				<div class="card  transparent">
					<div class="card-header" id="headingKeywords">
						<h4 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse"
								data-target="#collapseKeywords" aria-expanded="true"
								aria-controls="collapseKeywords"><i class="fas fa-tags"></i> Keywords</button>
						</h4>
					</div>

					<div id="collapseKeywords" class="collapse"
						aria-labelledby="headingKeywords" data-parent="#accordion">
						<div class="card-body">
						<?php $this->printKeywords($ms->Keywords); ?>
									</div>
					</div>
				</div>

				<div class="card  transparent">
					<div class="card-header" id="headingStatistics">
						<h4 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse"
								data-target="#collapseStatistics" aria-expanded="true"
								aria-controls="collapseStatistics"><i class="fas fa-chart-bar"></i> Statistics</button>
						</h4>
					</div>

					<div id="collapseStatistics" class="collapse"
						aria-labelledby="headingStatistics" data-parent="#accordion">
						<div class="card-body">
							<ul id='msdetails'>
								<li> Published: <?php print $ms->Published ?></li>
								<li>Rating: <?php print $ms->Rating ?></li>
								<li>Viewed: <?php print $ms->Viewed ?></li>
								<li>Added: <?php print $ms->Added ?></li>
								<li>Modified: <?php print $ms->Modified ?></li>
								<li>Files: <?php print $numberOfMovies + $numberOfPix; ?>
									<ul>
										<li>Pictures: <?php print $numberOfPix; ?> </li>
										<li>Movies: <?php print $numberOfMovies; ?> <ul>
										<li>HD-Movies: <?php print $numberOfHDVideos; ?></li>
										<li>High Quality: <?php print $numberOfHiVideos?></li>
										</ul></li>
										</ul>
										</li>
							</ul>	
						</div>
					</div>
				</div>
				
				<div class="card transparent">
					<div class="card-header" id="headingWatchList">
						<h4 class="mb-0">
							<button class="btn btn-link" data-toggle="collapse"
								data-target="#collapseWatchList" aria-expanded="true"
								aria-controls="collapseWatchList"><i class="fas fa-binoculars"></i> Watch Lists</button>
							<button class="btn btn-secondary addepisodetowatchlist" value='<?php print $ms->ID_Episode;?>'>
                          		<i class="fas fa-binoculars"></i> 
                          	</button>
                          	<button class="btn btn-secondary" onClick='watchlistAdd(<?php print $ms->ID_Episode.", ".$_SESSION['watchlater'];?>)'>
                          		<i class="far fa-clock"></i> <!-- Todo: Check if already set --> 
                          	</button>
						</h4>
					</div>

					<div id="collapseWatchList" class="collapse"
						aria-labelledby="headingWatchList" data-parent="#accordion">
						<div class='card-body' id='WatchListContainer'>
                			<?php $this->printWatchlists($ms->ID_Episode);?>
						</div>
					</div>
				</div>  
				  
				
  				<?php if ( $ms->Comment != "" ){ ?>
  				<div class="card transparent">
					<div class="card-header" id="headingComment">
						<h4 class="mb-0">
							<button class="btn btn-link collapsed" data-toggle="collapse"
								data-target="#collapseComment" aria-expanded="false"
								aria-controls="collapseComment"><i class="fas fa-comments"></i> Comment</button>
						</h4>
					</div>
					<div id="collapseComment" class="collapse" aria-labelledby="headingComment"
						data-parent="#accordion">
						<div class="card-body">
							<p><?php print nl2br($ms->Comment); ?></p>
						</div>
					</div>
				</div>
  				<?php } ?>
  				</div> <!-- accordeon -->
			</div>	<!-- col -->
		</div>	<!-- row -->
	</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>

	<script type="text/javascript">
		
    $(function() {
  $(".my-rating-6").starRating({
    totalStars: 5,
    initialRating: $(".my-rating-6").attr('value'),
    strokeWidth: 0,
    ratedColor: 'Gold',
    disableAfterRate: false,
    callback: function(currentRating, $el){
    	var msid = <?php print $ms->ID_Episode;?>; /*$('#msid').attr('value');*/
        var values = {
          		'what': 'rating',
        		'msid': msid,
  				'rating': currentRating,
  		};
  		
        var result = $.ajax({
            url:'/mediadb/ajax/update.php',
            type: 'POST',
            data: values,	
            success: function() {
				console.log("Update OK");
            }
		});
  		console.log(result);
              
      
      console.log('rated:', currentRating);
    }
  });
});

    function removeFromWatchlist(wlid, msid, pos){
        var values = {
            'what': 'removefromwatchlist',
            'msid': msid,
            'wlid': wlid,
            'pos': pos,
        };
        //alert("Values: "+msid+" "+wlid+" "+pos);
        var result = $.ajax({
            url:'/mediadb/ajax/update.php',
            type: 'POST',
            data: values,
            error: function(){
        		console.log(result);
                alert("Not removed from Watchlist!");
        	}
        });
        console.log(result);    
        //window.location.reload(false); 
        //TODO: better: remove the respective pill
        id='#WatchList'+wlid+"_"+pos;
        //$(this).hide();
        $(id).remove();
    }

</script>
<script src="/mediadb/js/jquery.star-rating-svg.js"></script>
<script src="/mediadb/js/togglewatch.js"></script>

<script type="text/javascript">
<!-- Unlinks the actor from the episode and removes the actor from the current list.
//-->
$('.unlinkActor').click(function(){
	var name =$(this).data('name');
	span = $(this).parent(); 
	var values = {
            'what': 'unlink',
            'msid': $(this).data('set'),
            'mid': $(this).data('actor')
        };
        
        var result = $.ajax({
            url:'/mediadb/ajax/update.php',
            type: 'POST',
            data: values,
            success: function() {
                console.log("Removed");
                span.remove();
                span = null; 
            }
        });
         
});

</script>

<?php
    include VIEWPATH.'episodes/delete_episode.php';
    include VIEWPATH.'fragments/addwatchlist.php';
?>
</body>
</html>
