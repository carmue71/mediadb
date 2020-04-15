<?php
    include VIEWPATH.'fragments/confirmdelete.php';
?>

<!-- Delete MediaSet -->
<script type="text/javascript">



$('#deleteMS').click( function(event){
    msid = $(this).attr('value');
    $('#modaltitle').text('Please confirm to delete this episode!');
    $('#confirmTxt').text('Are you sure to delete this episode?');
    $("#confirmDeleteModal").modal('show');
    //event.preventDefault();
    return false;
});
    
    $("#confirmDeleteBtn").click(function(){
        //TODO: Delete Files from Directory - if possible
        //TODO: Remove DB-Entry
        //TODO: remove rows
        //alert("Some fine day I'll do this!");
        msid = $('#deleteMS').attr('value');

        
        purge = $("#confirmDeleteModal").find(':checkbox').prop('checked');
        console.log("purge: "+purge);
        var values = {
            'what': 'deletems',
            'msid': msid,        
            'purge': purge,
        };
        
        var result = $.ajax({
            url:'/mediadb/ajax/update.php',
            type: 'POST',
            data: values,
            success: function() {
                console.log("Delete OK");
            }
        });
            console.log(result);
            
            $("#confirmDeleteModal").modal('hide');
    });
        
        $('#cancelWithoutConfirmationBtn').click(function(){
            $("#confirmDeleteModal").modal('hide');
        });
</script>
            