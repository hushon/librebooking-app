<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageCreditAutomationPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/GroupRepository.php');
require_once(ROOT_DIR . 'lib/Config/Configuration.php');

interface IManageCreditAutomationPage extends IActionPage
{
    public function GetDailyAmount();
    public function GetDailyTime();
    public function GetTaxPercent();
    public function GetGroups();
    public function BindGroups($groups, $selected);
    public function BindSettings($amount, $time, $tax);
}

class ManageCreditAutomationPage extends ActionPage implements IManageCreditAutomationPage
{
    private $presenter;

    public function __construct()
    {
        parent::__construct('ManageCreditAutomation', 1);
        $this->presenter = new ManageCreditAutomationPresenter($this, new GroupRepository(), new Configurator());
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();
        // template lives under tpl/Admin, include folder name so Smarty resolves correctly
        $this->Display('Admin/manage_credit_automation.tpl');

    }
    public function ProcessDataRequest($dataRequest){}

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function GetDailyAmount()
    {
        return $this->GetForm('dailyAmount');
    }

    public function GetDailyTime()
    {
        return $this->GetForm('dailyTime');
    }

    public function GetTaxPercent()
    {
        return $this->GetForm('taxPercent');
    }

    public function GetGroups()
    {
        return $this->GetForm('groups');
    }

    public function BindGroups($groups, $selected)
    {
        $this->Set('Groups', $groups);
        $this->Set('SelectedGroups', $selected);
    }

    public function BindSettings($amount, $time, $tax)
    {
        $this->Set('DailyAmount', $amount);
        $this->Set('DailyTime', $time);
        $this->Set('TaxPercent', $tax);
    }
}
