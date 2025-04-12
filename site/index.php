<?php
require_once('inc/bootstrap.php');

include_once('views/partials/header.php');

$view = $default_view;

if (isset($_REQUEST['view']) &&
    file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')) {
	$view = $_REQUEST['view'];
}

$postAction = $_REQUEST[Bookshop\Controller::ACTION] ?? null;
if ($postAction != null) {
	Bookshop\Controller::getInstance()->invokePostAction();
}

include_once('views/' . $view . '.php');

//echo 'View: ' . $_GET['view'] . '<br>';
//echo 'View: ' . $_REQUEST['view'];

include_once('views/partials/footer.php');