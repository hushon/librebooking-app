<?php
/**
* Cron job to award daily credits and apply credit tax
*/

define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');
require_once(ROOT_DIR . 'lib/Config/Configuration.php');

Log::Debug('Running dailycredits.php');
JobCop::EnsureCommandLine();

try {
    $amount = floatval(Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_DAILY_AMOUNT));
    $groupIds = Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_DAILY_GROUPS);
    $groups = array_filter(array_map('intval', explode(',', $groupIds)));
    $taxPercent = floatval(Configuration::Instance()->GetSectionKey(ConfigSection::CREDITS, ConfigKeys::CREDITS_TAX_PERCENT));

    $groupRepo = new GroupRepository();
    $db = ServiceLocator::GetDatabase();
    foreach ($groups as $gid) {
        $group = $groupRepo->LoadById($gid);
        foreach ($group->UserIds() as $uid) {
            if ($amount > 0) {
                $db->Execute(new AdjustUserCreditsCommand($uid, -$amount, Resources::GetInstance()->GetString('NoteDailyCreditsAwarded')));
            }
            if ($taxPercent > 0) {
                $reader = $db->Query(new AdHocCommand('SELECT credit_count FROM users WHERE user_id = ' . intval($uid)));
                $credit = 0;
                if ($row = $reader->GetRow()) { $credit = $row[0]; }
                $reader->Free();
                $deduct = round($credit * $taxPercent / 100, 2);
                if ($deduct > 0) {
                    $db->Execute(new AdjustUserCreditsCommand($uid, $deduct, Resources::GetInstance()->GetString('NoteCreditTaxed')));
                }
            }
        }
    }
} catch (Exception $ex) {
    Log::Error('Error running dailycredits.php: %s', $ex);
}

Log::Debug('Finished running dailycredits.php');
