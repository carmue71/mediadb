<?php

/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Shows the details of a given episode
 */

include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';

\mediadb\Logger::debug("details_episode.php: Document started");
?>

<div class="container-fluid">

<?php
$activeTab = "Details";
include VIEWPATH . 'episodes/episode_tabs.php';

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
  		<img width=800px src='/mediadb/ajax/getAsset.php?type=poster&size=XL&file=<?php print $ms->Picture;?>' />
  		<?php  if ($numberOfMovies>0) print "</a>"; ?> 
		
		<br>
		<h3>Description</h3>
		<p><?php print nl2br($ms->Description);?></p> 
		<hr>
		<?php if ( $ms->Comment != "" ){ ?>
			<h3>Comments</h3>
  			<p><?php print nl2br($ms->Comment); ?></p>
  		<?php }?>
  		</div><!-- col-lg-8 -->		
		
		<div class="col-lg-4">
			<div class="info-area">
			<div class="my-rating-6" value='<?php print $ms->Rating; ?>'> </div><br>
			
			<h4><i class="fas fa-female"></i><i class="fas fa-male"></i> Actresses / Actors 
								<span class='badge badge-info'><?php print $numberOfActors;?></span>
							
			</h4>
			<p class="info-text">
				<?php if ( isset($actors)){      
        	       foreach ($actors as $actor){?>
        	       	<span class='badge badge-pill badge-light'>
        	        <a class='actorlink' href='<?php print INDEX;?>showactor?id=<?php print $actor['ID_Actor'];?>'><?php print $actor['Fullname']?></a>
					<span class='unlinkActor' data-actor='<?php print $actor['ID_Actor']?>' data-name='<?php print $actor['Fullname']?>' data-set='<?php print $ms->ID_Episode?>'> 
					<i class='fas fa-times-circle'></i></span></span>
					<?php } //foreach 
				}?>
			</p>
			<hr>
			<h4><i class="fas fa-binoculars"></i> Watch Lists</h4>
				<p class="info-text"><?php $this->printWatchlists($ms->ID_Episode);?></p>
			<hr>
			<h4><i class="fas fa-tags"></i> Keywords</h4>
			<p class="info-text"><?php $this->printKeywords($ms->Keywords); ?></p>
			<hr>
			
			<h4><i class="fas fa-chart-bar"></i> Statistics</button></h4>
			<p class="info-text">
				<ul id='msdetails'>
								<li>Publisher Code:<a href='<?php print $ms->Link ?>'> <?php print $ms->PublisherCode ?></a></li>
								<li>Published: <?php print $ms->Published ?></li>
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
							</p>
			<hr>
			<h4>Actions</h4>
			<p class="info-text">
			<button class="btn btn-secondary" onClick='watchlistAdd(<?php print $ms->ID_Episode.", ".$_SESSION['watchlater'];?>)'><i class="far fa-clock"></i>&nbsp;Wach&nbsp;Later</button>&nbsp;
          	<button class='btn btn-primary' id='scanEpisode' value='<?php print $ms->ID_Episode;?>'><i class="fas fa-search"></i>&nbsp;Scan Episode</button>&nbsp;
          	<button class="btn btn-secondary" class='btn secondary' id='addActors' title='Link/Unlink Actors' data-id='<?php print $ms->ID_Episode;?>'><i class="fas fa-user-tag"></i>&nbsp;Add Actor</button>&nbsp;
          	<button class="btn btn-secondary addepisodetowatchlist" value='<?php print $ms->ID_Episode;?>'><i class="fas fa-binoculars">&nbsp;Add&nbsp;to&nbsp;Wachlist</i></button>&nbsp;
			</p>
			<br>
			<button class='btn btn-warning' id='deleteEpisode' value='<?php print $ms->ID_Episode;?>'data-id='<?php print $ms->ID_Episode;?>'><i class="fas fa-trash"></i> Delete Episode</button>
          	</div><!-- info-area -->
		</div><!-- Col-lg-4 -->	 

				
				
		</div>	<!-- row -->
	</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
<script type="text/javascript">

$('#scanEpisode').click( function(event){
	console.log("scanEpisode clicked");
	return false;
});

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
    \mediadb\Logger::debug("details_episode.php: Document created");
?>
</body>
</html>
