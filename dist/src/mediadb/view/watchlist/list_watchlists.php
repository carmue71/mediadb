<?php
$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/wallpaper.php?file=watchlist.jpg') no-repeat center center fixed;"
        ."background-size: cover;\"";
        
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
<div class="col-sm-12">
	<h1>List of your Watch Lists</h1>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card-group">
			<table> 
		<?php foreach ($watchlists as $wl) :?>    
            <tr>
            <td> &nbsp;&nbsp;
            	<a href='<?php print INDEX."showwatchlist?id={$wl->ID_WatchList}";?>'>
                <i class="fas fa-binoculars fa-3x"></i>   
                </a>
                &nbsp;&nbsp;
                </td>    
            <td> 
            <a href='<?php print INDEX."showwatchlist?id={$wl->ID_WatchList}";?>'><?php print $wl->Title; ?></a><br/>
            <small><?php print $wl->Description; ?></small></td>
            <td>
            <a class='btn btn-secondary' href='<?php print INDEX."editwatchlist?id={$wl->ID_WatchList}"; ?>'><i class="fas fa-pen-square"></i></a>           
			<a href='#' value='<?php print "{$wl->ID_WatchList}"; ?>'><i class="fas fa-trash" style="color:Tomato"></i></a>
            </td>
            </tr>
    	<?php endforeach;?>
    		</table>
        </div><!-- card group -->
	</div>	<!-- col -->
</div><!-- row -->
<div class="row">
	<div class="col-md-12">
		<?php $this->printPagination();?>
    </div><!-- col -->
</div> <!-- row -->
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>

