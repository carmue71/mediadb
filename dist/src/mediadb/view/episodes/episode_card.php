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
<!--  </div> -->