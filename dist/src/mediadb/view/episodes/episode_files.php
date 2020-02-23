<?php
include VIEWPATH.'fragment/header.php';
include VIEWPATH.'fragment/navigation.php';
?>
<div class="container-fluid">

<?php 
$activeTab="Files"; 
include VIEWPATH.'episodes/episode_tabs.php';
$target = "showfiles?id={$ms->ID_Episode}&";?> 
<div class="row">
<?php include VIEWPATH.'files/file_options.php';?>
</div> <!-- row -->

<div class="row">
	<div class="col-md-12">
		<?php $this->printPagination($lastpage);?>
	</div>
</div>

<?php  
    switch ( $this->fileStyle ){
    case 'Preview':
        include VIEWPATH."files/file_preview.php";
        break;
    case 'List':
    default:
        include VIEWPATH."files/list_files.php";
        break;
        }//switch
    ?>
    
	<div class="row">
        	<div class="col-md-12">
        		<?php $this->printPagination($lastpage);?>
			</div>
	</div>      
</div><!-- container -->


<?php 
    include VIEWPATH.'fragments/js.php';
    include VIEWPATH.'fragments/confirmdelete.php';
?>

<script src="/mediadb/js/togglewatch.js"></script>
<script src="/mediadb/js/deletefile.js"></script>
<script src="/mediadb/js/js.cookie.js"></script>

</body>
</html>