<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', 1);

$default_view = 'welcome';

spl_autoload_register(function ($class) {
	// $class = Bookshop\Category
	$filename = __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
	if (file_exists($filename)) {
		require_once($filename);
	}
});

\Bookshop\SessionContext::create();

require_once __DIR__ . '/../lib/Data/DataManager_mock.php';
