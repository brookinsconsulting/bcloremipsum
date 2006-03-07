<h1>Please wait</h1>

<div class="bar">
<div class="status"" style="width: {$parameters.created_count|mul(100)|div($parameters.total_count)|round}%" >
</div>
</div>

<p>
Created {$parameters.created_count} of {$parameters.total_count} objects (<b>{$parameters.created_count|mul(100)|div($parameters.total_count)|round}%</b>).
</p>

<p>
Start time: {$parameters.start_time|l10n('datetime')}, actual time: {$parameters.time|l10n('datetime')}<br />
<b>Estimated time to finish: {$parameters.time|sub($parameters.start_time)|div($parameters.created_count)|mul($parameters.total_count)|round|sum($parameters.start_time)|l10n('datetime')}</b>
</p>

<form name="li_form" action={"/lorem/ipsum"|ezurl} method="post">
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
