{**
 * plugins/blocks/articles/block.tpl
 *
 *
 *}
{if isset($top10articles)}
<div class="block" id="top10articles">
    <span class="blockTitle">{translate key=plugins.block.top10articles.title}</span>
    <ol class="top10articlesList">
        {foreach from=$top10articles item=arrayRecord}
            {assign var=cesta value=$arrayRecord[0]}
            {assign var=nazev value=$arrayRecord[1]}
            <li><a href="{url page="article" op="view" path=$cesta}" title="{$nazev}">{$nazev|truncate:55}</a></li>
        {/foreach}
    </ol>
</div>
{/if}
