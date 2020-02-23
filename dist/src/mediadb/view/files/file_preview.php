<div class='row'>
	<table class="table table-hover table-sm transparent">
		<thead>	<tr><th></th><th>Image</th><th>File<br> <small>Path</small></th><th>Title</th><th>Size<br>Resolution</th>
				<th>Created<br> <small>Modified</small></th><th>Info</th><th>Rating</th><th>Actions</th></tr></thead>
		<tbody>
<?php
if ($files != null)
    $curPos = $offset;
    foreach ($files as $file) {?>
        <tr id='<?php print "row_{$file->ID_File}";?>'>
			<td> <input type='checkbox' class='fileselector' data-fid='<?php print "row_{$file->ID_File}";?>' /> </td>
			<?php if ( $file->REF_Filetype == 2 ||  $file->REF_Filetype == 3 ) {//it is an image or video ?>
			<td class='gal-thumbcontainer gal-button'
            	data-title='<?php print $file->Name;?>'
				data-img='/mediadb/ajax/image.php?path=<?php print $file->getInternalPath();?>&type=<?php print ($file->REF_Filetype==2)?"img":"video";?>'
            	data-imgid=<?php print $file->ID_File;?>
            	data-type=<?php print $file->REF_Filetype;?>
            	data-pos='<?php print $curPos;?>'>
            	<img src='/mediadb/ajax/image.php?path=<?php print $file->getInternalPath();?>&type=thumb' class='gal-thumbnail'/></td>
            <?php } else {?>
            <td>
            	<?php if ( $file->REF_Filetype == 3 ) {?>
            		<img src='/mediadb/img/video_thumb.png'/>
            	<?php } else {?>
            		<img src='/mediadb/img/otherfile_thumb.png'/>
            	<?php }?>
            		</td>
            <?php }?>
			<td>
				<a href="/mediadb/ajax/file.php?path=<?php print $file->getInternalPath();?>&type=<?php $file->printType();?>&filename=<?php print $file->Name;?>">
					<?php print $file->Name; ?>
				</a>
				<br>
				<small>	<?php print $file->Path; ?></small></td>
			<td><?php print $file->Title; ?></td>
			<td><?php print $file->printSize()?><br /><?php print $file->printResolution()?><br/><?php print $file->printPlaytime();?></td>
			<td><?php print $file->Created;?><br/><small><?php print $file->Modified; ?> </small></td>
			<td><?php print $file->FileInfo; ?></td>
			<td><?php print $file->Rating; ?></td>
			<td> 
				<button class='btn edit' id='edit_<?php print $file->ID_File; ?>'><i class="fas fa-pencil-alt"></i></button> &nbsp;
				<button class='btn deleteFile' data-imgid='<?php print $file->ID_File;?>'><i class="fas fa-trash"></i></button> &nbsp; 
				<a class='btn' href="/mediadb/ajax/file.php?path=<?php print $file->getInternalPath();?>&type=<?php $file->printType();?>&filename=<?php print $file->Name;?>&forcedownload=yes">
					<i class="fas fa-save"></i></a>
			</td>
		</tr>
<?php $curPos++; }?>
		</tbody>
	</table>
</div>
<!-- row -->
