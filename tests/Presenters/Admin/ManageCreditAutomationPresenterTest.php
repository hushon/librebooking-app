<?php
require_once(ROOT_DIR . 'Presenters/Admin/ManageCreditAutomationPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageCreditAutomationPage.php');

class ManageCreditAutomationPresenterTest extends TestBase
{
    private $page;
    private $presenter;
    private $config;

    public function setUp(): void
    {
        parent::setUp();
        $this->page = new FakeCreditAutomationPage();
        $this->config = new FakeConfigurator();
        $this->presenter = new ManageCreditAutomationPresenter($this->page, $this->config);
    }


    public function testSaveWritesSettings()
    {
        $this->page->_form = [
            'scheduleAmount'=>'3',
            'schedulePeriod'=>'2',
            'scheduleTime'=>'01:00',
            'maxCredit'=>'9'
        ];
        $this->presenter->Save();
        $this->assertEquals('3', $this->config->_settings['credits'][ConfigKeys::CREDITS_SCHEDULE_AMOUNT]);
    }
}

class FakeCreditAutomationPage extends ManageCreditAutomationPage
{
    public $_form = [];
    public $_Amount;
    public $_Period;
    public $_Time;
    public $_Max;

    public function __construct(){}

    public function GetForm($key){return $this->_form[$key] ?? null;}

    public function BindSettings($a,$p,$t,$m){$this->_Amount=$a;$this->_Period=$p;$this->_Time=$t;$this->_Max=$m;}
}

class FakeConfigurator implements IConfigurationSettings
{
    public $_settings = [];
    public function GetSettings($file){return $this->_settings;}
    public function BuildConfig($c,$n,$r=false){}
    public function WriteSettings($path,$set){$this->_settings=$set;}
    public function CanOverwriteFile($f){return true;}
}
