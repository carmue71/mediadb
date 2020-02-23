<!-- Modal Add To Watchlist -->
<div class="modal fade" id="addtowatchlist" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">Add to Watch List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
      	<div class=form-group>
						<label class="col-form-label" for='wlselect'>Select a Watch List</label> 
						<select class='form-control' id='wlselect' name="wlselect">
						<?php $wl = $this->repository->getWatchLists(); 
						foreach ($wl as $w){
						      print "<option value='{$w[0]}'>{$w[1]}</option>\n";
						}?>
						</select>
					</div> <!-- grp -->
		</form>	  
        <button id='addBtn' type=button class='btn btn-info'>Add</button>
        <button id='cancelBtn' type=button class='btn btn-secondary data-dismiss="modal" aria-label="Close" '>Cancel</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$('.addepisodetowatchlist').click( function(event){
	msid = $(this).attr('value');
    //todo: fill with watchlists
    $("#addtowatchlist").modal('show');
    //event.preventDefault();
    //todo: add to list
    return false;
});

$("#addBtn").click(function(){
    var msid = $('.addepisodetowatchlist').attr('value');
    var wlid = $('#wlselect').val();
    watchlistAdd(msid, wlid);
	$("#addtowatchlist").modal('hide');
});
    
    $('#cancelBtn').click(function(){
        $("#addtowatchlist").modal('hide');
    });

function watchlistAdd(msid, wlid){
	var values = {
	        'what': 'addtowatchlist',
	        'msid': msid,
	        'wid': wlid,
	    };
	    var result = $.ajax({
	        url:'/mediadb/ajax/update.php',
	        type: 'POST',
	        data: values,
	        success: function(response) {
	            console.log("OK");
	            console.log(response);
	            results = JSON.parse(response);
	            if ( results[0] == "OK" )
	     	   		$('#WatchListContainer').append(
	     	   			"<span class='badge badge-pill badge-light' id=WatchList"+wlid+"> <a href='/mediadb/index.php/showwatchlist?id="+wlid+"'>"+
	                    "<i class='fas fa-binoculars'></i>"+results[1]+"</a>&nbsp; <a href='#' onClick='removeFromWatchlist("+wlid+","+msid+",0)'>"+
						"<i class='fas fa-times-circle'></i></a></span>&nbsp;");
	        }
	    });
	        console.log(result);
}
</script>