<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Details') print ' active'; else if ($channel->ID_channel== -1) print " disabled";?> " href='<?php print ($channel->ID_Channel== -1)?"#":INDEX."showchannel?id={$channel->ID_Channel}"; ?>'>Details</a>
  </li>
  <li class="nav-item">
    	<a class="nav-link <?php if ($activeTab == 'Episodes') print ' active'; else if ($channel->ID_Channel== -1) print " disabled"; ?>" href='<?php print ($channel->ID_Channel == -1)?"#":INDEX."listepisodesforchannel?id={$channel->ID_Channel}"; ?>'>Episodes
    	<?php if ($numberOfSets>-1) {?>
    		<span class='badge <?php if ($numberOfSets<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $numberOfSets;?></span>
    	<?php } ?>
    	</a>
  </li>
  <li class="nav-item">
    	<a class="nav-link <?php if ($activeTab == 'Files') print ' active'; else if ($channel->ID_Channel== -1) print " disabled"; ?>" 
    		href='<?php print ($channel->ID_Channel == -1)?"#":INDEX."filesforchannel?id={$channel->ID_Channel}"; ?>'>Files
    		<?php if ($totalNumberOfFiles>-1) {?>
			<span class='badge <?php if ($totalNumberOfFiles<1) print "badge-warning"; else print"badge-success"; ?>'><?php print $totalNumberOfFiles;?></span>
    		<?php } ?></a></li>
  <li class="nav-item">
    <a class="nav-link <?php if ($activeTab == 'Edit') print ' active';?>" href='<?php print INDEX."editchannel?".(($channel->ID_Channel == -1)?"name={$channel->Fullname}":"id={$channel->ID_Channel}"); ?>'>Edit</a>
  </li>
</ul>
