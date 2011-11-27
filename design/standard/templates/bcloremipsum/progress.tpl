<h1>{'Please wait'|i18n('extension/bcloremipsum/create')}</h1>

<div class="bar">
<div class="status"" style="width: {$parameters.created_count|mul(100)|div($parameters.total_count)|round}%" >
</div>
</div>

<p>
{'Created %1 of %2 objects'|i18n('extension/bcloremipsum/create', '', hash( '%1', $parameters.created_count, '%2', $parameters.total_count ) )} (<b>{$parameters.created_count|mul(100)|div($parameters.total_count)|round}%</b>).
</p>

<p>
{'Start time'|i18n('extension/bcloremipsum/create')}: {$parameters.start_time|l10n('datetime')}, actual time: {$parameters.time|l10n('datetime')}<br />
<b>{'Estimated time to finish'|i18n('extension/bcloremipsum/create')}: {$parameters.time|sub($parameters.start_time)|div($parameters.created_count)|mul($parameters.total_count)|round|sum($parameters.start_time)|l10n('datetime')}</b>
</p>

<form name="li_form" action={"/bcloremipsum/create"|ezurl} method="post">
<div>
    <input type="hidden" name="ParametersSerialized" value="{$parameters_serialized|wash}" />
    <input type="hidden" name="GenerateButton" value="1" />
    <noscript>
        <input id="submit" type="submit" class="button" value="Continue" />
    </noscript>
</div>
</form>

{literal}
<script type="text/javascript">
<!--
    window.onload = new function()
    {
        document.forms.li_form.submit();
    }
// -->
</script>
{/literal}
