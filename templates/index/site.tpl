{**
* templates/index/site.tpl
*
* Copyright (c) 2013-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Site index.
*
*}
{strip}
    {if $siteTitle}
        {assign var="pageTitleTranslated" value=$siteTitle}
    {/if}
    {include file="common/header.tpl"}
{/strip}

<br />

{if $intro}<div id="intro" class="description">{$intro|nl2br}</div>{/if}

<a name="journals"></a>
<p>{translate key="site.journal.fakulty"} {foreach from=$facultyList item=faculty}<a href="{url searchFaculty=$faculty sort="title"}">{if $faculty == $searchFaculty}<strong>{$faculty|escape}</strong>{else}{$faculty|escape}{/if}</a>, {/foreach}<a href="{url}">{if $searchFaculty==''}<strong>{translate key="common.all"}</strong>{else}{translate key="common.all"}{/if}</a></p>

<p>{translate key="site.journal.databaze"} {foreach from=$databaseList item=database}<a href="{url searchDatabase=$database sort="title"}">{if $database == $searchDatabase}<strong>{$database|escape}</strong>{else}{$database|escape}{/if}</a>, {/foreach}<a href="{url}">{if $searchDatabase==''}<strong>{translate key="common.all"}</strong>{else}{translate key="common.all"}{/if}</a></p>


{if $useAlphalist}
    <p>{foreach from=$alphaList item=letter}<a href="{url searchInitial=$letter sort="title"}">{if $letter == $searchInitial}<strong>{$letter|escape}</strong>{else}{$letter|escape}{/if}</a> {/foreach}<a href="{url}">{if $searchInitial==''}<strong>{translate key="common.all"}</strong>{else}{translate key="common.all"}{/if}</a></p>
{/if}

{iterate from=journals item=journal}
<table>
    <tr><td>
            {assign var=externiCasopis value=$journal->getSetting('externiCasopis')}
            {assign var=odkazCasopis value=$journal->getSetting('odkazCasopis')}
            {assign var=odkazCislo value=$journal->getSetting('odkazCislo')}
            {assign var=databaze value=$journal->getSetting('databaze')}
            {if $site->getSetting('showTitle')}
                <h3>{$journal->getLocalizedTitle()|escape}</h3>
            {/if}
            {if $site->getSetting('showThumbnail')}
                {assign var="displayJournalThumbnail" value=$journal->getLocalizedSetting('journalThumbnail')}
                <div style="clear:left;">
                    {if $displayJournalThumbnail && is_array($displayJournalThumbnail)}
                        {assign var="altText" value=$journal->getLocalizedSetting('journalThumbnailAltText')}
                        <div class="homepageImage">
                            {if $externiCasopis && $odkazCasopis && $odkazCasopis != ""}
                                <a href="{$odkazCasopis}" class="action" target="_blank">
                            {else}
                                <a href="{url journal=$journal->getPath()}" class="action">
                            {/if}
                                <img src="{$journalFilesPath}{$journal->getId()}/{$displayJournalThumbnail.uploadName|escape:"url"}" {if $altText != ''}alt="{$altText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} />
                            </a>
                        </div>
                            {/if}
                </div>
            {/if}

            {if $site->getSetting('showDescription')}
                {if $journal->getLocalizedDescription()}
                    <p class="description" style="text-align:justify;" id="journalDescription-{$journal->getId()|escape}">{$journal->getLocalizedDescription()|nl2br|strip_tags}</p>
                {/if}    
            {/if}
            {if $databaze && $databaze != ""}
                    <p class="databaze" id="journalDatabaze-{$journal->getId()|escape}">{translate key="site.journal.databaze"} {$databaze|nl2br|strip_tags}</p>
            {/if}  
            <p style="padding-top:-31px; min-width:700px;">
                {if $externiCasopis && $odkazCasopis && $odkazCasopis != ""}
                    <a href="{$odkazCasopis}" class="action" target="_blank">
                {else}
                    <a href="{url journal=$journal->getPath()}" class="action">
                {/if}
                    {translate key="site.journalView"}
                </a>
                    <span class="space"></span>  
                {if $externiCasopis && $odkazCislo && $odkazCislo != ""}
                    <a href="{$odkazCislo}" class="action" target="_blank">
                        {translate key="site.journalCurrent"}
                    </a>
                    <span class="space"></span>
                {elseif $externiCasopis}
                {else}
                    <a href="{url journal=$journal->getPath() page="issue" op="current"}" class="action">
                        {translate key="site.journalCurrent"}
                    </a>
                    <span class="space"></span>
                {/if}                 
                    
                {if !$externiCasopis}
                    <a href="{url journal=$journal->getPath() page="user" op="register"}" class="action">
                        {translate key="site.journalRegister"}
                    </a>
                {/if}
            </p>
        </td></tr>
</table>  
{/iterate}
{if $journals->wasEmpty()}
    {translate key="site.noJournals"}
{/if}

<div id="journalListPageInfo">{page_info iterator=$journals}</div>
<div id="journalListPageLinks">{page_links anchor="journals" name="journals" iterator=$journals}</div>
{include file="common/footer.tpl"}

