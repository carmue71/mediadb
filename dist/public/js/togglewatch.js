function setwatched(msid, watched){
	if ( watched ){
		console.log('Marking ' + msid+" as watched");
        var values = {
            'what': 'watched',
            'msid': msid,
        };
        
        var result = $.ajax({
            url: '/mediadb/ajax/update.php',
            type: 'POST',
            data: values,
            success: function() {
                console.log("Update OK");
            },
        	error: function(){
        		console.log("Some error occured");
        	}
        });
            console.log(result);
            $('#statusflagunwatched').hide();
            $('#statusflagwatched').show();
            $('#watchedtoggle').text('Watched');
            $('#watchedtoggle').attr('value', 'true');
            $('#watchedtoggle').addClass('watched').removeClass('unwatched');
            
    } else {
    	console.log('Marking ' + msid+" as UNWATCHED");
        var values = {
            'what': 'unwatched',
            'msid': msid,
        };
        
        var result = $.ajax({
            url: '/mediadb/ajax/update.php',
            type: 'POST',
            data: values,
            success: function() {
                console.log("Update OK");
            }
        });
            console.log(result);
            $('#statusflagwatched').hide();
            $('#statusflagunwatched').show();
            $('#watchedtoggle').text('Unwatched');
            $('#watchedtoggle').attr('value', 'false');
            $('#watchedtoggle').addClass('unwatched').removeClass('watched');
    }	
}


$('#watchedtoggle').click(function(){
    var msid = $('#msid').attr('value');
    var watched = $(this).attr('value');
    if ( watched == 'false' )
    	setwatched(msid, true);
    else
    	setwatched(msid, false); 
});

    