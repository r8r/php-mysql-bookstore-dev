<?php

require_once("inc/bootstrap.php");

$view = $default_view;
if (isset($_REQUEST['view']) && $_REQUEST['view'] &&
    file_exists(__DIR__ .  "/views/" . $_REQUEST['view'] . ".php")
) {
	$view = $_REQUEST['view'];
}

/* if we have a form post, invoke the controller */
$postAction = $_REQUEST[Bookshop\Controller::ACTION] ?? null;
if ($postAction != null) {
	Bookshop\Controller::getInstance()->invokePostAction();
}


include("views/" . $view . ".php");