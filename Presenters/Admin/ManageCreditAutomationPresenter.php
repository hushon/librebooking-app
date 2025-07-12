<?php
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Config/Configurator.php');
require_once(ROOT_DIR . 'Domain/Access/UserRepository.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');


class ManageCreditAutomationActions
{
    public const SAVE = 'save';
    public const UPDATE_NOW = 'updateNow';
}

class ManageCreditAutomationPresenter extends ActionPresenter
{
    private $page;
    private $config;
    private $configFile;

    public function __construct(IManageCreditAutomationPage $page, IConfigurationSettings $config)
    {
        parent::__construct($page);
        $this->page = $page;
        $this->config = $config;
        $this->configFile = ROOT_DIR . 'config/config.php';
        $this->AddAction(ManageCreditAutomationActions::SAVE, 'Save');
        $this->AddAction(ManageCreditAutomationActions::UPDATE_NOW, 'UpdateNow');
    }

    public function PageLoad()
    {
        $amount = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_SCHEDULE_AMOUNT);
        $period = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_SCHEDULE_PERIOD);
        $time = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_SCHEDULE_TIME);
        $max = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_MAX_BALANCE);
        $this->page->BindSettings($amount, $period, $time, $max);

    }

    public function Save()
    {
        $settings = $this->config->GetSettings($this->configFile);
        $settings['credits'][ConfigKeys::CREDITS_SCHEDULE_AMOUNT] = $this->page->GetScheduleAmount();
        $settings['credits'][ConfigKeys::CREDITS_SCHEDULE_PERIOD] = $this->page->GetSchedulePeriod();
        $settings['credits'][ConfigKeys::CREDITS_SCHEDULE_TIME] = $this->page->GetScheduleTime();
        $settings['credits'][ConfigKeys::CREDITS_MAX_BALANCE] = $this->page->GetMaxCredit();
        $this->config->WriteSettings($this->configFile, $settings);
    }

    public function UpdateNow()
    {
        $amount = floatval($this->page->GetImmediateAmount());
        $max = floatval(Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_MAX_BALANCE));
        if ($amount <= 0) {
            return;
        }
        $this->AwardCredits($amount, $max);
    }

    private function AwardCredits($amount, $max)
    {
        $repo = new UserRepository();
        $db = ServiceLocator::GetDatabase();
        $users = $repo->GetAll();
        foreach ($users as $user) {
            $current = floatval($user->CurrentCreditCount());
            $grant = $amount;
            if ($max > 0 && ($current + $amount) > $max) {
                $grant = max(0, $max - $current);
            }
            if ($grant > 0) {
                $db->Execute(new AdjustUserCreditsCommand($user->UserId, -$grant, Resources::GetInstance()->GetString('CreditsUpdatedLog', [ServiceLocator::GetServer()->GetUserSession()] )));
            }
        }
    }

}
