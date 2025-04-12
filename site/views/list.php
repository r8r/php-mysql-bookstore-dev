<?php

use Data\DataManager;

$categories = DataManager::getCategories();
$categoryId =
	isset($_REQUEST['categoryId']) ?
		(int) $_REQUEST['categoryId'] :
		null;

$books = null;
if ($categoryId && $categoryId > 0) {
	$books = DataManager::getBooksByCategory($categoryId);
}

require_once('views/partials/header.php');
?>

<div class="page-header">
	<h2>List of books by category</h2>
</div>

<ul class="nav nav-tabs">
	<?php foreach ($categories as $cat) : ?>
		<li role="presentation" class="navitem">
			<button class="nav-link
			<?php if ($cat->getId() === $categoryId) : ?>
				active
			<?php endif; ?>">
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?view=list&amp;categoryId=<?php echo urlencode($cat->getId());
				?>"><?php echo ($cat->getName()); ?></a></button>
		</li>
	<?php endforeach; ?>
</ul>

<?php
// $categoryId … Kategorie
//print_r($books);

if (isset($books)) {
	if (sizeof($books) > 0) {
		include('views/partials/booklist.php');
	}
  else {
    print "<p>There are no books.</p>";
  }
}
else {
  print "<p>Please choose a category.</p>";
}

require_once('views/partials/footer.php');
