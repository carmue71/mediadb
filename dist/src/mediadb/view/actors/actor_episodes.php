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
$activeTab="Episodes";
include VIEWPATH.'actors/actor_tabs.php';
?>
 <div class='row'>
 	<div class='col-sm-12'>
 	<h4>Actor <i><?php print $actor->Fullname?></i> appears in the following sets:</h4>
 	</div><!-- col -->
 </div><!-- row -->
 <?php 
 $target = "listepisodesforactor?id={$actor->ID_Actor}&";
 $episode_list = $sets;
 $display_style = $this->msStyle;
 $episodeRepository = $this->episodeRepository;
 $msLastPage = $this->msLastPage; 
 include VIEWPATH.'episodes/display_episodes.php'; ?>
	
 
 </div><!-- container -->
<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>

