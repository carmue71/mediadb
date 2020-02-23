<?php
$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file=actor.png') no-repeat center center fixed;"
    ."background-size: cover;\"";
    
    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container">
<div class="row">
<?php 
$target = "listactors?";
include VIEWPATH.'actors/option_actors.php';
?>
</div> <!-- row -->
<div class="row">
	<div class="col-lg-12">
		<h1>List of Actors</h1>
	</div><!-- col -->
</div><!-- row header -->
<?php 
if ( $this->actorStyle != "Continuous"){?>
    <div class="row">
    <div class="col-md-12">
    <?php $this->printPagination();?>
	</div>
</div>
    
<?php }
 switch ($this->actorStyle ){
    case 'Table':?>
        <div class="row">
        	<table width=100%>
        		<tr>
        			<th>Mugshot</th>
        			<th>Fullname</th>
        			<th>#Sets</th>
        			<th>Rating</th>
        		</tr>
        	<?php foreach ($params['entries'] as $actor) :?>
        		<tr>
                	<td align=center><a href='<?php print INDEX."showactor?id={$actor->ID_Actor}"?>'>
                		<img src='/mediadb/ajax/getAsset.php?type=thumbnail&file=<?php print $actor->Thumbnail;if ( isset($actor->Gender) ) print "&gender={$actor->Gender}"; ?>&size=S'></a></td>
               		<td><h5><a href='<?php print INDEX."showactor?id={$actor->ID_Actor}"?>'>
               			<?php print $actor->Fullname?></a></h5>
               			<small><?php print($this->cutStr($actor->Description,200,20));?></small> 
					</td>
					<td></td>
					<td><div class="my-rating-6" value='<?php print $actor->Rating; ?>'> </div></td>
				</tr>
            <?php endforeach; ?>
        	</table>
		</div><!-- row -->
		<?php break;
    case 'Card': ?>
    	<div class="row">
    	<table class=actor_grid><tr>
    	<?php 
    	$col = 0;
    	$cols_per_row = 5;
    	foreach ($params['entries'] as $actor){?>
    		<td width=100% align=center>
    			<a href='<?php print INDEX."showactor?id={$actor->ID_Actor}"?>'	class="mb-1">
    			<img
    				alt='<?php print($this->cutStr($actor->Description,200,20));?>'  
    				src='/mediadb/ajax/getAsset.php?type=mugshot&file=<?php print $actor->Mugshot;if ( isset($actor->Gender) ) print "&gender={$actor->Gender}"; ?>&size=M'  
    				align=left>
    				<br/>
    			</a>
				<p> <a href='<?php print INDEX."showactor?id={$actor->ID_Actor}"?>' class="mb-1"> 
      				<?php print $actor->Fullname?></a>
				</p>
    		</td>
    	<?php 
    	   $col++;
    	   if ( $col == $cols_per_row ){
    	       $col = 0;?>
    			</tr><!-- class="row"-->
    			<tr>
    			<?php        
    	    }
    	}?>
    	</tr></table></div><!-- class="row"-->	    
        <?php break;    
    case 'List':
    default: ?> 
	<div class="row">
		<div class="col-lg-12">
			<ul class='list-group'>
        		<?php foreach ($params['entries'] as $actor) :?>
        		  <li 	class='list-group-item list-group-item-action flex-column align-items-start transparent'>
					<table>
						<tr>
							<td><img src='/mediadb/ajax/getAsset.php?type=mugshot&file=<?php print $actor->Mugshot;if ( isset($actor->Gender) ) print "&gender={$actor->Gender}"; ?>&size=S' align=left></td>
							<td width=100%>
								<div class="d-flex w-100 justify-content-between">
								<h5> <a href='<?php print INDEX."showactor?id={$actor->ID_Actor}"?>'	class="mb-1"> 
      								<?php print $actor->Fullname?></a>
								</h5>
								<small><?php print $actor->Gender;?></small> 
							</div>
							<p class="mb-1"><?php print($this->cutStr($actor->Description,200,20));?></p>
								<small><?php if (isset($actor->Keywords) ) $this->printKeywords($actor->Keywords)?></small>
							</td>
						</tr>
					</table>
				</li>
        		<?php endforeach; ?>
        	</ul>
		</div><!-- col -->
	</div><!-- row -->

<?php }//switch ?>

<?php if ( $this->actorStyle != "Continuous"){?>

<div class="row">
	<div class="col-md-12">
		<?php $this->printPagination();?>
	</div>
</div>
<?php }?>
</div><!-- container -->


<?php include VIEWPATH . 'fragments/js.php';?>
<script src="/mediadb/js/jquery.star-rating-svg.js"></script>
</body>
</html>