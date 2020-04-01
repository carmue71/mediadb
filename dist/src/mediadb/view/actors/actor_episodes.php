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
 <div class='row'>
 <?php 
 $target = "listepisodesforactor?id={$actor->ID_Actor}&";
 include VIEWPATH . 'episodes/options_episodes.php'; 
 ?>
</div><!-- row -->
 <div class='row'>
 	
 	<?php
		if ( $this->msStyle == 'card' ){
		    print "<div class='card-group'>";
		    $i = 2;
		} else {
		    print "<div class='col-lg-12'>";
		}  
 	
 	  foreach ($sets as $set ):
 	   
 	      switch ( $this->msStyle){
 	          case 'card':
 	              $i = ($i+1) % 3;
 	              if ( $i == 0 ){
 	                  print "</div><br/><div class='card-group'>";
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
 	      }//switch
 	  endforeach;
 	  ?> </div>
 	</div><!-- row -->
	<div class="row">
		<div class="col-md-12">
        		<?php $this->printPagination($this->msLastPage, $this->episodeRepository); ?>
            </div>
	</div>
<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>

