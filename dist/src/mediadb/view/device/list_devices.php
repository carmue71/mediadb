<?php

/* searchresults.php
 * Version: 0.8
 * Project: MediaDB
 * Author:  Karl Müller
 * Purpose: Displays a list of defined devices
 */


$bodymodifier = " style=\""
    ."background: linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6) ),url('/mediadb/wallpaper.php?file=default.png') no-repeat center center fixed;"
        ."background-size: cover;\"";
        
        include VIEWPATH.'fragments/header.php';
        include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<div class="row">
	<div class="col-sm-12">
		<h1>Device List</h1>
	</div>
</div>
<div class="row">
	<div class='col-sm-12'>
		<table class="table table-hover table-sm">
			<thead>
				<tr>
					<th>Device</th>
					<th>Path</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
        		<?php foreach ($devices as $device) :
                    $active = $device->isActive();
                ?>
					<tr id='row_<?php print $device->ID_Device;?>'>
					<td><?php print $device->Name;?> 
					<span class="badge badge-pill <?php print $active?'badge-success':'badge-danger'; ?>">
							<?php print $active?"connected":"unavaillable"; ?></span>
							</td>
					<td><?php print $device->Path;?></td>
					<td><a href='<?php print INDEX."scandevice?id={$device->ID_Device}";?>'	type="button"
						class="btn <?php print $active?'btn-info':'btn-warning';?>"> <i class="fas fa-search"></i> Scan Device</a>
							
						<a href='<?php print INDEX."editdevice?id={$device->ID_Device}";?>'
							class="btn btn-secondary"><i class="fas fa-pencil-alt"></i> Edit Device</a>
							
						<a class="btn btn-danger deleteDevice" data-devid='<?php print $device->ID_Device;?>' data-devname='<?php print $device->Name;?>'>
							<i class="fas fa-trash-alt"></i> Delete Device</a>
					</td>
				</tr>
      			<?php endforeach; ?>
      			</tbody>
		</table>
	</div>
	<!-- col -->
</div>
<!-- row -->
</div><!-- container -->


<?php 
    include VIEWPATH.'fragments/footer.php';
    include VIEWPATH.'fragments/confirmdelete.php';
?>

<script type="text/javascript">
	devid = -1;
	
	$(".deleteDevice").click(function(){
		//handles the delete button after  
		
		devid = $(this).data('devid');
		devname = $(this).data('devname');

		$('#modaltitle').text('Delete a Device');
		$('#confirmTxt').html('Do really want to delete the device "'+devname+'"?<br>Files on the device will be untouchted<br><b>But any additional information will be lost!</b>');
		$('#physicallyDeleteFiles').hide();
		console.log("asking user if he wandts to remove file "+devid+";");
		
		$("#confirmDeleteModal").modal('show');
		
	});

	$("#confirmDeleteBtn").click(function(){
		var values = {
	            'what': 'deletedevice',
	            'devid': devid,
	        };
	        
	        var result = $.ajax({
	            url:'/mediadb/ajax/update.php',
	            type: 'POST',
	            data: values,
	            success: function() {
	                console.log("Device "+devid+" removed");
	                //remove row
	                $('#row_'+devid).remove();
	                console.log(result); 
	            },
	            error: function(){
		            console.log('error, while removing '+devid);
	            	console.log(result);
	            	alert("Problem deleting the device");
	            }
	        });
		$("#confirmDeleteModal").modal('hide');
	});

	$('#cancelWithoutConfirmationBtn').click(function(){
		$("#confirmDeleteModal").modal('hide');
	});
		
</script>

