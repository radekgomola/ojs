{**
 * templates/article/article.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article View.
 *}

{strip}
{if $galley}
	{assign var=pubObject value=$galley}
{else}
	{assign var=pubObject value=$article}
{/if}
{include file="article/header.tpl"}

{if (!$subscriptionRequired || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || $subscribedUser || $subscribedDomain)}
        {assign var=hasAccess value=1}
{else}
        {assign var=hasAccess value=0}
{/if}

{/strip}
{$article->getArticleNumber()}
{if $galley}
        
	{if $galley->isHTMLGalley()}
            <div class="htmlContents">
                {if $article->getLocalizedCitace() || ($citation && $showCitationHtml)}
                        <div id="articleCitace">
                            {if $article->getLocalizedCitace()} 
                                {$article->getLocalizedCitace()|strip_unsafe_html|nl2br}
                            {else}
                                {$citation}
                            {/if}
                        </div>
                {/if}
		{$galley->getHTMLContents()}
            </div>
	{elseif $galley->isPdfGalley()}
                {if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}
                    {foreach from=$article->getGalleys() item=galleyItem name=galleyList}
                            <script type="text/javascript">
                                vypisKdyzIE('<h4>{translate key=IE10.problem}</h4>','<h4>{translate key=IE11.problem}</h4>','<p>{translate key=IE.problem.redirect} <a target="_blank" href="{url page="article" op="viewFile" path=$article->getBestArticleId($currentJournal)|to_array:$galleyItem->getBestGalleyId($currentJournal)}" class="file" >{translate key=click.here}</a></p><p>{translate key=IE.problem.solution}</p>')
                            </script>
                            {if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
                                    {if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || !$galleyItem->isPdfGalley()}
                                            <img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
                                    {else}
                                            <img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
                                    {/if}
                            {/if}
                            
                    {/foreach}
                    {include file="article/pdfViewer.tpl" ga=$galley}                          
                {/if}
                 
		
	{/if}
{else}
	<div id="topBar">
		{if is_a($article, 'PublishedArticle')}{assign var=galleys value=$article->getGalleys()}{/if}
		{if $galleys && $subscriptionRequired && $showGalleyLinks}
			<div id="accessKey">
				<img src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
				{translate key="reader.openAccess"}&nbsp;
				<img src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
				{if $purchaseArticleEnabled}
					{translate key="reader.subscriptionOrFeeAccess"}
				{else}
					{translate key="reader.subscriptionAccess"}
				{/if}
			</div>
		{/if}
	</div>
	{if $coverPagePath}
		<div id="articleCoverImage"><img src="{$coverPagePath|escape}{$coverPageFileName|escape}"{if $coverPageAltText != ''} alt="{$coverPageAltText|escape}"{else} alt="{translate key="article.coverPage.altText"}"{/if}{if $width} width="{$width|escape}"{/if}{if $height} height="{$height|escape}"{/if}/>
		</div>
	{/if}
	{call_hook name="Templates::Article::Article::ArticleCoverImage"}
	<div id="articleTitle"><h3>{$article->getLocalizedTitle()|strip_unsafe_html}</h3></div>
	{if $journal->getSetting('allowMedailon')}
            {assign var=authors value=$article->getAuthors()}
            <div id="authorString">
                <em>
                    {foreach from=$authors item=author}
                        <a href="javascript:openRTWindow('{url op="editorialTeamBio" path=$author->getId()}')">{$author->getFullName()}</a>
                    {/foreach}
                </em>
            </div>
        {else}
            <div id="authorString"><em>{$article->getAuthorString()|escape}</em></div>
        {/if}
	<br />
	{if $article->getLocalizedAbstract()}
		<div id="articleAbstract">
		<h4>{translate key="article.abstract"}</h4>
		<br />
		<div>{$article->getLocalizedAbstract()|strip_unsafe_html|nl2br}</div>
		<br />
		</div>
	{/if}
        {if $article->getLocalizedCitace()}
		<div id="articleCitace">
		<h4>{translate key="article.citace"}</h4>
		<br />
		<div>{$article->getLocalizedCitace()|strip_unsafe_html|nl2br}</div>
		<br />
		</div>
        {else if $citation}
                <div id="articleCitace">
		<h4>{translate key="article.citace"}</h4>
		<br />
		<div>{$citation}</div>
		<br />
		</div>
	{/if}

	{if $article->getLocalizedSubject()}
		<div id="articleSubject">
		<h4>{translate key="article.subject"}</h4>
		<br />
		<div>{$article->getLocalizedSubject()|escape}</div>
		<br />
		</div>
	{/if}

	{*{if (!$subscriptionRequired || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || $subscribedUser || $subscribedDomain)}
		{assign var=hasAccess value=1}
	{else}
		{assign var=hasAccess value=0}
	{/if}*}

	{if $galleys}
            {assign var=test value="false"}
                <div id="articleFullText">
		<h4>{translate key="reader.fullText"}</h4>
		{if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}
			{foreach from=$article->getGalleys() item=galley name=galleyList}
                            {if $galley->isPdfGalley()}
                                    <script type="text/javascript">
                                        if(detectIE()===10 || detectIE()===11){ldelim}
                                            document.write('<a target="_blank" href="{url page="article" op="viewFile" path=$article->getBestArticleId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal)}" class="file" >{$galley->getGalleyLabel()|escape}</a>');                                    
                                        {rdelim}
                                        else{ldelim}
                                            document.write('<a href="{url page="article" op="view" path=$article->getBestArticleId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal)}" class="file" {if $galley->getRemoteURL()}target="_blank"{else}target="_parent"{/if}>{$galley->getGalleyLabel()|escape}</a>');
                                        {rdelim}                                    
                                    </script>
                            {else}
                            
				<a href="{url page="article" op="view" path=$article->getBestArticleId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal)}" class="file" {if $galley->getRemoteURL()}target="_blank"{else}target="_parent"{/if}>{$galley->getGalleyLabel()|escape}</a>
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
		{else}
			&nbsp;<a href="{url page="about" op="subscriptions"}" target="_parent">{translate key="reader.subscribersOnly"}</a>
		{/if}
		</div>
	{/if}
        <br />
	{if $citationFactory->getCount()}
		<div id="articleCitations">
		<h4>{translate key="submission.citations"}</h4>
		<div>
			{capture assign="references"}

				{iterate from=citationFactory item=citation}
  				<p>{$citation->getRawCitation()|strip_unsafe_html}</p>
  			{/iterate}
			{/capture}
			{include file="controllers/extrasOnDemand.tpl" id="references" moreDetailsText="references.more" lessDetailsText="references.less" extraContent=$references}
		</div>
		<br />
		</div>
	{/if}
{/if}

{foreach from=$pubIdPlugins item=pubIdPlugin}
	{if $issue->getPublished()}
		{assign var=pubId value=$pubIdPlugin->getPubId($pubObject)}
	{else}
		{assign var=pubId value=$pubIdPlugin->getPubId($pubObject, true)}{* Preview rather than assign a pubId *}
	{/if}
	{if $pubId}
		<br />
		<br />
		{$pubIdPlugin->getPubIdDisplayType()|escape}: {if $pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}<a id="pub-id::{$pubIdPlugin->getPubIdType()|escape}" href="{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}">{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}</a>{else}{$pubId|escape}{/if}
	{/if}
{/foreach}
{if $galleys}
	{foreach from=$pubIdPlugins item=pubIdPlugin}
		{foreach from=$galleys item=galley name=galleyList}
			{if $issue->getPublished()}
				{assign var=galleyPubId value=$pubIdPlugin->getPubId($galley)}
			{else}
				{assign var=galleyPubId value=$pubIdPlugin->getPubId($galley, true)}{* Preview rather than assign a pubId *}
			{/if}
			{if $galleyPubId}
				<br />
				<br />
				{$pubIdPlugin->getPubIdDisplayType()|escape} ({$galley->getGalleyLabel()|escape}): {if $pubIdPlugin->getResolvingURL($currentJournal->getId(), $galleyPubId)|escape}<a id="pub-id::{$pubIdPlugin->getPubIdType()|escape}-g{$galley->getId()}" href="{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $galleyPubId)|escape}">{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $galleyPubId)|escape}</a>{else}{$galleyPubId|escape}{/if}
			{/if}
		{/foreach}
	{/foreach}
{/if}
{call_hook name="Templates::Article::MoreInfo"}
{include file="article/comments.tpl"}

{include file="article/footer.tpl"}
