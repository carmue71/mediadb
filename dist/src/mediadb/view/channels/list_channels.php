<?php
$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file=default.png') no-repeat center center fixed;"
        ."background-size: cover;\"";

        include VIEWPATH.'fragments/header.php';
        include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
<div class="row">
	<?php 
        $target = "listchannels?";
        include VIEWPATH.'channels/options_channel.php';
    ?>
</div>
<div class="row">
	<div class="col-lg-12">
	<h1>List of Channels</h1>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $this->printPagination();?>
    </div><!-- col -->
</div> <!-- row -->
<!--  div class="row" -->
<div class="col-lg-12">
	<div class="card-group"> 
		<?php 
		foreach ($channels as $channel) :  ?>  
            <!-- single channel as card -->
			<div class="col-sm-4">
				<div class="card transparent">
					<a href='<?php print INDEX . "showchannel?id={$channel->ID_Channel}"?>'>
						<img class="card-img-top"
							src='<?php print WWW."ajax/getAsset.php?type=logo&size=S&file={$channel['Logo']}";?>'
							alt=<?php print $channel['Name'];?>>
					</a>
					<div class="card-body">
						<h4 class="card-title"><?php print $channel['Name'];?></h4>
						<p class="card-text"><?php print nl2br(cutStr($channel->Comment, 80,10)) ?></p>
					</div>
				</div>
			</div>
    	<?php endforeach;?>
        </div><!-- card group -->
</div>
<!-- row -->
<div class="row">
	<div class="col-md-12">
		<?php $this->printPagination();?>
    </div><!-- col -->
</div> <!-- row -->
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
