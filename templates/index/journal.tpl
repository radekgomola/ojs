{**
* templates/index/journal.tpl
*
* Copyright (c) 2013-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Journal index page.
*
*}
{strip}
    {assign var="pageTitleTranslated" value=$siteTitle}
    {include file="common/header.tpl"}
{/strip}
{if $journalDescription}
    <div id="journalDescription">{$journalDescription}</div>
{/if}

{call_hook name="Templates::Index::journal"}

{if $homepageImage}
    <div id="homepageImage"><img src="{$publicFilesDir}/{$homepageImage.uploadName|escape:"url"}" width="{$homepageImage.width|escape}" height="{$homepageImage.height|escape}" {if $homepageImageAltText != ''}alt="{$homepageImageAltText|escape}"{else}alt="{translate key="common.journalHomepageImage.altText"}"{/if} /></div>
    {/if}

{if $additionalHomeContent}
    <div id="additionalHomeContent">{$additionalHomeContent}</div>
{/if}
<div class="special_block">
{if $enableAnnouncementsHomepage}
    {* Display announcements *}
    <div id="announcementsHome">
        <h3>{translate key="announcement.announcementsHome"}</h3>
        {include file="announcement/list.tpl"}	
        <table class="announcementsMore">
            <tr>
                <td><a href="{url page="announcement"}">{translate key="announcement.moreAnnouncements"}</a></td>
            </tr>
        </table>
    </div>
{/if}
{if $currentJournal->getId() == 67}
    {call_hook|assign:"rightSidebarCode" name="Templates::Common::RightSidebar"}
    <div id="sidebar">
        {assign var=blok value="0"} 
        {if $rightSidebarCode}   
            <div id="rightSidebar">
                {$rightSidebarCode}
            </div>
        {/if}
    </div>
{/if}
</div>


{if $issue && $currentJournal->getSetting('publishingMode') != $smarty.const.PUBLISHING_MODE_NONE}
    {* Display the table of contents or cover page of the current issue. *}
    {assign var=coverPageName value=$issue->getFileName($locale)}
    {if $coverPageName}<div class="coverImageHome"><div id="issueCoverImage"><a href="{$currentUrl}"><img src="{$coverPagePath|escape}{$issue->getFileName($locale)|escape}"{if $coverPageAltText != ''} alt="{$coverPageAltText|escape}"{else} alt="{translate key="issue.coverPage.altText"}"{/if}{if $width} width="{$width|escape}"{/if}{if $height} height="{$height|escape}"{/if}/></a></div></div>{/if}
    <div id="issueHome">
        <h3 class="issueTitle">{$issue->getIssueIdentification()|strip_unsafe_html|nl2br}</h3>
        {include file="issue/view.tpl"}
    </div>
{/if}

{include file="common/footer.tpl"}

