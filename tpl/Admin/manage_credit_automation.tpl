{include file='globalheader.tpl'}

<div id="page-manage-credit-automation" class="admin-page">
    <h1 class="border-bottom mb-3">{translate key=ManageCreditAutomation}</h1>
    <form id="credit-automation-form" method="post" action="manage_credit_automation.php">
        <div class="mb-3">
            <label for="dailyAmount" class="form-label">{translate key="Credits"}</label>
            <input type="number" step="0.01" class="form-control w-auto" name="dailyAmount" id="dailyAmount" value="{$DailyAmount}">
        </div>
        <div class="mb-3">
            <label for="dailyTime" class="form-label">{translate key="Time"}</label>
            <input type="time" class="form-control w-auto" name="dailyTime" id="dailyTime" value="{$DailyTime}">
        </div>
        <div class="mb-3">
            <label for="taxPercent" class="form-label">{translate key="Tax"}</label>
            <input type="number" step="0.01" class="form-control w-auto" name="taxPercent" id="taxPercent" value="{$TaxPercent}">
        </div>
        <div class="mb-3">
            <label for="groups" class="form-label">{translate key="Groups"}</label>
            <select multiple class="form-select" name="groups[]" id="groups">
                {foreach from=$Groups item=g}
                    <option value="{$g->Id()}" {if in_array($g->Id(), $SelectedGroups)}selected{/if}>{$g->Name()}</option>
                {/foreach}
            </select>
        </div>
        <input type="hidden" name="{QueryStringKeys::ACTION}" value="save" />
        <button type="submit" class="btn btn-primary">{translate key="Save"}</button>
    </form>
</div>

{include file='globalfooter.tpl'}
