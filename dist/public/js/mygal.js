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
