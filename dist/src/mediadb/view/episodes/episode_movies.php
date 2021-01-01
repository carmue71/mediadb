<?php
\mediadb\Logger::debug("episode_movies.php: document started");
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
<div class = "row">
<div class="col-sm-12">
<br>
<?php 
$activeTab="Movie";
include VIEWPATH.'episodes/episode_tabs.php';

//if ( isset($movies[0])){
$vid = 0;
foreach ($movies as $video){
    $vid++;
    //$video = $movies[0];
    $fullpath="{$video['DevicePath']}files/{$video['Path']}{$video['Name']}";
    $ext = strtolower(pathinfo($fullpath, PATHINFO_EXTENSION));
    $vidtype = "";
    switch ($ext){
        case "wmv": 
            $vidtype='video/x-ms-wmv';
            break;
        case "mkv":
            #$vidtype = 'video/x-matroska';
            #break;
        case "webm":
            $vidtype='video/webm';
            break;
        
        case "mp4":
        default: 
            $vidtype='video/mp4';
    }
    ?>
    	<p align = center>
    		<video 
    			id=myVideo_<?php print $vid?> 
    			src='<?php print $fullpath?>' 
    			type='<?php print $vidtype?>' 
    			width=70% 
        		poster='<?php print WWW."ajax/getAsset.php?type=poster&file={$ms->Picture}&size=N"?>' 
        		autobuffer 
        		controls > 
        		</video>
        </p>
        <div class=row>
        	<div class='col-lg-12'>
    			<p align=center id=progressContainer>
    				<a><i class='fas fa-undo-alt activeItem' id=goback></i></a>&nbsp; 
    				<a><i class='fas fa-tachometer-alt activeItem' id=gofaster></i></a>&nbsp;
    				<a><i class='fas fa-volume-up activeItem' id=unmute></i></a>&nbsp;
    				<span id=progress>0:00</span>
    				&nbsp; 
    				<span class='fa-layers fa-fw activeItem' id='gofwd'>
    					<i class='fas fa-redo-alt'></i>
    					<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-11.5" style="font-weight:900">30s</span></span>
    				</p>
    	   	</div>
    	</div>
    <?php } ?>
</div>
</div>

</div><!-- container -->

<?php 
include VIEWPATH.'fragments/js.php'; 
include VIEWPATH.'episodes/delete_episode.php';
?>
<script src="/mediadb/js/jquery.star-rating-svg.js"></script>
<script src="/mediadb/js/togglewatch.js"></script>
<script src="/mediadb/js/js.cookie.js"></script>
	
	<script type='text/javascript'>

		lastProgress = 0;
		currentVideo = null;
		fileid = -1; /*$video[0]->ID_File*/

		$(document).ready(function () {
			console.log('Document is ready - moving to the last position:' + <?php print $movies[0]->getProgress(); ?>);
			$("#myVideo_1")[0].currentTime = <?php print $movies[0]->getProgress(); ?>;
			$("#myVideo_1")[0].volume = <?php print $this->videoVolume; ?>;  
		});
		$("#myVideo_1").on("timeupdate", function (e) {
			if ( e.target.currentTime > lastProgress + 10 ){
				$('#progress').text('Playing: ' + printStatus(e.target.currentTime, e.target.duration));
				lastProgress = e.target.currentTime;
				updateProgress(<?php print $ms->ID_Episode;?>, fileid, lastProgress);  
			}		
		});
		
		$("#myVideo_1").on("pause", function (e) {
			$('#progress').text('Paused at: ' + printStatus(e.target.currentTime, e.target.duration));
			lastProgress = e.target.currentTime;
			updateProgress(<?php print $ms->ID_Episode;?>, fileid, lastProgress);
		});

		$("#myVideo_1").on("play", function (e) {
			$('#progress').text('Playing: ' + printStatus(e.target.currentTime, e.target.duration));
			currentVideo = e.target;
			//console.log(e.target);
			lastProgress = e.target.currentTime;
			updateProgress(<?php print $ms->ID_Episode;?>, fileid, lastProgress);
		});

		$("#myVideo_1").on('ended', function (e) {
			$('#progress').text('Finished after: ' + e.target.currentTime);
			setwatched(<?php print $ms->ID_Episode;?>, true);
			lastProgress = e.target.currentTime;
			updateProgress(<?php print $ms->ID_Episode;?>, fileid, lastProgress);
		});

		$("#myVideo_1").on('volumechange', function (e) {
			console.log('Volume changed:' + e.target.volume);
			console.log('Muted:' + e.target.muted);
			if ( e.target.muted )
				Cookies.set("VideoVolume", 0, { expires: 365 });
			else
				Cookies.set("VideoVolume", e.target.volume, { expires: 365 });
		});

		$("#unmute").click(function() {
			console.log('unmute pressed');
			currentVideo.muted = false;
			Cookies.set("VideoVolume", 30, { expires: 365 });
		});

		$("#goback").click(function(){
			console.log('trying to go back 30 secs');
			currentVideo.currentTime = currentVideo.currentTime-30>0 ? currentVideo.currentTime-30:0;   
		});

		$("#gofaster").click(function(){
			console.log('going faster');
			currentVideo.defaultPlaybackRate = 2.0;
		});

		$("#gofwd").click(function(){
			console.log('going forward 30 secs');
			//TODO: check valid time
			currentVideo.currentTime = currentVideo.currentTime-30<currentVideo.duration ?
					currentVideo.currentTime+30:currentVideo.duration;
		});

		
		
		function updateProgress(msid, fid, progress){
			
			console.log(msid+"-"+ fid +":"+ progress);

			var values = {
			        'what': 'logprogress',
			        'fid':<?php print $movies[0]->ID_File;?>,
			        'progress': progress,
			    };
			var result = $.ajax({
			        url:'/mediadb/ajax/update.php',
			        type: 'POST',
			        data: values,
			        success: function() {
			            console.log("OK");
			            console.log(result);
			        }
			});
		}

		function printStatus(current, total){
			var cur = (current/3600).toFixed(0)+":"+
				((current%3600)/60).toFixed(0)+":"+(current.toFixed(0)%60);
			var to = (total/3600).toFixed(0)+":"+
				((total%3600)/60).toFixed(0)+":"+(total.toFixed(0)%60);
			var percent = (current/total*100).toFixed(0);
			return cur + "/" + to + " ("+percent+"%)"
		}
</script>
</body>
</html>
<?php \mediadb\Logger::debug("episode_movies.php: document finished");?>