<?php
require_once('../AdminPage.php');
require_once('../../Pages/Admin/ManageCreditAutomationPage.php');
$page = new AdminPageDecorator(new ManageCreditAutomationPage());
$page->PageLoad();
