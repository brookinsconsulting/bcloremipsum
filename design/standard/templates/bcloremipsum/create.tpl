{def $parameter_node=false()
     $can_create=true()
     $parameterClass=fetch( 'content', 'class', hash( 'class_id', $parameters.class ) )
     $attributes=fetch( 'class', 'attribute_list', hash( 'class_id', $parameters.class ) )
     $creation_warning_expensive_count=75
     $class_edit_url=concat( '/class/edit/', $parameters.class )|ezurl( 'no' )
     $attribute_data_type_strings=array()
     $unsupported_and_required_attribute_data_type_strings=array()
     $classes=fetch( 'class', 'list' )
     $last_found_class_attribute_datatype_unsupported=false()}

{foreach $attributes as $attribute}
    {if and( $datatypes_supported|contains( $attribute.data_type_string ), $attribute_data_type_strings|contains( $attribute.data_type_string )|not )}
        {set $attribute_data_type_strings=$attribute_data_type_strings|append( $attribute.data_type_string )}
    {else}
        {if and( $attribute.is_required, $datatypes_supported|contains( $attribute.data_type_string )|not, $unsupported_and_required_attribute_data_type_strings|contains( $attribute.data_type_string )|not )}
            {set $unsupported_and_required_attribute_data_type_strings=$unsupported_and_required_attribute_data_type_strings|append( $attribute.data_type_string )}
            {if $can_create|ne( false() )}
                {set $can_create=false()}
            {/if}
        {/if}
    {/if}
{/foreach}

{literal}
<script type="text/javascript">
<!--
    function lips_inc( el )
    {
        if ( !isNaN( el.value ) )
        {
            el.value++;
        }
    }

    function lips_inc_max( el, max )
    {
        if ( !isNaN( el.value ) && el.value <= max - 1 )
        {
            el.value++;
        }
    }

    function lips_dec( el )
    {
        if ( !isNaN( el.value ) && 1 <= el.value )
        {
            el.value -= 1; // double minus cannot be used here
        }
    }
// -->
</script>
{/literal}

<form name="loremipsum" action={'bcloremipsum/create'|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create nodes in content tree of eZ Publish'|i18n( 'extension/bcloremipsum/create' )}</h1>
<p>{'Dynamically generated content using Lorem Ipsum text and image content'|i18n( 'extension/bcloremipsum/create' )}.</p>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="header-row">

    {section show=is_set( $parameters.created_count)}
    <div class="message-feedback-good">
    <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span>
        <span class="bump-left">{'Created %1 objects/nodes in %2 seconds%3%3%5Review the following parent nodes for the newly created nodes%4contained within'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', $parameters.created_count, '%2', $parameters.used_time, '%3', '<br />', '%4', '<br />', '%5', '<span class="small-notice-text">' ) )}: {if $parameters.nodes|count|gt( 0 )}{foreach $parameters.nodes as $index => $nodeID}{set $parameter_node=fetch( 'content', 'node', hash( 'node_id', $nodeID ) )}<a href={$parameter_node.url_alias|ezurl}>{$parameter_node.name|wash}</a>{if $index|sum( 1 )|lt( $parameters.nodes|count )}, {/if}{/foreach}</a>{/if}</span></h2>
    </div>
    {/section}

    {if $can_create|not}
    <div class="message-error-custom">
        <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span>
        <span class="asterisk">*</span> {'Error: Class selected requires attribute content using an unsupported datatype'|i18n( 'extension/bcloremipsum/create', '', hash( ) )}!</h2>
    </div>
    {/if}

    <div class="classes-of-nodes">
        <label>{'Class of nodes to create'|i18n( 'extension/bcloremipsum/create' )}</label>
        <select class="classes-of-nodes-select" name="Parameters[class]" onchange="this.form.submit()">
        {foreach $classes as $class}
            <option value="{$class.id}"{if $class.id|eq( $parameters.class )} selected="selected"{/if}>{$class.name|wash}</option>
        {/foreach}
        </select>

        <noscript>
        <input class="button" type="submit" value="Update" />
        </noscript>

        {* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
        {def $totalNodeCreationCount=$parameters.nodes|count|mul( $parameters.count )|wash}
        <div class="block">
            {if and( $can_create, $parameters.nodes)}
                <input class="button create-button" type="submit" name="CreateButton" value="Start creating nodes" onclick="return confirm( '{'Warning%1%2You are about to request creation of '|i18n( 'extension/bcloremipsum/create', '', hash( '%1', ':', '%2', '\\n\\n' ) )}' + ( document.forms.loremipsum['Parameters[count]'].value * ( nodes=(typeof document.forms.loremipsum['Parameters[nodes][]'].length != 'undefined' )?document.forms.loremipsum['Parameters[nodes][]'].length:1 ) ) + '{' node(s) in the content tree'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', ':', '%2', '\\n\\n', '%3', $totalNodeCreationCount ) )}.{if $totalNodeCreationCount|gt( $creation_warning_expensive_count )} {'This may be a potentially expensive operation to complete'|i18n( 'extension/bcloremipsum/create' )}.{/if}{'%1This can not be easily undone!%2Are you absolutely certain you wish to create these nodes'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '\\n\\n', '%2', '\\n\\n' ) )}?' )" />
            {else}
                <input class="button-disabled" disabled="disabled" type="submit" name="CreateButton" value="Start creating nodes" />
            {/if}
        </div>
        {* DESIGN: Control bar END *}</div></div></div></div></div></div>
    </div>

</div>

<div class="header-spacer">
</div>

<div class="tight-block-right-secondary">
    <fieldset>
        <legend>{'Class'|i18n( 'extension/bcloremipsum/create' )}</legend>
        <p>{'Review and customize the default class parameters by editing the %1%2 class%3'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', concat( '<a href="', $class_edit_url, '" title="Edit class">' ), '%2', $parameterClass.name|wash, '%3', '</a>' ) )}.</p>

        <table class="list" cellspacing="0" border="0">
            <tr>
                <th>{'Name'|i18n( 'extension/bcloremipsum/create' )}</th>
                <th>{'ID'|i18n( 'extension/bcloremipsum/create' )}</th>
                <th>{'Attributes'|i18n( 'extension/bcloremipsum/create' )}</th>
                <th>{'Description'|i18n( 'extension/bcloremipsum/create' )}</th>
            </tr>
            <tr>
                <td>{$parameterClass.name|wash}</td>
                <td>{$parameterClass.id|wash}</td>
                <td>{$parameterClass.data_map|count|wash}</td>
                <td>{if $parameterClass.description|eq( '' )}No class description{else}{$parameterClass.description|wash}{/if}</td>
            </tr>
        </table>
    </fieldset>
    <br />
    <fieldset>
        <legend>{'Node attributes'|i18n( 'extension/bcloremipsum/create' )}</legend>
        <p>{'Review and customize node attribute content default parameters'|i18n( 'extension/bcloremipsum/create' )}.</p>

        <table class="list" cellspacing="0">
            <tr>
                <th>{'Name'|i18n( 'extension/bcloremipsum/create' )}</th>
                <th>{'Type'|i18n( 'extension/bcloremipsum/create' )}</th>
                <th>{'Content'|i18n( 'extension/bcloremipsum/create' )}</th>
            </tr>

        {foreach $attributes as $attribute sequence array( 'bglight','bgdark' ) as $tr_class}

            <tr class="{$tr_class}">
                <td>{$attribute.name|wash} {if $attribute.is_required}*{/if}</td>
                <td>{$attribute.data_type_string|wash}</td>
                <td>
                    {switch match=$attribute.data_type_string}

                    {case match="ezstring"}
                        {'Generate'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min_words]"
                        value="{first_set( $parameters.attributes[$attribute.id].min_words,4)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_words" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_words]" value="{first_set( $parameters.attributes[$attribute.id].max_words,6)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_words" />' );
                        // -->
                        </script>
                        {'words'|i18n( 'extension/bcloremipsum/create' )}.
                        <map id="par_{$attribute.id}_min_words" name="par_{$attribute.id}_min_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_words" name="par_{$attribute.id}_max_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezdatetime"}
                        {'Generate a unix timestamp before current date time'|i18n( 'extension/bcloremipsum/create' )}. <input name="Parameters[attributes][{$attribute.id}][min_timestamp]" value="{first_set( $parameters.attributes[$attribute.id].min_timestamp, rand( 0, currentdate() ) )}" size="11" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_timestamp" />' );
                        // -->
                        </script>
                        <map id="par_{$attribute.id}_min_timestamp" name="par_{$attribute.id}_min_timestamp">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_timestamp]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_timestamp]'])" alt="" />
                        </map>
                        {'to'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][max_timestamp]" value="{first_set( $parameters.attributes[$attribute.id].max_timestamp, currentdate() )}" size="11" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_timestamp" />' );
                        // -->
                        </script>
                        <map id="par_{$attribute.id}_max_timestamp" name="par_{$attribute.id}_max_timestamp">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_timestamp]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_timestamp]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezxmltext"}
                        {'Generate'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min_pars]" value="{first_set( $parameters.attributes[$attribute.id].min_pars, 4 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_pars" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_pars]" value="{first_set( $parameters.attributes[$attribute.id].max_pars, 6 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_pars" />' );
                        // -->
                        </script>
                        {'paragraphs%1 each'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', ',<br />' ) )} <input name="Parameters[attributes][{$attribute.id}][min_sentences]" value="{first_set( $parameters.attributes[$attribute.id].min_sentences, 4 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_sentences" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_sentences]" value="{first_set( $parameters.attributes[$attribute.id].max_sentences, 6 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_sentences" />' );
                        // -->
                        </script>
                        {'sentences'|i18n( 'extension/bcloremipsum/create' )}.
                        <map id="par_{$attribute.id}_min_pars" name="par_{$attribute.id}_min_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_pars" name="par_{$attribute.id}_max_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_min_sentences" name="par_{$attribute.id}_min_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_sentences" name="par_{$attribute.id}_max_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                        </map>
                    {/case}

                    {case match="eztext"}
                        {'Generate'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min_pars]" value="{first_set( $parameters.attributes[$attribute.id].min_pars, 1 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_pars" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_pars]" value="{first_set( $parameters.attributes[$attribute.id].max_pars, 1 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_pars" />' );
                        // -->
                        </script>
                        {'paragraphs%1 each'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', ',' ) )} <input name="Parameters[attributes][{$attribute.id}][min_sentences]" value="{first_set( $parameters.attributes[$attribute.id].min_sentences, 4 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_sentences" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_sentences]" value="{first_set( $parameters.attributes[$attribute.id].max_sentences, 6 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_sentences" />' );
                        // -->
                        </script>
                        {'sentences'|i18n( 'extension/bcloremipsum/create' )}.
                        <map id="par_{$attribute.id}_min_pars" name="par_{$attribute.id}_min_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_pars" name="par_{$attribute.id}_max_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_min_sentences" name="par_{$attribute.id}_min_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_sentences" name="par_{$attribute.id}_max_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezboolean"}
                        {'Generate %1true%2 with the probability'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<i>', '%2', '</i>' ) )} <input name="Parameters[attributes][{$attribute.id}][prob]" value="{first_set( $parameters.attributes[$attribute.id].prob, 50 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_prob" />' );
                        // -->
                        </script>
                        %.
                        <map id="par_{$attribute.id}_prob" name="par_{$attribute.id}_prob">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc_max( document.forms.loremipsum['Parameters[attributes][{$attribute.id}][prob]'], 100 )" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][prob]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezimage"}
                        {'Insert %1image%2 with the probability'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<i>', '%2', '</i>' ) )} <input name="Parameters[attributes][{$attribute.id}][prob]" value="{first_set( $parameters.attributes[$attribute.id].prob, 100 )}" size="3" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_prob" />' );
                        // -->
                        </script>
                        %.
                        <map id="par_{$attribute.id}_prob" name="par_{$attribute.id}_prob">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc_max( document.forms.loremipsum['Parameters[attributes][{$attribute.id}][prob]'], 100 )" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][prob]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezinteger"}
                        {'Generate an integer number from'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min]" value="{first_set( $parameters.attributes[$attribute.id].min, 0 )}" size="5" />
                        {'to'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][max]" value="{first_set( $parameters.attributes[$attribute.id].max, 999 )}" size="5" />
                    {/case}

                    {case match="ezfloat"}
                        {'Generate number from'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min]" value="{first_set( $parameters.attributes[$attribute.id].min, 0 )}" size="5" />
                        {'to'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][max]" value="{first_set( $parameters.attributes[$attribute.id].max, 999 )}" size="5" />
                    {/case}

                    {case match="ezprice"}
                        {'Generate a price from'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min]" value="{first_set( $parameters.attributes[$attribute.id].min, 0 )}" size="5" />
                        {'to'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][max]" value="{first_set( $parameters.attributes[$attribute.id].max, 999 )}" size="5" />
                    {/case}

                    {case match="ezuser"}
                        <input name="Parameters[attributes][{$attribute.id}]" value="1" type="hidden" />
                        {'Auto generating user'|i18n( 'extension/bcloremipsum/create' )}.
                    {/case}

  					{case match="ezkeyword"}
                        {'Generate'|i18n( 'extension/bcloremipsum/create' )} <input name="Parameters[attributes][{$attribute.id}][min_words]"
                        value="{first_set( $parameters.attributes[$attribute.id].min_words, 4 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_words" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_words]" value="{first_set( $parameters.attributes[$attribute.id].max_words, 6 )}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_words" />' );
                        // -->
                        </script>
                        {'words'|i18n( 'extension/bcloremipsum/create' )}.
                        <map id="par_{$attribute.id}_min_words" name="par_{$attribute.id}_min_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_words" name="par_{$attribute.id}_max_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.loremipsum['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                        </map>
                    {/case}

                    {case}{set $last_found_class_attribute_datatype_unsupported=$attribute.data_type_string|wash}<span class="asterisk">*</span> {'Not supported'|i18n( 'extension/bcloremipsum/create' )}.{if $attribute.is_required} {'%1It is not possible to use this class%2. %3See notes below%4'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<span class="underline">', '%2', '</span>', '%3', '<b>', '%4', '</b>' ) )}!</b>{/if}{/case}

                    {/switch}
                </td>
            </tr>
        {/foreach}
        </table>
    </fieldset>
</div>

<div class="parent-nodes">
    {if $can_create}
    <fieldset>
        <legend>{'Parent nodes'|i18n( 'extension/bcloremipsum/create' )} [{$parameters.nodes|count}]</legend>
        <p>{'Select the parent nodes you want to create content under in the content tree'|i18n( 'extension/bcloremipsum/create' )}.</p>

        {if $parameters.nodes}
            <table class="list" cellspacing="0" border=1>
                <tr>
                    <th class="tight">&nbsp;</th>
                    <th>{'Path'|i18n( 'extension/bcloremipsum/create' )}</th>
                </tr>
                {foreach $parameters.nodes as $node_id sequence array( 'bglight','bgdark' ) as $tr_class}
                    {def $node=fetch( 'content', 'node', hash( 'node_id', $node_id ) )}

                    {if $node}
                        <tr class="{$tr_class}">
                            <td><input type="checkbox" name="DeleteNodeIDArray[]" value="{$node_id}" /></td>
                            <td>
                                {foreach $node.path as $path_item}
                                    {$path_item.name|wash} /
                                {/foreach}
                                {$node.name|wash}
                                <input type="hidden" name="Parameters[nodes][]" value="{$node_id|wash}" />
                            </td>
                        </tr>
                    {/if}

                    {undef $node}
                {/foreach}
            </table>
        {else}
            <p>{'The node list is empty'|i18n( 'extension/bcloremipsum/create' )}.</p>
        {/if}

        <input class="button{if $parameters.nodes|not}-disabled{/if}" {if $parameters.nodes|not}disabled="disabled"{/if} type="submit" name="DeleteNodesButton" value="Remove selected" />
        <input class="button" type="submit" name="AddNodeButton" value="Add node" />
    </fieldset>

   <div class="secondary-options">
        <div class="quick-mode">
            <label><input type="checkbox" name="Parameters[quick]"{if and(is_set( $parameters.quick ), $parameters.quick )} checked="checked"{else} checked="checked"{/if} />{'Quick Mode'|i18n( 'extension/bcloremipsum/create' )}</label>
            <p class="quick-mode-summary">{'With %1Quick Mode%2 enabled, both content object indexing and all workflows will not be executed.%3'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<i>', '%2', '</i>', '%3', '<br />' ) )}
            <span class="small-notice-text">{'%3 %1Quick Mode%2 is approximately, 5 times faster than normal execution.%3The default is most often the best choice available'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<i>', '%2', '</i>', '%3', '<br />' ) )}.</span></p>
        </div>

        <div class="number-of-nodes-per-parent">
            <label>{'Number of nodes to generate%1under each parent node'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<br />' ) )}</label>
            <p class="number-of-nodes-per-parent-input">
                <input name="Parameters[count]" value="{$parameters.count|wash}" size="5" />
            </p>
        </div>

    </div>
    {else}
        {if $can_create|not}
        <div class="larger-warning">
            <h2>{'%3Please choose another class%4 %9%9 %7Presently it is not supported to create nodes using the currently selected content class%8%9 %9 %11 To use this class you would first have to %5%6 to change class attributes using the currently unsupported datatype as required and change the class attribute to the not required state. Save your changes and return here to use the class normally.'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '.', '%2', '<br /><br />', '%3', '<span class="larger-warning-text">', '%4', '</span>', '%5', concat( '<a href="', $class_edit_url, '" title="Edit class">', 'edit the class definition' ), '%6', '</a>', '%7', '<span class="underline">', '%8', '</span>', '%9', '<br />', '%10', ',', '%11', '<span class="asterisk">*</span>' ) )}. <br /><br /> {'%1 If this current installation of eZ Publish is not a development copy of your website%4s production copy of eZ Publish%7 best practices strongly recommended that you do not edit classes or change the class attribute%4s required%5 Please make sure you have full db and filesystem backups before making changes to content classes%5%2'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<span class="small-notice-text">', '%2', '</span>', '%3', '(', '%4', "'", '%5', '.', '%6', ')', '%7', ',' ) )} <br /><br /> {'%1 If you would like to request support be added for your currently required datatype,%4%10%11 please submit a feature request to our %8project forums%9%5%2'|i18n( 'extension/bcloremipsum/create', '', hash( '%1', '<span class="small-notice-text">', '%2', '</span>', '%3', '(', '%4', "'<span class=\"slightly-larger-text\">", '%5', '.', '%6', ')', '%7', ',', '%8', '<a href="http://projects.ez.no/bcloremipsum">', '%9', '</a>', '%10', $last_found_class_attribute_datatype_unsupported, '%11', "</span>'" ) )}</h2>
        </div>
        {/if}
    {/if}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

</div>

</form>
