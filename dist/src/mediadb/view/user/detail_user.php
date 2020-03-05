<?php

$this->pageTitle = 'Details for User '.$user->Login;

include (VIEWPATH . 'fragments/header.php');
include (VIEWPATH . 'fragments/navigation.php');
?>

<div class="container">

<?php if ( isset($message) && $message != ""){ ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Erfolg!</strong> <?php print $message; $message=";" ?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php }?>

<div class="row">
	<div class="col-sm-12">
		<h1><?php print $this->pageTitle?></h1>
	</div>
</div>
<div class="row">
	<div class='col-sm-6'>
		<p><img src='<?php print WWW."ajax/getAvatar.php?file={$user->Avatar}";?>&size=XL' alt='<?php print $user->Avatar;?>'></p>
	</div>
	<div class='col-sm-6  fog'>
		<h1><?PHP print $user->Login; ?> </h1>
		<p>Name: <?PHP print $user->Name; ?></p>
		<p>E-Mail-Adresse: <a href='mailto:<?PHP print $user->EMail; ?>'><?PHP print $user->EMail; ?></a></p>
		<p>Rolle: <?PHP 
		  if ($user->Role=="read_only") 
		      print "Read Only";
		  elseif ( $user->Role=="admin" || $user->Role=="standard" ) 
		      print "Administrator";
		  else 
		      print "Standard"; ?>
		      
		      </p>
		      <a class="btn btn-primary" href='<?php print INDEX."edituser?uid={$user->ID_User}";?>'>Edit</a>
	</div>	<!-- col -->
</div><!-- row -->
</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>