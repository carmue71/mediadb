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
?>
	<div class='row'>
		<?php  
		         $target = "listepisodesforchannel?id={$channel->ID_Channel}&";
		          include VIEWPATH . 'episodes/options_episodes.php'; 
		?>
		<?php
		if ( $this->msStyle == 'card' ){
		    print "<div class='card-group'>";
		    $i = 2;
		} else {
		    print "<div class='col-lg-12'>";
		}
				foreach($episodes as $set): 
				    switch ( $this->msStyle){
				        case 'card':
				            $i = ($i+1) % 3;
				            if ( $i == 0 ){
				                print "</div><br/> <div class='card-group'>";
				            }
				            include VIEWPATH."episodes/episode_card.php";
                            break;
				        case 'list':
				            include VIEWPATH.'episodes/episode_row.php';
				            break;
				        case 'plain':
				        default:
				            print "<div class='list-group'>";
				            include VIEWPATH."channels/episodes_for_channel.php";
				            print "</div> <!-- list group -->";
				    }
				endforeach;
				print "</div>";  ?>
	</div>
	<!-- row -->
	<div class="row">
		<div class="col-md-12">
        		<?php $this->printPagination($this->msLastPage, $this->mediaSetRepository); ?>
            </div>
	</div>
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
