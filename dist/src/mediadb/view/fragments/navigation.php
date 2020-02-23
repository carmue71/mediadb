<!-- navigation.php start -->
<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark transparent">
  <a class="navbar-brand" href='<?php print WWW;?>'>Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
  	aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if (isset($this) && $this->currentSection == 'Actors') print ' active'; ?>">
        <a class="nav-link" href="<?php print INDEX?>listactors">Actors <i class="fas fa-female"></i><i class="fas fa-male"></i></a>
      </li>
      <li class="nav-item <?php if (isset($this) && $this->currentSection == 'Media Sets') print ' active'; ?>">
        <a class="nav-link" href="<?php print INDEX?>listepisodes">Media Sets <i class="fab fa-youtube"></i></a>
      </li>
      
      <li class="nav-item <?php if (isset($this) && $this->currentSection == 'Channels') print ' active'; ?>">
        <a class="nav-link" href="<?php print INDEX?>listchannels">Channels <i class="fas fa-user-secret"></i></a>
      </li>
      
      <li class="nav-item <?php if (isset($this) && $this->currentSection == 'Keywords') print ' active'; ?>">
        <a class="nav-link" href="<?php print INDEX?>listkeywords">Keywords <i class="fas fa-tags"></i></a>
      </li>
      
      <li class="nav-item <?php if (isset($this) && $this->currentSection == 'WatchList') print ' active'; ?>">
        <a class="nav-link" href="<?php print INDEX?>listwatchlists"> Watch-Lists <i class="fas fa-binoculars"></i></a>
      </li>
      
      <li class="nav-item <?php if (isset($this) && $this->currentSection == 'Devices') print ' active'; ?>">
        <a class="nav-link" href="<?php print INDEX?>listdevices">Devices <i class="fas fa-database"></i></a>
      </li>
      
      
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Maintainance
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href='<?php print INDEX."editsettings"?>'><i class="fas fa-wrench"></i> Settings</a>
          <a class="dropdown-item" href='<?php print INDEX."newactor"?>'><i class="fas fa-female"></i><i class="fas fa-male"></i> Add Actor</a>
          <a class="dropdown-item" href='<?php print INDEX."newepisode"?>'><i class="fab fa-youtube"></i> Add Media Set</a>
          <a class="dropdown-item" href='<?php print INDEX."newchannel"?>'><i class="fas fa-user-secret"></i> Add Channel</a>
          <a class="dropdown-item" href='<?php print INDEX."newwatchlist"?>'><i class="fas fa-binoculars"></i> Add Watch-List</a>
          <a class="dropdown-item" href='<?php print INDEX."newdevice"?>'><i class="fas fa-database"></i> Add Device</a>
          
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href='<?php print INDEX."listusers"?>'><i class="fas fa-users"></i> List Users</a>
          <a class="dropdown-item" href='<?php print INDEX."adduser"?>'><i class="fas fa-user-plus"></i>Add User</a>
          
          <?php if (isset($ms)){?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" id='deleteMS' value='<?php print $ms->ID_Episode;?>' href='#'> 
          	<i class="fas fa-trash"></i> Delete Media Set</a>
          	<div class="dropdown-divider"></div>
          <a class='dropdown-item addepisodetowatchlist' value='<?php print $ms->ID_Episode;?>' href='#'> 
          	<i class="fas fa-binoculars"></i> Add to Watch List</a>
		  <?php }?>
        </div>
      </li>
      </ul>
    
    <form class="form-inline my-2 my-lg-0" method="GET" action='<?php print INDEX."search"?>'>
      <input id="search" class="form-control mr-sm-2" name="search" type="search" placeholder="Search Text" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    </div>
</nav>
<!-- navigation.php end -->
<br />