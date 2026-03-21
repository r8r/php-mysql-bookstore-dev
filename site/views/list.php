<?php

use Bookshop\Book;
use Bookshop\Category;
use Data\DataManager;

$categories = DataManager::getCategories();

require("views/partials/header.php");

?>

	<div class="page-header">
		<h2>Booklist</h2>
	</div>

<?php if (count($categories) > 0) : ?>

	<ul class="nav nav-tabs">

    <?php foreach ($categories as $category) : ?>
      <?php
    		$htmlClassActive = "";
        if (isset($_GET['categoryId']) && $category->getId() == $_GET['categoryId']) {
          $htmlClassActive = " active";
        }
      ?>
    <li class="nav-item">
			<a class="nav-link<?php echo $htmlClassActive; ?>" href="?view=<?php echo $_REQUEST['view']; ?>&amp;
			categoryId=<?php echo
      $category->getId
      (); ?>">
				<?php echo $category->getName(); ?>
			</a>
		</li>
    <?php endforeach; ?>

  </ul>


<?php
if (isset($_GET['categoryId'])) {
	$books = DataManager::getBooksByCategory($_GET['categoryId']);
}
?>

<?php
  if (count($books) > 0) {
    include(__DIR__ . "/partials/booklist.php");
  }
  ?>




<?php endif; ?>

<?php
require_once("views/partials/footer.php");