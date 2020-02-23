	<div class="row">
		<div class="col-sm-12">
			<div id="carouselExampleControls" class="carousel slide"
				data-ride="carousel">
				<div class="carousel-inner">
					<?php $first = true; 
					   foreach ($files as $picture): 
					       $fullpath="{$picture['DevicePath']}files/{$picture['Path']}{$picture['Name']}";
					   ?>
						<div class="carousel-item <?php if ($first) { print "active"; $first = false;} ?>">
							<img class='d-block w-100' src='<?php print $fullpath;?>'>
						</div>
					<?php endforeach;?>
				</div>
				<a class="carousel-control-prev" href="#carouselExampleControls"
					role="button" data-slide="prev"> <span
					class="carousel-control-prev-icon" aria-hidden="true"></span> <span
					class="sr-only">Previous</span>
				</a> <a class="carousel-control-next"
					href="#carouselExampleControls" role="button" data-slide="next"> <span
					class="carousel-control-next-icon" aria-hidden="true"></span> <span
					class="sr-only">Next</span>
				</a>
			</div>

		</div>
	</div>
