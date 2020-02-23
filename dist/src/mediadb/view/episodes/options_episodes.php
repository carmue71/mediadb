
	<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Style: <?php print $this->msStyle?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'style=plain'?>">Plain</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'style=list'?>">List</a>
					<a class="dropdown-item" href="<?php print INDEX.$target.'style=card'?>">Card</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'style=table'?>">Table</a>
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					Order: <?php print $this->msOrder?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Title'?>">Title <i class="fas fa-sort-alpha-down"></i></a>
  					<a class="dropdown-item" href="<?php print INDEX.$target.'order=Title%20DESC'?>">Title <i class="fas fa-sort-alpha-up"></i> </a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Added'?>">Added <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Added%20DESC'?>">Added <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Published'?>">Published <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Published%20DESC'?>">Published <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Rating'?>">Rating <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Rating%20DESC'?>">Rating <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=PublisherCode'?>">PublisherCode <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=PublisherCode%20DESC'?>">PublisherCode <i class="fas fa-sort-alpha-up"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Modified'?>">Modified <i class="fas fa-sort-alpha-down"></i></a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'order=Modified%20DESC'?>">Modified <i class="fas fa-sort-alpha-up"></i></a>
  				</div>
			</div> <!-- dropdown -->
		</div>
		<div class="col-md-2">
			<div class="dropdown">
  				<button class="btn btn-secondary dropdown-toggle" type="button" 
  					id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  					<i class="fas fa-filter"></i> Filter: <?php print $this->msFilter?></button>
  				<div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
  					<a class="dropdown-item" href="<?php print INDEX.$target.'filter=All'?>">All</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Unwatched'?>">Unwatched</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Watched'?>">Watched</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Top'?>">Top</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=OK'?>">OK</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Flop'?>">Flop</a>
    				<a class="dropdown-item" href="<?php print INDEX.$target.'filter=Unrated'?>">Unrated</a>    				
  				</div>
			</div> <!-- dropdown -->		
		</div>
	