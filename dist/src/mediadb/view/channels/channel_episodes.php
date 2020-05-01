<?php

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$channel->Wallpaper}') no-repeat center center fixed;"
    ."background-size: cover;\"";
    

    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
<div class="row">
	<div class="col-8">
		<h1><?php print $channel->Name; ?></h1>
	</div>
	<div class="col-4">
		<a href='http://<?php print $channel->Site; ?>' target=_blank><img
			src='/mediadb/ajax/getAsset.php?type=logo&file=<?php print $channel->Logo;?>&size=S'
			align=right alt='<?php print $channel->Name;?>'></a>
			<small><a href='http://<?php print $channel->Site; ?>' target=_blank><?php print $channel->Site?></a></small>
	</div>
</div>

<?php
    $activeTab = "Media Sets";
    include VIEWPATH . 'channels/channel_tabs.php';
    $target = "listepisodesforchannel?id={$channel->ID_Channel}&";
    $episodeRepository  = $this->episodeRepository;
    $episode_list = $episodes;
    $display_style = $this->msStyle;
    $msLastPage = $this->msLastPage;
    include VIEWPATH.'episodes/display_episodes.php'; ?>
	
	
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
