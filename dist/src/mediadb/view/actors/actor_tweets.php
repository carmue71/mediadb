<?php

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$actor->Wallpaper}') no-repeat center center fixed;"
    ."background-size: cover;\"";
    
    
    include VIEWPATH.'fragment/header.php';
    include VIEWPATH.'fragment/navigation.php';
    ?>

<div class="container-fluid">

<?php 
$activeTab="Tweets";
include VIEWPATH.'actors/actor_tabs.php';
if ( $actor->Twitter[0] == '@')
    $twitteraccount = substr($actor->Twitter, 1);
else 
    $twitteraccount = $actor->Twitter;
?>
 <div class="row">
		<div class="col-sm-3">
			<h1> <?php print $actor->Fullname; ?></h1>
			<a href='/mediadb/ajax/getAsset.php?type=mugshot&file=<?php print $actor->Mugshot; if ( isset($actor->Gender) ) print "&gender={$actor->Gender}";?>&size=N'>
				<img class="img-thumbnail" src='/mediadb/ajax/getAsset.php?file=<?php print $actor->Mugshot; if ( isset($actor->Gender) ) print "&gender={$actor->Gender}";  ?>&type=mugshot'>
			</a>
		</div>
		<div class="col-sm-9">
			<a class="twitter-timeline" 
				href="https://twitter.com/<?php print $twitteraccount?>?ref_src=twsrc%5Etfw">Tweets by <?php $actor->Fullname?></a> 
		</div><!-- col -->
    </div><!-- row -->
</div><!-- container -->

<?php include VIEWPATH.'fragment/js.php'; ?>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

</body>
</html>
