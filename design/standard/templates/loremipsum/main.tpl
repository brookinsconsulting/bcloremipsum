{section show=is_set($parameters.created_count)}
<div class="message-feedback">
<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> 
    Generated {$parameters.created_count} objects/nodes in {$parameters.used_time} seconds</h2>
</div>
{/section}

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

<form name="lips" action={"lorem/ipsum"|ezurl} method="post">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Lorem Ipsum Generator for eZ publish'|i18n('generator')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
    <fieldset>
        <legend>Parent nodes [{$parameters.nodes|count}]</legend>

        {if $parameters.nodes}
            <table class="list" cellspacing="0">
                <tr>
                    <th class="tight">&nbsp;</th>
                    <th>Path</th>
                </tr>
                {foreach $parameters.nodes as $node_id sequence array('bglight','bgdark') as $tr_class}
                    {def $node=fetch('content','node',hash('node_id',$node_id))}

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
            <p>The node list is empty.</p>
        {/if}

        <input class="button{if $parameters.nodes|not}-disabled{/if}" {if $parameters.nodes|not}disabled="disabled"{/if} type="submit" name="DeleteNodesButton" value="Remove selected" />
        <input class="button" type="submit" name="AddNodeButton" value="Add node" />
    </fieldset>
</div>

<div class="block">
    <label>Number of nodes to generate under each node</label>

    <p>
        <input name="Parameters[count]" value="{$parameters.count|wash}" size="5" />
    </p>
</div>

<div class="block">
    <label>Class of objects to create</label>

    {def $classes=fetch('class','list')}

    <select name="Parameters[class]" onchange="this.form.submit()">
    {foreach $classes as $class}
        <option value="{$class.id}"{if $class.id|eq($parameters.class)} selected="selected"{/if}>{$class.name|wash}</option>
    {/foreach}
    </select>

    <noscript>
    <input class="button" type="submit" value="Update" />
    </noscript>
</div>

{def $can_create=true()
     $attributes=fetch('class','attribute_list',hash('class_id',$parameters.class))}

<div class="block">
    <fieldset>
        <legend>Attributes</legend>

        <table class="list" cellspacing="0">
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Settings</th>
            </tr>

        {foreach $attributes as $attribute sequence array('bglight','bgdark') as $tr_class}
            <tr class="{$tr_class}">
                <td>{$attribute.name|wash}{if $attribute.is_required}*{/if}</td>
                <td>{$attribute.data_type_string|wash}</td>
                <td>
                    {switch match=$attribute.data_type_string}

                    {case match="ezstring"}
                        Generate <input name="Parameters[attributes][{$attribute.id}][min_words]" 
                        value="{first_set($parameters.attributes[$attribute.id].min_words,4)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_words" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_words]" value="{first_set($parameters.attributes[$attribute.id].max_words,6)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_words" />' );
                        // -->
                        </script>
                        words.
                        <map id="par_{$attribute.id}_min_words" name="par_{$attribute.id}_min_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_words" name="par_{$attribute.id}_max_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezxmltext"}
                        Generate <input name="Parameters[attributes][{$attribute.id}][min_pars]" value="{first_set($parameters.attributes[$attribute.id].min_pars,4)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_pars" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_pars]" value="{first_set($parameters.attributes[$attribute.id].max_pars,6)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_pars" />' );
                        // -->
                        </script>
                        paragraphs, each <input name="Parameters[attributes][{$attribute.id}][min_sentences]" value="{first_set($parameters.attributes[$attribute.id].min_sentences,4)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_sentences" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_sentences]" value="{first_set($parameters.attributes[$attribute.id].max_sentences,6)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_sentences" />' );
                        // -->
                        </script>
                        sentences.
                        <map id="par_{$attribute.id}_min_pars" name="par_{$attribute.id}_min_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_pars" name="par_{$attribute.id}_max_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_min_sentences" name="par_{$attribute.id}_min_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_sentences" name="par_{$attribute.id}_max_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                        </map>
                    {/case}

                    {case match="eztext"}
                        Generate <input name="Parameters[attributes][{$attribute.id}][min_pars]" value="{first_set($parameters.attributes[$attribute.id].min_pars,1)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_pars" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_pars]" value="{first_set($parameters.attributes[$attribute.id].max_pars,1)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_pars" />' );
                        // -->
                        </script>
                        paragraphs, each <input name="Parameters[attributes][{$attribute.id}][min_sentences]" value="{first_set($parameters.attributes[$attribute.id].min_sentences,4)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_sentences" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_sentences]" value="{first_set($parameters.attributes[$attribute.id].max_sentences,6)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_sentences" />' );
                        // -->
                        </script>
                        sentences.
                        <map id="par_{$attribute.id}_min_pars" name="par_{$attribute.id}_min_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][min_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_pars" name="par_{$attribute.id}_max_pars">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][max_pars]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_min_sentences" name="par_{$attribute.id}_min_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][min_sentences]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_sentences" name="par_{$attribute.id}_max_sentences">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][max_sentences]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezboolean"}
                        Generate <i>true</i> with the probability <input name="Parameters[attributes][{$attribute.id}][prob]" value="{first_set($parameters.attributes[$attribute.id].prob,50)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_prob" />' );
                        // -->
                        </script>
                        %.
                        <map id="par_{$attribute.id}_prob" name="par_{$attribute.id}_prob">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc_max(document.forms.lips['Parameters[attributes][{$attribute.id}][prob]'],100)" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][prob]'])" alt="" />
                        </map>
                    {/case}
                   
                   
                    {case match="ezimage"}
                        Insert <i>image</i> with the probability <input name="Parameters[attributes][{$attribute.id}][prob]" value="{first_set($parameters.attributes[$attribute.id].prob,100)}" size="3" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_prob" />' );
                        // -->
                        </script>
                        %.
                        <map id="par_{$attribute.id}_prob" name="par_{$attribute.id}_prob">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc_max(document.forms.lips['Parameters[attributes][{$attribute.id}][prob]'],100)" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][prob]'])" alt="" />
                        </map>
                    {/case}

                    {case match="ezinteger"}
                        Generate an integer number from <input name="Parameters[attributes][{$attribute.id}][min]" value="{first_set($parameters.attributes[$attribute.id].min,0)}" size="5" />
                        to <input name="Parameters[attributes][{$attribute.id}][max]" value="{first_set($parameters.attributes[$attribute.id].max,999)}" size="5" />
                    {/case}

                    {case match="ezfloat"}
                        Generate number from <input name="Parameters[attributes][{$attribute.id}][min]" value="{first_set($parameters.attributes[$attribute.id].min,0)}" size="5" />
                        to <input name="Parameters[attributes][{$attribute.id}][max]" value="{first_set($parameters.attributes[$attribute.id].max,999)}" size="5" />
                    {/case}

                    {case match="ezprice"}
                        Generate a price from <input name="Parameters[attributes][{$attribute.id}][min]" value="{first_set($parameters.attributes[$attribute.id].min,0)}" size="5" />
                        to <input name="Parameters[attributes][{$attribute.id}][max]" value="{first_set($parameters.attributes[$attribute.id].max,999)}" size="5" />
                    {/case}

                    {case match="ezuser"}
                        <input name="Parameters[attributes][{$attribute.id}]" value="1" type="hidden" />
                        Auto generating user.
                    {/case}

  					{case match="ezkeyword"}
                        Generate <input name="Parameters[attributes][{$attribute.id}][min_words]" 
                        value="{first_set($parameters.attributes[$attribute.id].min_words,4)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_min_words" />' );
                        // -->
                        </script>
                        - <input name="Parameters[attributes][{$attribute.id}][max_words]" value="{first_set($parameters.attributes[$attribute.id].max_words,6)}" size="2" /><script type="text/javascript">
                        <!--
                            document.writeln( '<img class="lips-arrows" src={"lips-arrows.png"|ezimage} border="0" alt="" usemap="#par_{$attribute.id}_max_words" />' );
                        // -->
                        </script>
                        words.
                        <map id="par_{$attribute.id}_min_words" name="par_{$attribute.id}_min_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][min_words]'])" alt="" />
                        </map>
                        <map id="par_{$attribute.id}_max_words" name="par_{$attribute.id}_max_words">
                            <area nohref="nohref" shape="rect" coords="0,0,10,9" onmouseup="lips_inc(document.forms.lips['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                            <area nohref="nohref" shape="rect" coords="0,10,10,19" onmouseup="lips_dec(document.forms.lips['Parameters[attributes][{$attribute.id}][max_words]'])" alt="" />
                        </map>
                    {/case}


                    {case}
                        Not supported.
                        {if $attribute.is_required}
                        It is not possible to use this class. <b>See notes below!</b>
                        {set $can_create=false()}
                        {/if}
                    {/case}

                    {/switch}
                </td>
            </tr>
        {/foreach}

        </table>
    </fieldset>
</div>

<div class="block">
<label><input type="checkbox" name="Parameters[quick]"{if and(is_set($parameters.quick),$parameters.quick)} checked="checked"{/if} /> Quick Mode</label>
<p>With <i>Quick Mode</i> on, indexing and workflows will not be executed. <i>Quick Mode</i> is approx. 5 times quicker.</p>
</div>

{if $can_create|not}
<div class="block">
<p>
You selected a class which contains mandatory attributes of not supported datatypes.
It is not possible to generate objects of this class. <b>Please choose another class</b>
or alter the class definition to make the attribute(s) not required.
</p>
</div>
{/if}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    {if and($can_create,$parameters.nodes)}
        <input class="button" type="submit" name="GenerateButton" value="Generate" />
    {else}
        <input class="button-disabled" disabled="disabled" type="submit" name="GenerateButton" value="Generate" />
    {/if}
</div>              

{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
