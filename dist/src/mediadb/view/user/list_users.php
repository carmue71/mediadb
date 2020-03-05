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
		<table class="table table-hover table-sm">
			<thead>
				<tr>
					<th>Avatar</th>
					<th>Login</th>
					<th>Name</th>
					<th>E-Mail</th>
					<th>Rolle</th>
					<th>Aktionen</th>
				</tr>
			</thead>
			<tbody>
        		<?php if ( $users != null )
        		    foreach ($users as $user) : ?>
					<tr>
						<td><img src='/ajax/getAvatar.php?file=<?php print $user->Avatar;?>&size=N' alt='<?php print $user->Avatar;?>'></td>
						<td><a href='<?php print INDEX."userdetails?uid={$user->ID_User}";?>'><?php print $user->Login;?></a></td>
						<td><?php print $user->Name;?></td>
						<td><a href='mailto:<?PHP print $user->EMail; ?>'><?php print $user->EMail;?></a></td>
						<td><?php print $user->Role;?></td>
						<td><a class="btn btn-primary" href='<?php print INDEX."edituser?uid={$user->ID_User}";?>'>Bearbeiten</a>
						<a class="btn btn-danger btn-small" href='<?php print INDEX."deleteuser?uid={$user->ID_User}";?>'>Löschen</a></td>
					</tr>
      			<?php endforeach; ?>
      			</tbody>
		</table>
	</div>
	<!-- col -->
</div>
<!-- row -->

</div><!-- container -->

<?php include VIEWPATH.'fragments/js.php'; ?>
</body>
</html>