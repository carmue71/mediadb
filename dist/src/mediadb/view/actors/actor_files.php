<?php
//
// Actor/showfiles.php
//

$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/ajax/wallpaper.php?file={$actor->Wallpaper}') no-repeat center center fixed;"
    ."background-size: cover;\"";
    
    
    include VIEWPATH.'fragments/header.php';
    include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">
<div class="row">
<?php 
$activeTab="Files";
include VIEWPATH.'actors/actor_tabs.php';
?>
</div> <!-- row -->
<div class='row'>

<?php 
    $target = "filesforactor?id={$actor->ID_Actor}&"; 
    include VIEWPATH.'files/file_options.php';
?>

</div> <!-- row -->

<?php     
    switch ( $this->fileStyle ){
    case 'Preview':
        include VIEWPATH."files/file_preview.php"; ?>
        <div class="row">
        	<div class="col-md-12">
        		<?php $this->printPagination($lastpage);?>
			</div>
		</div>
        <?php 
        break;
    case 'Gallery':
        include VIEWPATH."fragments/imagegallery.php";
        break;
    case 'Slideshow':
        include VIEWPATH."fragments/slideshow.php";
        break;
    case 'Continuous':
        //break;
    case 'List':
    default:
        include VIEWPATH."file/list_files.php";
        break;
        }//switch
    ?>
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
?>

<script src="/mediadb/js/mygal.js"></script>

<script type="text/javascript">
	fid = -1;

	$('.viewFile').click(function(){
		var src = $(this).data('img');
		var title = $(this).data('title');
		var id = $(this).data('imgid');
		currentPos = $(this).data('pos');
		//$(this).addClass('activeThumb');
		console.log(this);
		showLightbox(src, title, id, currentPos);
	});
	
	$("#selectAll").change(function(){ 
		$(".fileselector").prop('checked', this.checked); 
	});

	//TODO: implement mass deletion
	
	$(".deleteFile").click(function(){
		//handles the delete button after each file 
		
		fid = $(this).data('imgid');

		console.log("asking user if he wandts to remove file "+fid+";");
		$("#confirmDeleteModal").modal('show');
	});

	$("#confirmDeleteBtn").click(function(){
		var values = {
	            'what': 'deletefile',
	            'fid': fid,
	            'purge': $('#physicallyDeleteFiles').is(':checked'),
	        };
	        
	        var result = $.ajax({
	            url:'/mediadb/ajax/update.php',
	            type: 'POST',
	            data: values,
	            success: function() {
	                console.log("File "+fid+" removed");
	                //remove row
	                $('#row_'+fid).remove();
	                console.log(result); 
	            }
	        });
		$("#confirmDeleteModal").modal('hide');
	});

	$('#cancelWithoutConfirmationBtn').click(function(){
		$("#confirmDeleteModal").modal('hide');
	});


	function changeImage(i){
		var pos = getNextPos();
		//console.log(pos);
		var values = {
	    		'actorid': <?php print $actor->ID_Actor;?>,
	            'pos': pos
	        };
	        
	        var result = $.ajax({
	            url:'/mediadb/ajax/slideshow.php',
	            type: 'POST',
	            data: values,
	            success: function(response) {
					if ( response.hasOwnProperty('error') ){
						console.log('Invalid URL:');
						console.log(response);
					} else {
						//console.log(response);
			    		var obj = $.parseJSON(response);
			    		var currentID = obj['id'];
			    		setCurrentPos(obj['pos']); //can be changed, due to over/underrun
			    		var src ="/mediadb/ajax/image.php?path="+obj['path']+"&type=img"; 
			     		//console.log(src);
			     		$('.gal-image').attr('src', src);
			     		$('.gal-lightbox').attr('data-imgid', currentID);
			     		$('.gal-text').text(obj['title']);
					}
	            },
	        	error: function(e, status, error){
					alert(status)
					alert(error);
					console.log(status+"\n"+error);
				}
	        });
	}
		
</script>


</body>
</html>
