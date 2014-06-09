{**
 * templates/common/footer.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
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
                {translate|assign:"issnTextPrinted" key="journal.issn.print"}
		{assign var=pageFooter value="$issnText: $e_issn <br /> $issnTextPrinted: $p_issn"}
	{/if}
{/if}
{include file="core:common/footer.tpl"}
{/strip}

