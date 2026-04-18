<?php

declare(strict_types=1);

use Bookshop\SessionContext;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

spl_autoload_register(function ($class) {
	// dateipfad zusammenbasteln -> Namespace\Classname
	// require_once versuchen

	$filename = __DIR__ . "/../lib/" . str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";

	if (file_exists($filename)) {
		require_once($filename);
	}
});

\Bookshop\SessionContext::create();

$default_view = "welcome";

/**
 * DataManager
 * change to switch between different implementations … 'mock' | 'pdo'
 */
$mode = 'mock';

switch (mb_strtolower($mode)) {
	case 'mysqli':
		$class = 'mysqli';
		break;
	case 'pdo':
		$class = 'mysqlpdo';
		break;
	default:
		$class = 'mock';
		break;
}

require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'DataManager_' . $class . '.php');
// __DIR__ . '/../lib/Data/DataManager_' . $class . '.php'