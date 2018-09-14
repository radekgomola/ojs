{**
 * templates/issue/issue.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue
 *
 *}
{foreach name=sections from=$publishedArticles item=section key=sectionId}

{if $section.title}<h4 class="tocSectionTitle">{$section.title|escape}</h4>{/if}

{foreach from=$section.articles item=article}
        {assign var=pubObject value=$article}
	{assign var=articlePath value=$article->getBestArticleId($currentJournal)}
	{assign var=articleId value=$article->getId()}
        {assign var=skipGalleyId value=$article->getSkipGalleyId()}
        {assign var=skipLandingPage value=$article->getSkipLandingPage()}
        
        {if $skipLandingPage && $skipGalleyId && $skipGalleyId > 0}
            {assign var=skipGalley value=$article->getGalleyById($skipGalleyId)}
        {else}
            {assign var=skipGalley value=0}
        {/if}

	{if $article->getLocalizedFileName() && $article->getLocalizedShowCoverPage() && !$article->getHideCoverPageToc($locale)}
		{assign var=showCoverPage value=true}
	{else}
		{assign var=showCoverPage value=false}
	{/if}

	{if $article->getLocalizedAbstract() == ""}
		{assign var=hasAbstract value=0}
	{else}
		{assign var=hasAbstract value=1}
	{/if}
        
        {if $article->getLocalizedCitace() == ""}
		{assign var=hasCitace value=0}
	{else}
		{assign var=hasCitace value=1}
	{/if}

	{if (!$subscriptionRequired || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || $subscribedUser || $subscribedDomain || ($subscriptionExpiryPartial && $articleExpiryPartial.$articleId))}
		{assign var=hasAccess value=1}
	{else}
		{assign var=hasAccess value=0}
	{/if}
<table class="tocArticle">
<tr valign="top">
	<td class="tocArticleCoverImage{if $showCoverPage} showCoverImage{/if}">
		{if $showCoverPage}
			<div class="tocCoverImage">
				{if !$hasAccess || $hasAbstract}<a href="{url page="article" op="view" path=$articlePath}" class="file">{/if}
				<img src="{$coverPagePath|escape}{$article->getLocalizedFileName()|escape}"{if $article->getCoverPageAltText($locale) != ''} alt="{$article->getCoverPageAltText($locale)|escape}"{else} alt="{translate key="article.coverPage.altText"}"{/if}/>
				{if !$hasAccess || $hasAbstract}</a>{/if}
			</div>
		{/if}
	</td>

	{call_hook name="Templates::Issue::Issue::ArticleCoverImage"}

	<td class="tocArticleTitle{if $showCoverPage} showCoverImage{/if}" colspan="2">
		<div class="tocTitle">
                    {if $skipGalley}
                        {if $skipGalley->isPdfGalley()}
                                <script type="text/javascript">
                                    if(detectIE()===10 || detectIE()===11){ldelim}
                                        document.write('<a href="{url page="article" op="viewFile" path=$articlePath|to_array:$skipGalley->getBestGalleyId($currentJournal)}" target="_blank">{$article->getLocalizedTitle()|strip_unsafe_html}</a>');                                    
                                    {rdelim}
                                    else{ldelim}
                                        document.write('<a href="{url page="article" op="view" path=$articlePath|to_array:$skipGalley->getBestGalleyId($currentJournal)}" {if $skipGalley->getRemoteURL()}target="_blank" {/if}>{$article->getLocalizedTitle()|strip_unsafe_html}</a>');
                                    {rdelim}                                    
                                </script>
                        {else}

                            <a href="{url page="article" op="view" path=$articlePath|to_array:$skipGalley->getBestGalleyId($currentJournal)}" {if $skipGalley->getRemoteURL()}target="_blank" {/if}>{$article->getLocalizedTitle()|strip_unsafe_html}</a>
                        {/if}
                        {if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
                                {if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || !$skipGalley->isPdfGalley()}
                                        <img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
                                {else}
                                        <img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
                                {/if}
                        {/if}
                    {else}
			{if !$hasAccess || $hasAbstract || $hasCitace}
				<a href="{url page="article" op="view" path=$articlePath}">{$article->getLocalizedTitle()|strip_unsafe_html}</a>
			{else}
				{$article->getLocalizedTitle()|strip_unsafe_html}
			{/if}
                    {/if}
		</div>
	</td>
</tr>
<tr class="tocArticleAuthorsTr">
    <td class="tocArticleAuthors">
    {*<div class="tocAuthors">*}
			{if (!$section.hideAuthor && $article->getHideAuthor() == $smarty.const.AUTHOR_TOC_DEFAULT) || $article->getHideAuthor() == $smarty.const.AUTHOR_TOC_SHOW}
				{*{foreach from=$article->getAuthors() item=author name=authorList}
					{$author->getFullName()|escape}{if !$smarty.foreach.authorList.last},{/if}
				{/foreach}&nbsp;&nbsp;<br />*}
                                {foreach from=$article->getAuthors() item=author name=authorList key=i}
                                    {assign var=firstName value=$author->getFirstName()}
                                    {if $journal->getSetting('allowTOCMedailonCitations')}
                                            {if $author->getData('journalAuthorId') AND $author->getData('journalAuthorId') > 0}
                                                <a href="{url page="about" op="editorialTeamBioFullProfile" path=$author->getData('journalAuthorId')}">
                                            {else}
                                                <a href="{url page="about" op="bioAuthor" path=$author->getId()}">
                                            {/if}
                                            {$author->getFullName()|escape}</a>{if !$smarty.foreach.authorList.last},{/if}
                                    {else}
                                            {$author->getFullName()|escape}{if !$smarty.foreach.authorList.last},{/if}
                                    {/if}
                                {/foreach}
			{else}
				&nbsp;
			{/if}
                        
		{*</div>*}
    </td>
	<td class="tocArticleGalleysPages{if $showCoverPage} showCoverImage{/if}">
		<div class="tocGalleys">
			{if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}
				{foreach from=$article->getGalleys() item=galley name=galleyList}
					 {if $galley->isPdfGalley()}
                                                <script type="text/javascript">
                                                    if(detectIE()===10 || detectIE()===11){ldelim}
                                                        document.write('<a href="{url page="article" op="viewFile" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" target="_blank" class="file">{$galley->getGalleyLabel()|escape}</a>');                                    
                                                    {rdelim}
                                                    else{ldelim}
                                                        document.write('<a href="{url page="article" op="view" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" {if $galley->getRemoteURL()}target="_blank" {/if}class="file">{$galley->getGalleyLabel()|escape}</a>');
                                                    {rdelim}                                    
                                                </script>
                                        {else}
                                            
                                            <a href="{url page="article" op="view" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" {if $galley->getRemoteURL()}target="_blank" {/if}class="file">{$galley->getGalleyLabel()|escape}</a>
                                        {/if}
					{if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
						{if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || !$galley->isPdfGalley()}
							<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
						{else}
							<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
						{/if}
					{/if}
				{/foreach}
				{if $subscriptionRequired && $showGalleyLinks && !$restrictOnlyPdf}
					{if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN}
						<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
					{else}
						<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
					{/if}
				{/if}
			{/if}
		</div>
		<div class="tocPages">
			{$article->getPages()|escape}
		</div>
	</td>
</tr>
<tr class="tocArticleDoi">
    <td class="tocDoi">
        <div class="tocAuthors">
        {foreach from=$pubIdPlugins item=pubIdPlugin}
                {if $issue->getPublished()}
                        {assign var=pubId value=$pubIdPlugin->getPubId($pubObject)}
                {else}
                        {assign var=pubId value=$pubIdPlugin->getPubId($pubObject, true)}{* Preview rather than assign a pubId *}
                {/if}
                {if $pubId}
                        <span class="citation_doi">{$pubIdPlugin->getPubIdDisplayType()|escape}: </span>{if $pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}<a id="pub-id::{$pubIdPlugin->getPubIdType()|escape}" href="{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}">{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}</a>{else}{$pubId|escape}{/if}
                {/if}
        {/foreach}
        </div>
    </td>
    <td class="tocArticleNumber">
        {if $article->getArticleNumber()}
            <div class="tocArticleNumber">
                {translate key="article.clanek.articleNumber"}: {$article->getArticleNumber()}
            </div>
        {/if}
    </td>
</tr>
</table>
{call_hook name="Templates::Issue::Issue::Article"}
{/foreach}

{if !$smarty.foreach.sections.last}
<div class="separator"></div>
{/if}
{/foreach}

