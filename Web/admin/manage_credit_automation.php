<?php
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', dirname(__DIR__, 2) . '/');
}

require_once(ROOT_DIR . 'Pages/Admin/ManageCreditAutomationPage.php');

$page = new AdminPageDecorator(new ManageCreditAutomationPage());
$page->PageLoad();
