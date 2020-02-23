  
<div class="row">
	<div class="col-sm-8">
		<h3><?php if (isset($ms->Title) ) {
		      print $ms->Title."&nbsp;";?>
		         <i class='far fa-circle' 
		         	style='color:Grey;<?php if ( $ms->isWatched() ) print "display:none"; ?>' 
		         	id='statusflagunwatched'
		         	onClick='setwatched(<?php print $ms->ID_Episode?>, true)'></i>
		      	 <i class='fas fa-check-circle' 
		      	 	style='color:Gold;<?php if ( !$ms->isWatched() ) print "display:none"; ?>;' 
		      	 	id='statusflagwatched'
		      	 	onClick='setwatched(<?php print $ms->ID_Episode?>, false)'></i>
		      <?php }?>
		</h3>
	</div>
	<div class="col-sm-4">
		<?php if ( isset($ms->Logo)){?>
		<a href='showchannel?id=<?php print $ms->REF_Channel; ?>'> 
			<img 
				align=right
				alt='<?php print $ms->Channel;?>' 
				src='<?php print WWW."ajax/getAsset.php?type=logo&size=N&file={$ms->Logo}";?>'>
		</a>
		<?php }?>
	</div>
</div>

    
<ul class="nav nav-tabs">
    <!-- Details -->
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Details') print 'active'?> " href='<?php print INDEX."showepisode?id={$ms->ID_Episode}"; ?>'><i class="fas fa-info-circle"></i> Details</a>
  </li>
  <!-- Movie(s) -->
  <li class="nav-item">
  	<a class='nav-link <?php if ($numberOfMovies<1) print "disabled";  if ($activeTab == "Movie") print "active"?>' 
  		href='<?php print INDEX."movies?id={$ms->ID_Episode}"; ?>'> <i class="fas fa-file-video"></i> Movie 
  	<span class='badge <?php if ($numberOfMovies<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $numberOfMovies;?></span>
  	<?php if ( $numberOfHDVideos > 0){ ?>
  		<span class='badge badge-info'>HD</span>
  		<?php } else if ( $numberOfHiVideos > 0){ ?>
  		<span class='badge badge-light'>hq</span>
  		<?php }?>
  	</a>  </li>  
  
  <!-- Pictures -->
  <li class="nav-item">
    <a class='nav-link <?php if ($numberOfPix<1) print "disabled";  if ($activeTab == "Pictures") print "active"; ?>' 
    	href='<?php print INDEX."showpix?id={$ms->ID_Episode}"; ?>'><i class="fas fa-file-image"></i> Pictures
    <span class='badge <?php if ($numberOfPix<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $numberOfPix;?></span></a> 
  </li>
  
  <!-- All Files -->
  <li class="nav-item">
    <a class='nav-link <?php if ($activeTab == "Files") print "active"; ?>' 
    	href='<?php print INDEX."showfiles?id={$ms->ID_Episode}"; ?>'><i class="fas fa-file"></i> Files
    <span class='badge <?php if ($numberOfFiles<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $numberOfFiles;?></span></a> 
  </li>
  
  <!-- Edit -->
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Edit') print 'active'?> " href='<?php print INDEX."editepisode?id={$ms->ID_Episode}"; ?>'><i class="fas fa-pencil-alt"></i> Edit</a>
  </li>
</ul>
<br/>