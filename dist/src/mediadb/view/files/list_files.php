<div class="row">
	<div class="col-md-12">
		<table class="table table-hover table-sm  transparent">
			<thead>
				<tr>
					<th></th>
					<th>Device</th>
					<th>Episode</th>
					<th>File<br />
					<small>Path</small></th>
					<th>Title</th>
					<th>Size <br>
					<small>Playtime</small></th>
					<th>Resolution</th>
					<th>Created<br>
					<small>Modified</small></th>
					<th>Info</th>
					<th>Rating</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
			<?php
                if ($files != null)
                    $curPos = $offset;
                foreach ($files as $file) {
                    $fullpath = $file->getFullPath();?>
        		<tr id='row_<?php print $file->ID_File;?>'>
        			<td><input type='checkbox' class='fileselector'/> </td>
        			<td><a href='"<?PHP print INDEX."showdevice?id={$file->REF_Device}"?>'><?php print $file->Device; ?></a></td>
        			<td><a href='"<?PHP print INDEX."showepisode?id={$file->REF_Episode}"?>'>Episode</a></td>
					<td><a href='/mediadb/ajax/image.php?path=<?php print $file->getInternalPath();?>&type=img'><?php print $file->Name; ?></a><br/>
						<small> <?php print $file->Path; ?></small></td>
					<td><?php print $file->Title; ?></td>
					<td><?php print $file->printSize()?><br><small><?php print $file->printPlaytime();?></small></td>
					<td><?php print $file->printResolution()?></td>
					<td><?php print $file->Created."<br/>".$file->Modified ?></td>
					<td><?php print $file->FileInfo; ?></td>
					<td><?php print $file->Rating; ?></td>
					<td>
						<a class='btn btn-primary viewFile' 
							data-title='<?php print $file->Name;?>'
							data-img='/mediadb/ajax/image.php?path=<?php print $file->getInternalPath();?>&type=img'
            				data-imgid=<?php print $file->ID_File;?>
            				data-type=<?php print $file->REF_Filetype;?>
            				data-pos='<?php print $curPos;?>'><i class="far fa-eye"></i> </a>
						<a class='btn btn-secondary editFile' data-id='<?php print $file->ID_File;?>'><i class="fas fa-pencil-alt"></i></a>
						<a class='btn btn-secondary refreshFile' data-id='<?php print $file->ID_File;?>' title='Refresh File Info'><i class="fas fa-sync-alt"></i></a>
						<a class='btn btn-danger deleteFile' data-imgid='<?php print $file->ID_File;?>'><i class="fas fa-trash-alt"></i></a>
					</td>
				</tr>
    			<?php 
                    $curPos++;   }
                ?>
    	    	<tr>
					<td><input type='checkbox' id='selectAll' /></td>
					<td colspan=9>
						<button type="button" class="btn btn-primary" id='view_selected'>View
							Selected</button>
						<button class="btn btn-secondary" id='delete_selected'>Delete
							Selected</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
