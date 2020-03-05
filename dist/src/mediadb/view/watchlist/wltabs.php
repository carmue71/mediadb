<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Details') print ' active'; else if ($wl->ID_WatchList== -1) print " disabled";?> " href='<?php print ($wl->ID_WatchList == -1)?"#":INDEX."showwatchlist?id={$wl->ID_WatchList}"; ?>'>Details</a>
  </li>
  <li class="nav-item">
    	<a class="nav-link <?php if ($activeTab == 'Episodes') print ' active'; else if ($wl->ID_WatchList== -1) print " disabled"; ?>" href='<?php print ($wl->ID_WatchList == -1)?"#":INDEX."listepisodesforwatchlist?id={$wl->ID_WatchList}"; ?>'>Episodes
    	<?php if ($numberOfSets>-1) {?>
    		<span class='badge <?php if ($numberOfSets<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $numberOfSets;?></span>
    	<?php } ?>
    	</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Edit') print ' active';?>" href='<?php print INDEX."editwatchlist?".(($wl->ID_WatchList == -1)?"name={$wl->Title}":"id={$wl->ID_WatchList}"); ?>'>Edit</a>
  </li>
</ul>
