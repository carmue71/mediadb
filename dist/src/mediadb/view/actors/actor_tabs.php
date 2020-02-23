<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Details') print ' active'; else if ($actor->ID_Actor== -1) print " disabled";?> " href='<?php print ($actor->ID_Actor== -1)?"#":INDEX."showactor?id={$actor->ID_Actor}"; ?>'>Details</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Episodes') print ' active'; else if ($actor->ID_Actor== -1) print " disabled"; ?>" 
    	href='<?php print ($actor->ID_Actor == -1)?"#":INDEX."listmediasetsforactor?id={$actor->ID_Actor}"; ?>'>Episodes
    <?php if ($numberOfSets>-1) {?>
    	<span class='badge <?php if ($numberOfSets<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $numberOfSets;?></span>
    <?php } ?> </a></li>
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Tweets') print ' active'; else if ($actor->ID_Actor== -1 || $actor->Twitter=="" ) print " disabled";?> "
    	href='<?php print ($actor->ID_Actor== -1)?"#":INDEX."tweetsfromactor?id={$actor->ID_Actor}"; ?>'>Tweets <i class="fab fa-twitter-square"></i></a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Files') print ' active'; else if ($actor->ID_Actor== -1) print " disabled";?> " 
    	href='<?php print ($actor->ID_Actor== -1)?"#":INDEX."filesforactor?id={$actor->ID_Actor}"; ?>'>Files 
    	<?php if ($totalNumberOfFiles>-1) {?>
			<span class='badge <?php if ($totalNumberOfFiles<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $totalNumberOfFiles;?></span>
    	<?php } ?></a></li>
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Edit') print ' active';?>" href='<?php print INDEX."editactor?".(($actor->ID_Actor == -1)?"name={$actor->Fullname}":"id={$actor->ID_Actor}"); ?>'>Edit</a>
  </li>
</ul>