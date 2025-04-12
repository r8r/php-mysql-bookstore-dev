<?php
use Bookshop\ShoppingCart;
use Bookshop\Util;
?>

<!--display error messages-->

<?php if (isset($errors) && is_array($errors)) : ?>
  <div class="errors alert alert-danger mt-4">
    <ul>
      <?php foreach ($errors as $errMsg) : ?>
        <li><?php echo (Util::escape($errMsg)); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<!--/display error messages-->

<div class="footer">

	<!--display cart info-->
	<hr />
	<div class="row">
		<div class="col-md-4">
			<a href="index.php?view=checkout" class="footer-link"  data-bs-toggle="tooltip" data-bs-placement="top" title="Zum Checkout">
				<span class="badge bg-secondary"><?php print ShoppingCart::size(); ?></span> <span class="bi bi-cart4" aria-hidden="true"></span>
			</a>
		</div>
		<div class="col-md-4 ms-auto text-end">
			<p><?php print date('j.n.Y G:i'); ?></p>
		</div>
	</div>

	<!--/display cart info-->

</div>



</div> <!-- container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="assets/main.js"></script>

</body>

</html>