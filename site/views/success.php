<?php

$orderId = $_REQUEST['orderId'] ?? null;

require_once('views/partials/header.php');
?>

	<div class="page-header">
		<h2>Success!</h2>
	</div>

	<p>Thank you for your purchase.</p>

<?php if ($orderId != null) : ?>
	<p>Your order number is <strong><?php echo Bookshop\Util::escape($orderId); ?></strong>.</p>
<?php endif; ?>

<?php /* TODO order Summary */ ?>

<?php require_once('views/partials/footer.php');
