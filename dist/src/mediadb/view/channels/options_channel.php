	<div class="col-md-6">
	</div>
	<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Style: <?php print $this->studioStyle?></button>
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
  					Order: <?php print $this->studioOrder?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Name'?>">Name<i class="fas fa-sort-alpha-down"></i></a>
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Name%20DESC'?>">Name <i class="fas fa-sort-alpha-up"></i> </a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Added'?>">Added <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Added%20DESC'?>">Added <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=TotalNumber'?>">Total Number <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=TotalNumber%20DESC'?>">Total Number <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=UnseenNumber'?>">Unseen Number <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=UnseenNumber%20DESC'?>">Unseen Number <i class="fas fa-sort-alpha-up"></i></a>
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					<i class="fas fa-filter"></i> Filter: <?php print $this->studioFilter?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'filter=All'?>">All</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Unwatched'?>">Unwatched</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=AllWatched'?>">AllWatched</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Studios'?>">Studios</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Channels'?>">Channels</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Series'?>">Series</a>    				
  				</div>
			</div> <!-- dropdown -->		
		</div><!-- col -->
	<br/>