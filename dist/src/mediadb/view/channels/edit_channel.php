<?php
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<?php
$activeTab = "Edit";
include VIEWPATH . 'channels/channel_tabs.php';
?>
			<form method="POST" action='<?PHP print INDEX."savechannel"?>'>
				<input type="hidden" name="id" value='<?PHP print $channel->ID_Channel; ?>' />
				<div class='form-row'>
					<div class='col-sm-6'>
						<div class=form-group>
							<label class="col-form-label" for='name'>Name</label> 
							<input class='form-control' id='name' type="text" name="name" size=50 required value='<?PHP print $channel->Name; ?>'
								placeholder='Unique Name for the channel' />
						</div><!-- form-group -->
					</div> <!-- col -->
					
					<div class='col-sm-6'>
						<div class=form-group>
							<label class="col-form-label" for='site'>Site</label> 
							<input class='form-control' id='site' type="text" name="site" size=50 required value='<?PHP print $channel->Site; ?>'
								placeholder='The Site of the sudio' />
								<?php if ( isset($channel->Site) &&$channel->Site != "" ){?>
								<small><a href='<?php print "http://{$channel->Site}"?>' target=_blank ><?php print "http://{$channel->Site}"?></a></small>
								<?php }?>
						</div><!-- form-group -->
						<div class=form-group>
							<label class="col-form-label" for='setpath'>Default Set Path</label> 
							<input class='form-control' id='setpath' type="text" name="setpath" size=50 value='<?PHP print $channel->DefaultSetPath; ?>'
								placeholder='Default path to an episode of this channel' />
								<?php if ( isset($channel->DefaultSetPath) &&$channel->DefaultSetPath != "" ){?>
								<small><a href='<?php print "http://{$channel->DefaultSetPath}"?>' target=_blank ><?php print "http://{$channel->DefaultSetPath}"?></a></small>
								<?php }?>
						</div><!-- form-group -->
					</div> <!-- col -->
				</div><!-- form-row -->
				
				<div class='form-row'>
					<div class='col-sm-6'>
						<div class=form-group>
							<label class="col-form-label" for='type'>Type</label> 
							<select class='form-control' id='type' name="type">
								<?php foreach ( array("Studio","Series","Channel") as $st){
								    print "<option value='{$st}' "; if ($channel->ChannelType==$st) print "selected "; print">{$st}</option>";
								}?>
						</select>
						</div><!-- form-group -->
					</div> <!-- col -->
				</div><!-- form-row -->
				
				<div class='form-row'>
					<div class='col-sm-2'>
					<img src='/mediadb/ajax/getAsset.php?type=logo&file=<?php print $channel->Logo;?>&size=S' />
					</div><!-- col -->
					<div class='col-sm-4'>
						<div class=form-group>
							<label class="col-form-label" for='logo'>Logo</label> 
							<input class='form-control' id='logo' type="text" name="logo" size=50 value='<?PHP print $channel->Logo; ?>'
								placeholder='The logo of this Channel' />
						</div><!-- form-group -->
					</div> <!-- col -->
					
					<div class='col-sm-2'>
					<img src='/mediadb/ajax/wallpaper.php?file="<?php print $channel->Wallpaper;?>"&size=S' width=300px />
					</div><!-- col -->
					<div class='col-sm-4'>
						<div class=form-group>
							<label class="col-form-label" for='wallpaper'>Wallpaper</label> 
							<input class='form-control' id='wallpaper' type="text" name="wallpaper" size=50 value='<?PHP print $channel->Wallpaper; ?>'
								placeholder='A wallpaper for this channel' />
						</div><!-- form-group -->
					</div> <!-- col -->
					
					
					</div><!-- row -->
				<div class='form-row'>
					<div class='col-sm-12'>
					<div class=form-group>
						<label class="col-form-label" for='comment'>Comment</label> 
							<textarea class='form-control' id='comment' name="comment"><?PHP print $channel->Comment; ?></textarea>
						</div><!-- form-group -->
					</div> <!-- col -->
					</div><!-- row -->
				
				
				
				
				
				<div class='form-row'>
					<input class='form-control  btn-primary' type="submit" />
				</div>
				<!-- form-row -->
			</form>	
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>
