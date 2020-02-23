<?php
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>
<div class="container-fluid">

<?php 
$activeTab="Pictures"; 
include VIEWPATH.'episodes/episode_tabs.php';
$target = "showpix?id={$ms->ID_Episode}&";?> 
<div class="row">
<?php include VIEWPATH.'fragments/pix_options.php';?>
</div> <!-- row -->

<div class="row">
	<div class="col-md-12">
		<?php $this->printPagination($lastpage);?>
	</div>
</div>

<?php  
    switch ( $this->pixStyle ){
    case 'Slideshow':
        include VIEWPATH."fragments/slideshow.php";
        break;
    case 'Gallery':
        ?>
        <div class="row justify-content-center">
			<?php
                    if ($files != null)
                        $i = 2;
                        $curPos = $offset;
                        foreach ($files as $file) {
                            if ( $file->REF_Filetype <> 2 && $file->REF_Filetype <> 3 ){
                                $curPos++;
                            continue;
                        //TODO: Draw an dummy Thumb
                    }?>
        			<div class='gal-thumbcontainer'
            			data-title='<?php print $file->Name;?>'
						data-img='/mediadb/ajax/image.php?path=<?php print $file->getInternalPath();?>&type=img'
            			data-imgid=<?php print $file->ID_File;?>
            			data-type=<?php print $file->REF_Filetype;?>
            			data-pos='<?php print $curPos;?>'>
            			<img src='/mediadb/ajax/image.php?path=<?php print $file->getInternalPath();?>&type=lgthumb' class='gal-thumbnail'/>
						<p class='gal-thumb-title'><?php print $file->Name;?></p>
					</div>
    			<?php
    			 $curPos++;
              } ?>
    		</div><!-- row -->
        <?php 
        break;
    case 'Continuous':
        //break;
    case 'Preview':
    default:
        include VIEWPATH."files/file_preview.php";
        break;
        }//switch
    ?>
    
<div class="row">
        	<div class="col-md-12">
        		<?php $this->printPagination($lastpage);?>
			</div>
</div>
        

</div><!-- container -->

<!-- Lightbox -->
<div class='gal-lightbox' data-imgid=1>
	<div class='gal-content'>
		<img class='gal-image' src='img/large-1.jpg' />
		<p class='gal-text'>A cute Fox</p>
	</div>
	<span class='gal-close-lightbox' onclick="closeModal()">&times;</span>
	<a class="gal-prev-button" onclick="changeImage(-1)">&#10094;</a>
	<a class="gal-next-button" onclick="changeImage(1)">&#10095;</a>
</div>

<?php 
    include VIEWPATH.'fragments/js.php';
    include VIEWPATH.'fragments/confirmdelete.php';
    include VIEWPATH.'fragments/slideshowoptions.php';
?>

<script src="/mediadb/js/togglewatch.js"></script>
<script src="/mediadb/js/mygal.js"></script>
<script src="/mediadb/js/js.cookie.js"></script>

<?php include VIEWPATH.'fragments/galscript.php'; ?> 
</body>
</html>
