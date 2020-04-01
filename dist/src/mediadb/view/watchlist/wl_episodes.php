<?php

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/wallpaper.php?file=watchlist.jpg') no-repeat center center fixed;"
        ."background-size: cover;\"";
        
        include VIEWPATH.'fragments/header.php';
        include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
<div class="row">
	<div class="col-12">
		<h1><?php print $wl->Title; ?></h1>
	</div>
</div>

<?php
$activeTab = "Media Sets";
include VIEWPATH . 'watchlist/wltabs.php';
?>
	<div class='row'>
		<div class='col-lg-12'>
		<?php
		  foreach($episodes as $set):
		      include VIEWPATH.'watchlist/wl_episode_row.php';
		      //include VIEWPATH.'episodes/episode_row.php';
			endforeach;?>
			</div>
	</div>
	<!-- row -->
	<div class="row">
		<div class="col-md-12">
        		<?php
        		  $target = "listepisodesforwatchlist?id={$wl->ID_WatchList}&";
        		  $this->printPagination($this->msLastPage, $this->episodeRepository); 
        		 ?>
            </div>
	</div>
</div><!-- container -->
<?php include VIEWPATH.'fragments/js.php'; ?>
<script type="text/javascript">
<!--

//-->
function removeFromWatchlist(wlid, msid, pos){
	var values = {
        'what': 'removefromwatchlist',
        'msid': msid,
        'wlid': wlid,
        'pos': pos,
    };
    //alert("Values: "+msid+" "+wlid+" "+pos);
    var result = $.ajax({
        url:'/mediadb/update.php',
        type: 'POST',
        data: values,
        error: function(){
    		console.log(result);
            alert("Not removed from Watchlist!");
    	}
    });
    console.log(result);    
    window.location.reload(false); 
    //TODO: better: remove the respective row
}
</script>

</body>
</html>