<?php
require_once(ROOT_DIR . 'Presenters/Admin/ManageCreditAutomationPresenter.php');

class ManageCreditAutomationPresenterTest extends TestBase
{
    private $page;
    private $presenter;
    private $groupRepo;
    private $config;

    public function setUp(): void
    {
        parent::setUp();
        $this->page = new FakeCreditAutomationPage();
        $this->groupRepo = new FakeGroupRepository();
        $this->config = new FakeConfigurator();
        $this->presenter = new ManageCreditAutomationPresenter($this->page, $this->groupRepo, $this->config);
    }

    public function testPageLoadBindsGroups()
    {
        $group = new Group(1, 'g');
        $this->groupRepo->_groups = [new GroupItemView($group->Id(), $group->Name())];
        $this->config->_settings = ['credits'=>[ConfigKeys::CREDITS_DAILY_GROUPS=>'1',ConfigKeys::CREDITS_DAILY_AMOUNT=>'5',ConfigKeys::CREDITS_DAILY_TIME=>'00:00',ConfigKeys::CREDITS_TAX_PERCENT=>'1']];
        $this->presenter->PageLoad();
        $this->assertEquals(1, count($this->page->_Groups));
    }
}

class FakeCreditAutomationPage extends ManageCreditAutomationPage
{
    public $_Groups;
    public $_Selected;
    public $_Amount;
    public $_Time;
    public $_Tax;

    public function __construct(){parent::__construct();}
    public function BindGroups($groups, $selected){$this->_Groups=$groups;$this->_Selected=$selected;}
    public function BindSettings($a,$t,$x){$this->_Amount=$a;$this->_Time=$t;$this->_Tax=$x;}
}

class FakeGroupRepository implements IGroupRepository
{
    public $_groups = [];
    public function GetList($p=null,$s=null,$sf=null,$sd=null,$f=null){return new PageableData($this->_groups, count($this->_groups),1,1);}
    public function LoadById($id){return new Group($id,'g');}
    public function Add(Group $g){}
    public function Update(Group $g){}
    public function Remove(Group $g){}
    public function GetUsersInGroup($ids,$pn=null,$ps=null,$f=null,$st=AccountStatus::ALL){}
    public function GetGroupsByRole($r){}
    public function GetPermissionList(){}
}

class FakeConfigurator implements IConfigurationSettings
{
    public $_settings=[];
    public function GetSettings($file){return $this->_settings;}
    public function BuildConfig($c,$n,$r=false){}
    public function WriteSettings($path,$set){$this->_settings=$set;}
    public function CanOverwriteFile($f){return true;}
}
