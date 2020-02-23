	<div class="col-md-6">
	</div>
	<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Style: <?php print $this->actorStyle?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'style=List'?>">List</a>
					<a class="dropdown-item" href="<?php print INDEX.$target.'style=Card'?>">Card</a>
					<a class="dropdown-item" href="<?php print INDEX.$target.'style=Table'?>">Table</a>
					<a class="dropdown-item" href="<?php print INDEX.$target.'style=Continuous'?>">Continuous</a>
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Order: <?php print $this->actorOrder?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Fullname'?>">Fullname<i class="fas fa-sort-alpha-down"></i></a>
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Fullname%20DESC'?>">Fullname <i class="fas fa-sort-alpha-up"></i> </a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Added'?>">Added <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Added%20DESC'?>">Added <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Rating'?>">Rating <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Rating%20DESC'?>">Rating <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Modified'?>">Modified <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Modified%20DESC'?>">Modified <i class="fas fa-sort-alpha-up"></i></a>
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					<i class="fas fa-filter"></i> Filter: <?php print $this->actorFilter?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'filter=All'?>">All</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Female'?>">Female</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Male'?>">Male</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Other'?>">Other</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Top'?>">Top-Rated</a>    				
  				</div>
			</div> <!-- dropdown -->		
		</div><!-- col -->
	<br/>