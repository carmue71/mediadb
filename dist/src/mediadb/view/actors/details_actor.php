<?php
use mediadb\model\Episode;

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$actor->Wallpaper}') no-repeat center center fixed;"
    ."background-size: cover;\"";
    
    
    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<?php 
$activeTab="Details";
include VIEWPATH.'actors/actor_tabs.php';
?>
 <div class="row">
		<div class="col-sm-4">
			<img class="img-thumbnail" id=previewMugshot src='/mediadb/ajax/getAsset.php?file=<?php print $actor->Mugshot; if ( isset($actor->Gender) ) print "&gender={$actor->Gender}";  ?>&type=mugshot'
				data-img='/mediadb/ajax/getAsset.php?type=mugshot&file=<?php print $actor->Mugshot; if ( isset($actor->Gender) ) print "&gender={$actor->Gender}";?>&size=N'>
		</div>
		<div class="col-sm-8">
			<h1> <?php print $actor->Fullname; ?></h1>
			<div id="accordion">
  				<div class="card transparent">
    				<div class="card-header" id="headingOne">
    				<h5 class="mb-0">
        			<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          			General Information
        			</button>
      				</h5>
    			</div>
    			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      				<div class="card-body">
      		<?php if ( isset($actor->Aliases) && $actor->Aliases<>"" ){ ?>
			<p>Aliases: <?php print $actor->Aliases;  ?> </p>
			<?php }?>
			<p>Gender: <?php print $actor->Gender;  ?> </p>
			<?php if ( isset($actor->Twitter) && $actor->Twitter<>"" ){ ?>
			<p>
				<img src='mediadb/ajax/getAsset.php?type=system&file=twitter.gif?size=XS' alt='Twitter:'> <a
					target='_blank'
					href='https://twitter.com/<?php print $actor->Twitter;  ?> '><?php print $actor->Twitter;  ?> </a>
			</p>
			<?php }
			if ( isset($actor->Website) && $actor->Website<>"" ){ ?>
			<p>
				Website: <a target='_blank '
					href='<?php print $actor->Website;  ?> '><?php print $actor->Website;  ?> </a>
			</p>
			<?php }?>
			
			<div class="my-rating-6" value='<?php print $actor->Rating; ?>'> </div><br>
			</div>
    </div>
  </div>
  <div class="card  transparent">
    <div class="card-header" id="headingKeywords">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseKeywords" aria-expanded="false" aria-controls="collapseKeywords">
          Keywords &amp; Sites
        </button>
      </h5>
    </div>
    <div id="collapseKeywords" class="collapse" aria-labelledby="headingKeywords" data-parent="#accordion">
      <div class="card-body">
			<p><?php if ( $actor->Keywords != null) $this->printKeywords($actor->Keywords);  ?> </p>
			<?php if ( isset($actor->Sites) ){ ?>
			<br/>
			<h4>Further Sites:</h4>
			<ul>
			<?php
			$sites = explode(PHP_EOL, $actor->Sites);
			foreach($sites as $site):
			     $links = explode(':', $site);
			if ( !isset($links[1])) continue;
			     $links[1] = trim($links[1]);
			     if ( $links[1] != "http" && $links[1] != "https" )
			         $target = "http:".$links[1];//Todo: add further parts
			     else 
			         $target = $links[1].":".$links[2];//Todo: add further parts
			 ?>
				<li><a href='<?php print trim($target);?>' target=_blank><?php print $links[0]?></a></li>
			<?php endforeach;?>
			</ul>
			<?php }?>
			 </div>
    </div>
  </div>
  <?php if ($actor->Description != "" ){?>
  <div class="card transparent">
    <div class="card-header" id="headingDescription">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseDescription" aria-expanded="false" aria-controls="collapseDescription">
          Description
        </button>
      </h5>
    </div>
    <div id="collapseDescription" class="collapse" aria-labelledby="headingDescription" data-parent="#accordion">
      <div class="card-body">
			<p><?php print nl2br($actor->Description); ?></p>
			 </div>
    </div>
  </div>
  <?php }?>
  
  <?php if ($actor->Data != "" ){?>
  <div class="card transparent">
    <div class="card-header" id="headingData">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseData" aria-expanded="false" aria-controls="collapseData">
          Actor Data
        </button>
      </h5>
    </div>
    <div id="collapseData" class="collapse" aria-labelledby="headingData" data-parent="#accordion">
      <div class="card-body">
			<p><?php $actor->printData(); ?></p>
			 </div>
    </div>
  </div>
  <?php }?>
  
  <div class="card transparent">
    <div class="card-header" id="headingAction">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseAction" aria-expanded="false" aria-controls="collapseAction">
          Actions
        </button>
      </h5>
    </div>
    <div id="collapseAction" class="collapse" aria-labelledby="headingAction" data-parent="#accordion">
      <div class="card-body">
			<button 
				class='btn btn-secondary' 
				id='deleteActor' 
				data-id='<?php print $actor->ID_Actor;?>'
				data-name='<?php print $actor->Fullname;?>'><i class="fas fa-trash"></i>&nbsp;Delete Actor</button>
			 </div>
    </div>
  </div>
  
</div>
			
		</div>
</div><!-- row -->
<br /><br />
<div class='row'>
  <div class='card-group'>
	<?php foreach ($sets as $mset ):
 	      $set = new Episode();
 	      $set->ID_Episode = $mset->ID_Episode;
 	      $set->Added = $mset->Added;
 	      $set->Comment = $mset->Comment;
 	      $set->Description = $mset->Description;
 	      $set->Keywords = $mset->Keywords;
 	      $set->Link = $mset->Link;
 	      $set->Picture = $mset->Picture;
 	      $set->Published = $mset->Published;
 	      $set->PublisherCode = $mset->PublisherCode;
 	      $set->Rating = $mset->Rating;
 	      $set->Title = $mset->Title;
 	      $set->REF_Channel = $mset->REF_Channel;
 	      $set->Viewed = $mset->Viewed;
 	      $set->Wallpaper = $mset->Wallpaper;
 	      $set->Channel = $mset->Channel;?> 
 	      
 	      <!-- mscard -->
 	      <!-- div class="col-4"> -->
 	      <div class="card">
 	      <a href='<?php print INDEX."showepisode?id={$set->ID_Episode}"?>'>
 	      <img class="card-img-top" src='<?php print $set->getPicture(300); ?>' alt="<?php print $set->Title; ?>" ></a>
 	      <div class="card-body">
 	      <h4 class="card-title"><?php print $set->Title; $set->printWatched();?></h4>
    <p class="card-text"><?php  print ($this->cutStr($set->Description,80)); ?></p>
    <p class="card-text"><a href='<?php print INDEX."showchannel?id={$set->REF_Channel}"?>'><small class="text-muted"><?php print $set->Channel?></a></small></p>
    	<?php if ( isset($actors)){   ?>
    		<p>   
            <?php foreach ($actors as $actor){?>
				<a class='actorlink' href='<?php print INDEX;?>showactor?id=<?php print $actor['ID_Actor'];?>'><?php print $actor['Fullname']?></a>
			<?php } ?>
			</p> 
    	<?php } ?>  
    </div>
  </div>
<!--  </div> --> <?php 
 	      
 	      endforeach; ?>
	</div> <!-- card grp -->
</div>


</div><!-- container -->

<!-- Preview -->
<div class='gal-lightbox' data-imgid=1>
	<div class='gal-content'>
		<img class='gal-image' src='img/large-1.jpg' />
	</div>
	<span class='gal-close-lightbox' onclick="closeModal()">&times;</span>
</div>


<?php 
include VIEWPATH.'fragments/js.php'; 
include VIEWPATH.'fragments/confirmdelete.php';
?>
<script src="/mediadb/js/mygal.js"></script>
<script type="text/javascript">
<!-- React on the delete button -->

$('#deleteActor').click(function(){
	$('#modaltitle').text('Please confirm to delete this actor!');
    $('#confirmTxt').text('Are you sure to delete '+$('#deleteActor').data('name')+'?');
    $("#confirmDeleteModal").modal('show');	
});

    
$("#confirmDeleteBtn").click(function(){
	var values = {
    	'what': 'deleteactor',
        'mid': $('#deleteActor').data('id'),
        'purge': $('#physicallyDeleteFiles').is(':checked')
    };
        
    var result = $.ajax({
    	url:'/mediadb/ajax/update.php',
        type: 'POST',
        data: values,
        success: function() {
        	console.log("Delete OK");
        	console.log(result);
        }
	});
    $("#confirmDeleteModal").modal('hide');
    window.location = 'index.php';
});

$('#cancelWithoutConfirmationBtn').click(function(){
	$("#confirmDeleteModal").modal('hide');
});

$(function() {
  	$(".my-rating-6").starRating({
    totalStars: 5,
    initialRating: $(".my-rating-6").attr('value'),
    strokeWidth: 0,
    ratedColor: 'Orange',
    disableAfterRate: false,
    callback: function(currentRating, $el){
    	var mid = <?php print $actor->ID_Actor;?>; 
        var values = {
          		'what': 'rateactor',
        		'mid': mid,
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

$('#previewMugshot').click(function(){
	var src = $(this).data('img');
	showLightbox(src, "", 0, 0);
});

</script>

<script src="/mediadb/js/jquery.star-rating-svg.js"></script>


</body>
</html>
