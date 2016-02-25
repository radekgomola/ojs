{**
 * templates/common/footer.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Common site footer.
 *
 *}
{strip}
{if $pageFooter==''}
	{if $currentJournal && $currentJournal->getSetting('onlineIssn')}
		{assign var=e_issn value=$currentJournal->getSetting('onlineIssn')}
        {/if}
	{if $currentJournal && $currentJournal->getSetting('printIssn')}
		{assign var=p_issn value=$currentJournal->getSetting('printIssn')}
	{/if}
	{if $e_issn || $p_issn}
		{translate|assign:"issnText" key="journal.issn"}
                {translate|assign:"issnTextOnline" key="journal.issn.online"}
                {translate|assign:"issnTextPrinted" key="journal.issn.print"}
                {if !$e_issn}
                    {assign var=pageFooter value="$issnText: $p_issn ($issnTextPrinted)"}
                {elseif !$p_issn}
                    {assign var=pageFooter value="$issnText: $e_issn ($issnTextOnline)"}
                {else}
                    {assign var=pageFooter value="$issnText: $e_issn ($issnTextOnline)<br /> $issnText: $p_issn ($issnTextPrinted)"}
                {/if}
	{/if}
{/if}
{include file="core:common/footer.tpl"}
{/strip}
