{**
 * plugins/blocks/cz_en_blok_small/block.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- CZ EN toggle.
 *
 *}
{if $enableCz_en_blok_small}
<div class="block" id="sidebarCzEnSmall">
        {if $jazyk == "cs_CZ"}
            <a href="{url page="user" op="setLocale" path="en_US" source=$smarty.server.REQUEST_URI}" />{translate key=plugins.block.cz_en_blok_small.en}</a>
        {else}
            <a href="{url page="user" op="setLocale" path="cs_CZ" source=$smarty.server.REQUEST_URI}" />{translate key=plugins.block.cz_en_blok_small.cz}</a>
        {/if}
</div>
{/if}
