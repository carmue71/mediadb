<!-- galscript.php -->
<script type="text/javascript">

$('.viewFile').click(function(){
    var src = $(this).data('img');
    var title = $(this).data('title');
    var id = $(this).data('imgid');
    var filetype = $(this).data('type');
    //TODO: get the mime-type
    var mime_type = 'video/mp4'
    currentPos = $(this).data('pos');
    console.log(this);
    if ( filetype == 3 ){
        
        $('.gal-content').html("<video id=myVideo_1 class='gal-image' src='img/large-1.jpg' type='"+mime_type+"' "
            + "width=70% poster='img/large-1.jpg' autobuffer controls> </video>");
    }
    console.log(src);
    console.log(title);
    console.log(id);
    console.log(currentPos);
    showLightbox(src, title, id, currentPos);
});

function togglefullscreen(elem){
	console.log('toggling fullscreen');
}
    
function changeImage(i){
	 var pos = getNewPos(i);
		//console.log(pos);
	    var values = {
	    	'msid': <?php print $ms->ID_Episode;?>,
		    'pos': pos
		};
		//console.log("Values:", values);
       var result = $.ajax({
	            url:'/mediadb/ajax/slideshow.php',
	            type: 'POST',
	            data: values,
	            success: function(response) {
					if ( response.hasOwnProperty('error') ){
						console.log('Invalid URL:');
						console.log(response);
					} else {
						var obj = $.parseJSON(response);
			    		var currentID = obj['id'];
			    		var filetype = obj['filetype'];
			    		//TODO: Extract Mime-Type
			    		var mime_type = ""
			    		if ( filetype == 3){ //file is a video
			    			mime_type = 'video/mp4'
							$('.gal-content').html("<video id=myVideo_1 class='gal-image' src='img/large-1.jpg' type='"+mime_type+"' "
					    			+ "width=70% poster='img/large-1.jpg' autobuffer controls> </video>");
			    		} else if ( filetype == 2 ){ //file is an image
			    			$('.gal-content').html("<img class='gal-image' src='img/large-1.jpg' /> <p class='gal-text'>some title</p>");
			    		} else {
				    		//todo: do nothing
			    			$('.gal-content').html("<img class='gal-image' src='img/large-1.jpg' /> <p class='gal-text'>some title</p>");
			    		}
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

	$('#slideshowOptionsBtn').click(function(){
		console.log("Showing Slide Show Options");
		var ro = Cookies.get('SlideShowRandom');
		$('#ss_random_order').prop('checked', ro=='true');
		var ap = Cookies.get('SlideShowAutoplay');
		$('#ss_autoplay').prop('checked', ap=='true');
		//TODO: check for proper value otherwise set to 15
		$('#ss_interval').val(Cookies.get('SlideShowInterval'));
		$("#slideshowOptionsDlg").modal('show');
	});
	
	$('#ssoptions_okbtn').click(function(){
		console.log("Hiding Slide Show Options & getting data");
		Cookies.set('SlideShowRandom', $('#ss_random_order').is(':checked'));
		Cookies.set('SlideShowAutoplay', $('#ss_autoplay').is(':checked'));
		Cookies.set('SlideShowInterval', $('#ss_interval').val());
		$("#slideshowOptionsDlg").modal('hide');
	});

	$('#ssoptions_cancelbtn').click(function(){
		console.log("Hiding Slide Show Options");
		$("#slideshowOptionsDlg").modal('hide');
	});
		
</script>
