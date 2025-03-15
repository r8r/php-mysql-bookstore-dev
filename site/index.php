<?php
require_once('inc/bootstrap.php');

include_once('views/partials/header.php');

$view = $default_view;

if (isset($_REQUEST['view']) &&
    file_exists(__DIR__ . '/views/' . $_REQUEST['view'] . '.php')) {
	$view = $_REQUEST['view'];
}

include_once('views/' . $view . '.php');

//echo 'View: ' . $_GET['view'] . '<br>';
//echo 'View: ' . $_REQUEST['view'];

include_once('views/partials/footer.php');