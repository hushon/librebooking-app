{include file='globalheader.tpl'}

<div id="page-manage-credit-automation" class="admin-page">
    <h1 class="border-bottom mb-3">{translate key=ManageCreditAutomation}</h1>
    <form id="credit-automation-form" method="post" action="manage_credit_automation.php">
        <div class="mb-3 d-flex align-items-end">
            <div>
                <label for="immediateAmount" class="form-label">{translate key="Credits"}</label>
                <input type="number" step="0.01" class="form-control w-auto" name="immediateAmount" id="immediateAmount">
            </div>
            <button type="submit" name="{QueryStringKeys::ACTION}" value="updateNow" class="btn btn-secondary ms-2">{translate key="Update"}</button>
        </div>
        <fieldset class="border p-3">
            <legend class="float-none w-auto px-2">{translate key="Scheduled"}</legend>
            <div class="mb-3">
                <label for="scheduleAmount" class="form-label">{translate key="Credits"}</label>
                <input type="number" step="0.01" class="form-control w-auto" name="scheduleAmount" id="scheduleAmount" value="{$ScheduleAmount}">
            </div>
            <div class="mb-3">
                <label for="schedulePeriod" class="form-label">{translate key="PeriodDays"}</label>
                <input type="number" class="form-control w-auto" name="schedulePeriod" id="schedulePeriod" value="{$SchedulePeriod}">
            </div>
            <div class="mb-3">
                <label for="scheduleTime" class="form-label">{translate key="Time"}</label>
                <input type="time" class="form-control w-auto" name="scheduleTime" id="scheduleTime" value="{$ScheduleTime}">
            </div>
            <div class="mb-3">
                <label for="maxCredit" class="form-label">{translate key="Maximum"}</label>
                <input type="number" step="0.01" class="form-control w-auto" name="maxCredit" id="maxCredit" value="{$MaxCredit}">
            </div>
            <button type="submit" name="{QueryStringKeys::ACTION}" value="save" class="btn btn-primary">{translate key="Save"}</button>
        </fieldset>
    </form>
</div>

{include file='globalfooter.tpl'}
