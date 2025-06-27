<?php

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Admin/ManageCreditAutomationPage.php');

$page = new AdminPageDecorator(new ManageCreditAutomationPage());
$page->PageLoad();
