<?php
use Bookshop\Util;
use Bookshop\ShoppingCart;
?>

<table class="table">
	<thead>
	<tr>
		<th>
			Title
		</th>
		<th>
			Author
		</th>
		<th>
			Price
		</th>
		<th>
			<span class="bi bi-cart4" aria-hidden="true"></span>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($books as $book):
		$inCart = ShoppingCart::contains($book->getId());
		?>
		<tr>
			<td><strong>
					<?php echo Util::escape($book->getTitle()); ?>
				</strong>
			</td>
			<td>
				<?php echo Util::escape($book->getAuthor()); ?>
			</td>
			<td>
				<?php echo sprintf('%01.2f', ($book->getPrice())); ?>&nbsp;&euro;
			</td>
			<td class="add-remove">
				<?php if ($inCart):  ?>
					<form method="post" action="<?php echo Util::action
					(Bookshop\Controller::ACTION_REMOVE, array('bookId' => $book->getId())); ?>">
						<button type="submit" role="button" class="btn btn-sm btn-info">
							<span class="bi bi-cart-dash-fill" aria-hidden="true"></span>
						</button>
					</form>
				<?php else: ?>
					<form method="post" action="<?php echo Util::action
					(Bookshop\Controller::ACTION_ADD, ['bookId' => $book->getId()]); ?>">
						<button type="submit" role="button" class="btn btn-sm btn-success">
							<span class="bi bi-cart-plus-fill" aria-hidden="true"></span>
						</button>
					</form>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
