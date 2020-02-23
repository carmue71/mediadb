	<div class="col-md-4">
	</div>
	<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Style: <?php print $this->fileStyle?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'style=List'?>">List</a>
					<a class="dropdown-item" href="<?php print INDEX.$target.'style=Preview'?>">Preview</a>
					<!-- a class="dropdown-item" href="< ?php print INDEX.$target.'style=Gallery'?>">Gallery</a>
					<a class="dropdown-item" href="< ?php print INDEX.$target.'style=Slideshow'?>">Slideshow</a>
					<a class="dropdown-item" href="< ?php print INDEX.$target.'style=Continuous'?>">Continuous</a -->
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-3">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Order: <?php print $this->fileOrder?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
  					
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Name'?>">Name <i class="fas fa-sort-alpha-down"></i></a>
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Name%20DESC'?>">Name <i class="fas fa-sort-alpha-up"></i> </a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Title'?>">Title <i class="fas fa-sort-alpha-down"></i></a>
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Title%20DESC'?>">Title <i class="fas fa-sort-alpha-up"></i> </a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Created'?>">Created <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Created%20DESC'?>">Created <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Name'?>">Published <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Name%20DESC'?>">Published <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Rating'?>">Rating <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Rating%20DESC'?>">Rating <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Size'?>">Size <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Size%20DESC'?>">Size <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Modified'?>">Modified <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Modified%20DESC'?>">Modified <i class="fas fa-sort-alpha-up"></i></a>
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					<i class="fas fa-filter"></i> Filter: <?php print $this->fileFilter?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'filter=All'?>">All</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Videos'?>">Videos</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Pictures'?>">Pictures</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=OtherFiles'?>">Other Files</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=HQHDVideos'?>">HQ/HD Videos</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Top'?>">High Rated Files</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Unrated'?>">Unrated Files</a>    				
  				</div>
			</div> <!-- dropdown -->
			</div><!-- col -->
		<div class="col-md-1">
			<a class='btn btn-secondary' title="Slide Show Options" id="slideshowOptionsBtn"><i class="fas fa-cogs"></i></a>
		</div>
	<br/>