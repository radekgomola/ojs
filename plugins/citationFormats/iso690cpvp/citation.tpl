{**
 * plugins/citationFormats/iso690cpvp/citation.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article reading tools -- Capture Citation ISO 690 format
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
{php}
  $this->assign("interpunkce", array('?','.','!',';'));
{/php}
{foreach from=$authors item=author name=authors key=i}
	{assign var=firstName value=$author->getFirstName()}
        {assign var=lastName value=$author->getLastName()}
        {if $journal->getSetting('allowMedailonCitations')}
                    {if $i<$authorCount-1 || $i==0}<a href="{url page="about" op="bioAuthor" path=$author->getId()}">{$lastName|escape|mb_upper}, {$firstName|escape}</a>{elseif $i==$authorCount-1}<a href="{url page="about" op="bioAuthor" path=$author->getId()}">{$firstName|escape} {$lastName|escape|mb_upper}</a>{/if}{if $i==$authorCount-2} {translate key="common.and"} {elseif $i<$authorCount-2}, {elseif $i==$authorCount-1}.{/if}
        {else}
            {if $i<$authorCount-1 || $i==0}{$lastName|escape|mb_upper}, {$firstName|escape}{elseif $i==$authorCount-1}{$firstName|escape} {$lastName|escape|mb_upper}{/if}{if $i==$authorCount-2} {translate key="common.and"} {elseif $i<$authorCount-2}, {elseif $i==$authorCount-1}.{/if}
        {/if}
{/foreach}
{assign var=title value=$article->getLocalizedTitle()}
{assign var=posledniSymbol value=$title|substr:-1}
{$article->getLocalizedTitle()}{if not in_array($posledniSymbol, $interpunkce)}.{/if} <em>{$journal->getLocalizedTitle()}</em>. [{translate key="rt.captureCite.online"}]. {if $issue->getYear()}{$issue->getYear()|escape}{/if}{if $issue}{if $issue->getNumber()}, {translate key="citation.number"} {$issue->getNumber()|escape}{/if}{/if}{if $article->getPages()}, {translate key="citation.pages"} {$article->getPages()|escape}.{/if} {translate key="plugins.citationFormats.iso690.retrieved" retrievedDate=$smarty.now|iso690_date_format_with_day url=$articleUrl}
</div>
