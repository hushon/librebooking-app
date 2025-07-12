<?php
/**
 * Cron job to award scheduled credits to all users
 */

define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/UserRepository.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');
require_once(ROOT_DIR . 'lib/Config/Configuration.php');
require_once(ROOT_DIR . 'lib/Config/Configurator.php');
require_once(ROOT_DIR . 'lib/Common/Date.php');

Log::Debug('Running scheduledcredits.php');
JobCop::EnsureCommandLine();

try {
    $amount = floatval(Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_SCHEDULE_AMOUNT));
    $period = intval(Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_SCHEDULE_PERIOD));
    $max = floatval(Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_MAX_BALANCE));
    $last = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_LAST_RUN);

    if ($amount <= 0 || $period <= 0) {
        return;
    }

    $shouldRun = true;
    if (!empty($last)) {
        $lastDate = Date::FromDatabase($last);
        $diff = DateDiff::BetweenDates($lastDate, Date::Now())->Days();
        if ($diff < $period) {
            $shouldRun = false;
        }
    }

    if ($shouldRun) {
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
                $db->Execute(new AdjustUserCreditsCommand($user->UserId, -$grant, Resources::GetInstance()->GetString('NoteDailyCreditsAwarded')));
            }
        }

        $conf = new Configurator();
        $settings = $conf->GetSettings(ROOT_DIR . 'config/config.php');
        $settings['credits'][ConfigKeys::CREDITS_LAST_RUN] = Date::Now()->ToDatabase();
        $conf->WriteSettings(ROOT_DIR . 'config/config.php', $settings);
    }
} catch (Exception $ex) {
    Log::Error('Error running scheduledcredits.php: %s', $ex);
}

Log::Debug('Finished running scheduledcredits.php');

