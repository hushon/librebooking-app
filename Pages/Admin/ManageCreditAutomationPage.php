<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageCreditAutomationPresenter.php');

interface IManageCreditAutomationPage extends IActionPage
{
    public function GetScheduleAmount();
    public function GetSchedulePeriod();
    public function GetScheduleTime();
    public function GetMaxCredit();
    public function GetImmediateAmount();
    public function BindSettings($amount, $period, $time, $max);
}

class ManageCreditAutomationPage extends ActionPage implements IManageCreditAutomationPage
{
    private $presenter;

    public function __construct()
    {
        parent::__construct('ManageCreditAutomation', 1);
        $this->presenter = new ManageCreditAutomationPresenter($this, new Configurator());
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();
        $this->Display('Admin/manage_credit_automation.tpl');
    }

    public function ProcessDataRequest($dataRequest){}

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function GetScheduleAmount()
    {
        return $this->GetForm('scheduleAmount');
    }

    public function GetSchedulePeriod()
    {
        return $this->GetForm('schedulePeriod');
    }

    public function GetScheduleTime()
    {
        return $this->GetForm('scheduleTime');
    }

    public function GetMaxCredit()
    {
        return $this->GetForm('maxCredit');
    }

    public function GetImmediateAmount()
    {
        return $this->GetForm('immediateAmount');
    }

    public function BindSettings($amount, $period, $time, $max)
    {
        $this->Set('ScheduleAmount', $amount);
        $this->Set('SchedulePeriod', $period);
        $this->Set('ScheduleTime', $time);
        $this->Set('MaxCredit', $max);
    }
}
