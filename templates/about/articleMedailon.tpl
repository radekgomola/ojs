{**
 * templates/about/articleMedailon.tpl
 *
 *
 *}
{strip}
{assign var="pageTitle" value="about.profile"}
{include file="common/header.tpl"}
{/strip}

<p>
	
	{if $publishEmail && !$user->getAllowPublishingEmail()}
		{assign_mailto var=address address=$user->getEmail()|escape}
		<p><strong>E-mail:</strong> {icon name="mail" url=$address}</p>
	{/if}
	{if $user->getUrl()}<a href="{$user->getUrl()|escape:"quotes"}" target="_new">{$user->getUrl()|escape}</a><br/>{/if}
	{if $user->getLocalizedAffiliation()}{$user->getLocalizedAffiliation()|escape}{assign var=needsComma value=1}{/if}{if $country}{if $needsComma}, {/if}{$country|escape}{/if}
</p>
  <div class="teamBioBiography">
      {$user->getLocalizedBiography()|nl2br|strip_unsafe_html}
  </div>

{if $publishedArticles|@count > 0}  
  <ul>
{foreach from=$publishedArticles item=article}
	{assign var=issueId value=$article->getIssueId()}
	{assign var=issue value=$issues[$issueId]}
	{assign var=issueUnavailable value=$issuesUnavailable.$issueId}
	{assign var=sectionId value=$article->getSectionId()}
	{assign var=journalId value=$article->getJournalId()}
	{assign var=journal value=$journals[$journalId]}
	{assign var=section value=$sections[$sectionId]}
        {assign var=skipGalleyId value=$article->getSkipGalleyId()}
        {assign var=skipLandingPage value=$article->getSkipLandingPage()}
        {assign var=articlePath value=$article->getBestArticleId($currentJournal)}

        {if $skipLandingPage && $skipGalleyId && $skipGalleyId > 0}
            {assign var=skipGalley value=$article->getGalleyById($skipGalleyId)}
        {else}
            {assign var=skipGalley value=0}
        {/if}
	{if $issue->getPublished() && $section && $journal}
	<li>
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
                    <a href="{url journal=$journal->getPath() page="article" op="view" path=$article->getBestArticleId()}" class="file"><span class="clanek">{$article->getLocalizedTitle()|strip_unsafe_html}</span></a>
                {else}
                        {$article->getLocalizedTitle()|strip_unsafe_html}
                {/if}
            {/if}
            </a><br />
		{if (!$issueUnavailable || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN)}
		{foreach from=$article->getGalleys() item=galley name=galleyList}
                    {if $galley->isPdfGalley()}
                            <script type="text/javascript">
                                if(detectIE()===10 || detectIE()===11){ldelim}
                                    document.write('<a href="{url journal=$journal->getPath() page="article" op="viewFile" path=$article->getBestArticleId()|to_array:$galley->getBestGalleyId($journal)}" class="file">{$galley->getGalleyLabel()|escape}</a>');                                    
                                {rdelim}
                                else{ldelim}
                                    document.write('<a href="{url journal=$journal->getPath() page="article" op="view" path=$article->getBestArticleId()|to_array:$galley->getBestGalleyId($journal)}" class="file">{$galley->getGalleyLabel()|escape}</a>');
                                {rdelim}                                    
                            </script> | 
                    {else}
                        {*<a href="{url page="article" op="view" path=$articlePath|to_array:$galley->getBestGalleyId($currentJournal)}" {if $galley->getRemoteURL()}target="_blank" {/if}class="file">{$galley->getGalleyLabel()|escape}</a>*}
                        <a href="{url journal=$journal->getPath() page="article" op="view" path=$article->getBestArticleId()|to_array:$galley->getBestGalleyId($journal)}" class="file">{$galley->getGalleyLabel()|escape}</a> | 
                    {/if}
		{*<a href="{url journal=$journal->getPath() page="article" op="view" path=$article->getBestArticleId()|to_array:$galley->getBestGalleyId($journal)}" class="file" target="_new">{$galley->getGalleyLabel()|escape}</a>*}
		{/foreach} 
		{/if}<em><a href="{url journal=$journal->getPath() page="issue" op="view" path=$issue->getBestIssueId()}">{$issue->getIssueIdentification()|strip_unsafe_html|nl2br}</a> - {$section->getLocalizedTitle()|escape}</em><br />
                
		
	</li>
	{/if}
{/foreach}
</ul>
{/if}
<br />

{include file="common/footer.tpl"}

