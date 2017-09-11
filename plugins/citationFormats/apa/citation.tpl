{**
* plugins/citationFormats/apa/citation.tpl
*
* Copyright (c) 2013-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Article reading tools -- Capture Citation APA format
*
*}

{if $galley}
	{url|assign:"articleUrl" page="article" op="view" path=$article->getBestArticleId()|to_array:$galley->getBestGalleyId()}
{else}
	{url|assign:"articleUrl" page="article" op="view" path=$article->getBestArticleId()}
{/if}

<div id="citation">
    {assign var=authors value=$article->getAuthors()}
    {assign var=authorCount value=$authors|@count}
    {foreach from=$authors item=author name=authors key=i}
        {assign var=firstName value=$author->getFirstName()}
        {if $journal->getSetting('allowMedailonCitations')}
                {if $author->getData('journalAuthorId') AND $author->getData('journalAuthorId') > 0}
                    <a href="{url page="about" op="editorialTeamBioFullProfile" path=$author->getData('journalAuthorId')}">
                {else}
                    <a href="{url page="about" op="bioAuthor" path=$author->getId()}">
                {/if}
                {$author->getLastName()|escape}, {$firstName|escape|truncate:1:"":true}.</a>{if $i==$authorCount-2}, &amp; {elseif $i<$authorCount-1}, {/if}
        {else}
                {$author->getLastName()|escape}, {$firstName|escape|truncate:1:"":true}.{if $i==$authorCount-2}, &amp; {elseif $i<$authorCount-1}, {/if}
        {/if}
        {/foreach}

        ({if $article->getDatePublished()}{$article->getDatePublished()|date_format:'%Y'}{elseif $issue->getDatePublished()}{$issue->getDatePublished()|date_format:'%Y'}{else}{$issue->getYear()|escape}{/if}).
        {$article->getLocalizedTitle()}.
        <em>{$journal->getLocalizedTitle()}{if $issue}, {$issue->getVolume()|escape}</em>{if $issue->getNumber()}({$issue->getNumber()|escape}){/if}{else}</em>{/if}{if $article->getPages()}, {$article->getPages()}{/if}{if $article->getArticleNumber()}, article {$article->getArticleNumber()}{/if}.
    {if $galleyId}
	{url|assign:"articleUrl2" page="article" op="view" path=$articleId|to_array:$galleyId}
{else}
	{url|assign:"articleUrl2" page="article" op="view" path=$articleId}
{/if}
    <!--{$articleUrl2}-->
    {if $article->getPubId('doi')}<span class="citation_doi">doi:</span><a href="http://dx.doi.org/{$article->getPubId('doi')|escape}">http://dx.doi.org/{$article->getPubId('doi')}</a>{else}{translate key="plugins.citationFormats.apa.retrieved" retrievedDate=$smarty.now|date_format:$dateFormatLong url=$articleUrl}{/if}
</div>
