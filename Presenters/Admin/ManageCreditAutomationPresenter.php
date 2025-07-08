<?php
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Config/Configurator.php');

class ManageCreditAutomationActions
{
    public const SAVE = 'save';
}

class ManageCreditAutomationPresenter extends ActionPresenter
{
    private $page;
    private $groups;
    private $config;
    private $configFile;

    public function __construct(IManageCreditAutomationPage $page, IGroupRepository $groups, IConfigurationSettings $config)
    {
        parent::__construct($page);
        $this->page = $page;
        $this->groups = $groups;
        $this->config = $config;
        $this->configFile = ROOT_DIR . 'config/config.php';
        $this->AddAction(ManageCreditAutomationActions::SAVE, 'Save');
    }

    public function PageLoad()
    {
        $groupList = $this->groups->GetList()->Results();
        $selected = explode(',', Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_DAILY_GROUPS));
        $this->page->BindGroups($groupList, $selected);

        $amount = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_DAILY_AMOUNT);
        $time = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_DAILY_TIME);
        $tax = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_TAX_PERCENT);
        $this->page->BindSettings($amount, $time, $tax);
    }

    public function Save()
    {
        $settings = $this->config->GetSettings($this->configFile);
        $settings['credits'][ConfigKeys::CREDITS_DAILY_AMOUNT] = $this->page->GetDailyAmount();
        $settings['credits'][ConfigKeys::CREDITS_DAILY_GROUPS] = implode(',', (array)$this->page->GetGroups());
        $settings['credits'][ConfigKeys::CREDITS_DAILY_TIME] = $this->page->GetDailyTime();
        $settings['credits'][ConfigKeys::CREDITS_TAX_PERCENT] = $this->page->GetTaxPercent();
        $this->config->WriteSettings($this->configFile, $settings);
    }
}
