<?php
include VIEWPATH.'fragments/header.php';
include VIEWPATH.'fragments/navigation.php';
?>

<div class="container-fluid">

<div class="row">
	<div class="col-sm-12">
		<h1><?php print $this->pageTitle?></h1>
	</div>
</div>
<div class="row">
	<div class='col-sm-12'>
		<form method="POST" action='<?PHP print INDEX."saveuser"?>'>
		<input type="hidden" name="id" value='<?PHP print $user->ID_User; ?>' />
		
		<div class='form-row'>
			<div class='col-sm-6'>
				<div class=form-group>
					<label class="col-form-label" for='login'>Login</label> 
					<input class='form-control' id='login' type="text" name="login" required value='<?PHP print $user->Login; ?>'
						placeholder='Login Name' />
				</div><!-- form-group -->				
			</div><!-- col -->
		
			<div class='col-sm-6'>
				<div class=form-group>
					<label class="col-form-label" for='password'>Password</label> 
					<input class='form-control' id='password' type="password" name="password" required value='<?PHP print $user->Password; ?>'
						placeholder='Secure Password' />
				</div><!-- form-group -->				
			</div><!-- col -->
		</div><!-- form-row -->
		
		
		<div class='form-row'>
			<div class='col-sm-6'>
				<div class=form-group>
					<label class="col-form-label" for='name'>Name</label> 
					<input class='form-control' id='name' type="text" name="name" required value='<?PHP print $user->Name; ?>'
						placeholder='Complete Name' />
				</div><!-- form-group -->				
			</div><!-- col -->
			
				<div class='col-sm-6'>
				<div class=form-group>
					<label class="col-form-label" for='email'>E-Mail-Adresse</label> 
					<input class='form-control' id='email' type="email" name="email" required value='<?PHP print $user->EMail; ?>'
						placeholder='E-Mail-Address' />
				</div><!-- form-group -->				
			</div><!-- col -->
			
		</div><!-- form-row -->
		
		<div class='form-row'>
			<div class='col-sm-6'>
				<div class=form-group>
					<label class="col-form-label" for='role'>Rolle</label> 
					<select class='form-control' id='role' name="role">
							<option value="read_only" <?PHP if ($user->Role=="read_only") print "selected"; ?>>Nur-Lesen</option>
							<option value="standard" <?PHP if (!isset($user->Role) || $user->Role=="" || $user->Role=="standard" ) print "selected"; ?>>Standard</option>
							<option value="admin" <?PHP if ($user->Role=="admin") print "selected"; ?>>Administrator</option>
						</select>
				</div><!-- form-group -->				
			</div><!-- col -->
			
				<div class='col-sm-5'>
				<div class=form-group>
					<label class="col-form-label" for='avatar'>Avatar</label> 
					<input class='form-control' id='avatar' type="text" name="avatar" required value='<?PHP print $user->Avatar; ?>'
						placeholder='Avatar' />
				</div><!-- form-group -->
			</div><!-- col -->	
				<div class='col-sm-1'>
					<div class=form-group>
					<label class="col-form-label" for='uploadbtn'>&nbsp; </label><br> 
					<button type="button" name='uploadbtn' class="btn btn-secondary" data-toggle="modal" data-target="#uploadAvatar">Upload</button>
					</div>
				</div>
								
			
			
		</div><!-- form-row -->
		
		
		<div class='form-row'>
			<div class='col-sm-4'>
				<input class='form-control btn-primary' type="submit" />
			</div>
		</div>
		<!-- form-row -->
	</form>
	</div>
	<!-- col -->
</div>
<!-- row -->

</div><!-- container -->

<!-- Modal Upload Avatar -->
<div class="modal fade" id="uploadAvatar" tabindex="-1" role="dialog" aria-labelledby="uploadAvatarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadAvatarLabel">Avatar hochladen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<form>
        <input type=file id=avatar/><br/>
        <input type=submit>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include VIEWPATH.'fragment/js.php'; ?>
</body>
</html>