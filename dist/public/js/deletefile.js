fid = -1

$("#selectAll").change(function(){
    $(".fileselector").prop('checked', this.checked);
});

//TODO: implement mass deletion

$(".deleteFile").click(function(){
    //handles the delete button after each file
    
    fid = $(this).data('imgid');
    purge = false;
    
    console.log("Asking user if he wandts to remove file "+fid+";");
    
    $("#confirmDeleteModal").find(':checkbox').prop('checked', purge);
    
    $("#confirmDeleteModal").modal('show');
    $('#pdf').attr('checked', purge);
});

$("#confirmDeleteBtn").click(function(){
	//if ( $('#physicallyDeleteFiles').is(':checked') )
	//	purge = "true";
	//else
	//	purge = "false";
	//purge = "true";
	//console.log('purge: '+purge);
	//console.log($('#pdf').attr('checked'))
	purge = $("#confirmDeleteModal").find(':checkbox').prop('checked');
	console.log("Purging: "+purge);
    
	var values = {
        'what': 'deletefile',
        'fid': fid,
        'purge': purge,
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
        },
        error: function(){
            console.log('error, while removing '+fid);
            console.log(result);
            alert("Problem deleting the file");
        }
    });
    $("#confirmDeleteModal").modal('hide');
});

$('#cancelWithoutConfirmationBtn').click(function(){
    $("#confirmDeleteModal").modal('hide');
});
