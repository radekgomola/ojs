{**
 * plugins/citationFormats/cbe/citation.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article reading tools -- Capture Citation CBE format
 *
 *}
<div id="citation">
{assign var=authors value=$article->getAuthors()}
{assign var=authorCount value=$authors|@count}
{foreach from=$authors item=author name=authors key=i}
	{assign var=firstName value=$author->getFirstName()}
        {if $journal->getSetting('allowMedailonCitations')}
                    <a href="{url page="about" op="bioAuthor" path=$author->getId()}">{$author->getLastName()|escape}, {$firstName|escape|truncate:1:"":true}.</a>{if $i==$authorCount-2}, &amp; {elseif $i<$authorCount-1}, {/if}
        {else}
            {$author->getLastName()|escape}, {$firstName|escape|truncate:1:"":true}.{if $i==$authorCount-2}, &amp; {elseif $i<$authorCount-1}, {/if}
        {/if}
{/foreach}

{if $article->getDatePublished()}{$article->getDatePublished()|date_format:'%Y %b %e'}{elseif $issue->getDatePublished()}{$issue->getDatePublished()|date_format:'%Y %b %e'}{else}{$issue->getYear()|escape}{/if}. {$article->getLocalizedTitle()|strip_unsafe_html}. {$journal->getLocalizedTitle()|escape}. [{translate key="rt.captureCite.online"}] {if $issue}{$issue->getVolume()|escape}:{$issue->getNumber()|escape}{/if}
</div>
