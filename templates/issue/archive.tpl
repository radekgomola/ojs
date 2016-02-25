{**
 * templates/issue/archive.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue Archive.
 *
 *}
{strip}
{assign var="pageTitle" value="archive.archives"}
{include file="common/header.tpl"}
{/strip}

<div id="issues">
{iterate from=issues item=issue}
	{if $issue->getYear() != $lastYear}
		{if !$notFirstYear}
			{assign var=notFirstYear value=1}
		{else}
			</div>
			<div class="separator" style="clear:left;"></div>
		{/if}
		<div class="oneYearIssues">
		<h3>{$issue->getYear()|escape}</h3>
		{assign var=lastYear value=$issue->getYear()}
	{/if}

	
	{if $issue->getFileName($locale)}
		{assign var="coverLocale" value="$locale"}
	{else}
		{assign var="coverLocale" value="$primaryLocale"}
	{/if}
	{if $issue->getFileName($coverLocale) && $issue->getShowCoverPage($coverLocale) && !$issue->getHideCoverPageArchives($coverLocale)}
        <div id="issue-{$issue->getId()}" class="oneIssue oneIssueWithCover" style="clear:left;">
		<div class="issueArchiveTitle"><h4><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">{$issue->getIssueIdentification()|escape}</a></h4></div>
                <div class="issueArchiveTitleShort"><h4><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">{$issue->getIssueIdentification(false,false,false,false)|escape}</a></h4></div>
                <div class="issueCoverImage"><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}"><img src="{$coverPagePath|escape}{$issue->getFileName($coverLocale)|escape}"{if $issue->getCoverPageAltText($coverLocale) != ''} alt="{$issue->getCoverPageAltText($coverLocale)|escape}"{else} alt="{translate key="issue.coverPage.altText"}"{/if}/></a>
		</div>
		{if $issue->getLocalizedCoverPageDescription()}<div class="issueCoverDescription">{$issue->getLocalizedCoverPageDescription()|strip_unsafe_html|nl2br}</div>{/if}
	{else}
        <div id="issue-{$issue->getId()}" class="oneIssue" style="clear:left;">
            <div class="issueArchiveTitle"><h4><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">{$issue->getIssueIdentification()|escape}</a></h4></div>
            <div class="issueArchiveTitleShort"><h4><a href="{url op="view" path=$issue->getBestIssueId($currentJournal)}">{$issue->getIssueIdentification(false,false,false,false)|escape}</a></h4></div>
		{if $issue->getLocalizedDescription()}<div class="issueDescription">{$issue->getLocalizedDescription()|strip_unsafe_html|nl2br}</div>{/if}
	{/if}
	</div>

{/iterate}
{if $notFirstYear}<br /></div>{/if}
<div class="archivePaging">
{if !$issues->wasEmpty()}
	{page_info iterator=$issues}&nbsp;&nbsp;&nbsp;&nbsp;
	{page_links anchor="issues" name="issues" iterator=$issues}
{else}
	{translate key="current.noCurrentIssueDesc"}
{/if}
</div>
</div>
{include file="common/footer.tpl"}

