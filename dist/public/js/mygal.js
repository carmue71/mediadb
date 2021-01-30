function closeModal(){
	$('.gal-lightbox').stop().fadeOut(200); //css('visibility', 'hidden');
};

$(".gal-image").on({
	  mouseenter: function() {
		  $('.gal-text').css('visibility', 'visible');
	  },
	  mouseleave: function() {
		  $('.gal-text').css('visibility', 'hidden');
	  }
	});

/* ***** Thumbnails **********************************************************/

currentThumb = '';
currentPos = 0;

$('.gal-thumbcontainer').click(function(event) {
	event.preventDefault();
	
	var src = $(this).data('img');
	var title = $(this).data('title');
	var id = $(this).data('imgid');
	currentPos = $(this).data('pos');
	//$(this).addClass('activeThumb');
	console.log(this);
	showLightbox(src, title, id, currentPos);
	currentThumb = $(this);
});

function showLightbox(src, title, id, pos){
	$('.gal-image').attr('src', src);
	$('.gal-lightbox').attr('data-imgid', id);
	$('.gal-text').text(title);
	$('.gal-lightbox').css('display', 'flex');
	currentPos = pos;
	console.log($('.gal-lightbox').data('imgid'));
}

function getNextPos(){
	currentPos++;
	return currentPos;
}

function getNewPos(direction){
	currentPos=currentPos+direction;
	return currentPos;
}

function setCurrentPos(val){
	currentPos = val;
}

$(".gal-thumbcontainer").on({
	  //on mouseenter show the title
	  mouseenter: function() {
		  $(this).children('.gal-thumb-title').css('visibility', 'visible');
	  },
	  //hide the text on mouseleave
	  mouseleave: function() {
		  $(this).children('.gal-thumb-title').css('visibility', 'hidden');
	  }
});

//---------------------------------------------------------------------------------
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

/*function togglefullscreen(elem){
	
	console.log('toggling fullscreen');
}*/
    
function changeImage(i){
	 var pos = getNewPos(i);
		console.log(pos);
		console.log(current_episode)
	    var values = {
	    	'msid': current_episode,
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
	
	function toggleFullscreen(elem) {
		//console.log('toggling fullscreen');
		//console.log(elem)
        elem = elem || document.documentElement;
                if (!document.fullscreenElement && !document.mozFullScreenElement &&
                    !document.webkitFullscreenElement && !document.msFullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
    	setImageSize('.gal-image', $(window).width(), $(window).height());
 	}
 	
 	function setImageSize(img, maxWidth, maxHeight){
                var ratio = 0;  // Used for aspect ratio
                var width = $(img).width();    // Current image width
                var height = $(img).height();
                
                // Check if the current width is larger than the max
                if ( width > maxWidth ){
                    ratio = maxWidth / width;   // get ratio for scaling image
                    $(img).css("width", maxWidth); // Set new width
                    $(img).css("height", height * ratio);  // Scale height based on ratio
                    height = height * ratio;    // Reset height to match scaled image
                    width = width * ratio;    // Reset width to match scaled image
                }
                // Check if current height is larger than max
                if ( height > maxHeight ){
                    ratio = maxHeight / height; // get ratio for scaling image
                    $(img).css("height", maxHeight);   // Set new height
                    $(img).css("width", width * ratio);    // Scale width based on ratio
                    width = width * ratio;    // Reset width to match scaled image
                    height = height * ratio;    // Reset height to match scaled image
                }
                //$(img).height(height).width(width);
            }
	
	