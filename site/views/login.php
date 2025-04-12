
<?php
use Bookshop\Controller;
use Bookshop\AuthenticationManager;
use Bookshop\Util;

if (AuthenticationManager::isAuthenticated()) {
	Util::redirect('/index.php');
}

require_once('views/partials/header.php');

?>

<div class="page-header">
	<h2>Login</h2>
</div>

<form method="post" action="<?php echo Util::action(Controller::ACTION_LOGIN, array('view' => $view)); ?>">
	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">User name:</label>
		<div class="col-sm-6">
			<input type="text" class="form-control" id="inputName" name="<?php print Controller::USER_NAME; ?>" placeholder="try 'scm4'" value="<?php echo htmlentities($userName ?? ''); ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="inputPassword" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-6">
			<input type="password" class="form-control" id="inputPassword" name="<?php print Controller::USER_PASSWORD; ?>" placeholder="try 'scm4'">
		</div>
	</div>
	<button type="submit" class="btn btn-primary">Login</button>
</form>

<?php
require_once('views/partials/footer.php');