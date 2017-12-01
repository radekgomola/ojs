{**
 * templates/layoutEditor/active.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Show layout editor's active submissions.
 *
 *}
<div id="submissions">
<table class="listing" width="100%">
	<tr><td colspan="7" class="headseparator">&nbsp;</td></tr>
	<tr class="heading" valign="bottom">
		<td width="5%">{sort_heading key="common.id" sort="id"}</td>
		<td width="5%"><span class="disabled">{translate key="submission.date.mmdd"}</span><br />{sort_heading key="common.assigned" sort="assignDate"}</td>
		<td width="5%">{sort_heading key="submissions.sec" sort="section"}</td>
		<td width="30%">{sort_heading key="article.authors" sort="authors"}</td>
		<td width="30%">{sort_heading key="article.title" sort="title"}</td>
		<td width="15%" align="right">{sort_heading key="common.status" sort="status"}</td>
		<td width="10%">&nbsp;</td>
	</tr>
	<tr><td colspan="7" class="headseparator">&nbsp;</td></tr>

{iterate from=submissions item=submission}
	{assign var="articleId" value=$submission->getId()}
	{assign var="layoutSignoff" value=$submission->getSignoff('SIGNOFF_LAYOUT')}

	<tr valign="top">
		<td>{$articleId|escape}</td>
		<td>{$layoutSignoff->getDateNotified()|date_format:$dateFormatTrunc}</td>
		<td>{$submission->getSectionAbbrev()|escape}</td>
		<td>{$submission->getAuthorString(true)|truncate:40:"..."|escape}</td>
		<td><a href="{url op="submission" path=$articleId}" class="action">{$submission->getLocalizedTitle()|strip_tags|truncate:60:"..."}</a></td>
		<td align="right">
			{if not $layoutSignoff->getDateCompleted()}
				{translate key="submissions.initial"}
			{else}
				{translate key="submissions.proofread"}
                                {assign var="authorProofreadSignoff" value=$submission->getSignoff('SIGNOFF_PROOFREADING_AUTHOR')}
                                {assign var="proofreaderProofreadSignoff" value=$submission->getSignoff('SIGNOFF_PROOFREADING_PROOFREADER')}
                                {assign var="layoutEditorProofreadSignoff" value=$submission->getSignoff('SIGNOFF_PROOFREADING_LAYOUT')}
                                
                                {if $authorProofreadSignoff->getDateNotified() && not $authorProofreadSignoff->getDateCompleted() && not $proofreaderProofreadSignoff->getDateNotified() && not $layoutEditorProofreadSignoff->getDateNotified()}
                                    {translate key="submissions.proofread.autor"}
                                {elseif $authorProofreadSignoff->getDateCompleted() && $proofreaderProofreadSignoff->getDateNotified() && not $layoutEditorProofreadSignoff->getDateNotified()}
                                    {translate key="submissions.proofread.korektor"}
                                {elseif $proofreaderProofreadSignoff->getDateCompleted() && $layoutEditorProofreadSignoff->getDateNotified() && not $layoutEditorProofreadSignoff->getDateCompleted()}   
                                    {translate key="submissions.proofread.typograf"}
                                {else}
                                    <br />-
                                {/if}
			{/if}
		</td>
		{*{if $layoutSignoff->getDateCompleted()}
			{assign var="proofreaderProofreadSignoff" value=$submission->getSignoff('SIGNOFF_PROOFREADING_PROOFREADER')}
			{url|assign:"url" op="completeProofreader" articleId=$submission->getId()}
			<td>
				{translate|assign:"confirmMessage" key="common.confirmComplete"}
				{if $proofreaderProofreadSignoff->getDateCompleted()}{assign var="disabled" value="disabled"}{/if}
				{icon name="mail" onclick="return confirm('$confirmMessage')" url=$url disabled=$disabled}
			</td>
		{/if}*}
                {if $layoutSignoff->getDateCompleted()}
			{assign var="layoutEditorProofreadSignoff" value=$submission->getSignoff('SIGNOFF_PROOFREADING_LAYOUT')}
			{url|assign:"url" op="completeProofreader" articleId=$submission->getId()}
			<td>
				{translate|assign:"confirmMessage" key="common.confirmComplete"}

                                {if not $layoutEditorProofreadSignoff->getDateNotified()}{assign var="disabled" value="disabled"}{else}{assign var=disabled value=""}{/if}
				{if $layoutEditorProofreadSignoff->getDateCompleted()}{assign var="disabled" value="disabled"}{/if}
                                {if $disabled == "disabled"}
                                    {icon name="mail" url=$url disabled=$disabled}
                                {else}
                                    {icon name="mail" onclick="return confirm('$confirmMessage')" url=$url}<a href="{$url}" onclick="return confirm('$confirmMessage')">{translate key="submission.complete.complete"}</a>
                                {/if}
			</td>
		{/if}
	</tr>
	<tr>
                <td colspan="7" class="{if $submissions->eof()}end{/if}separator">&nbsp;</td>
	</tr>
{/iterate}
{if $submissions->wasEmpty()}
	<tr>
		<td colspan="7" class="nodata">{translate key="submissions.noSubmissions"}</td>
	</tr>
	<tr>
		<td colspan="7" class="endseparator">&nbsp;</td>
	</tr>
{else}
	<tr>
		<td colspan="4" align="left">{page_info iterator=$submissions}</td>
		<td colspan="3" align="right">{page_links anchor="submissions" name="submissions" iterator=$submissions sort=$sort sortDirection=$sortDirection}</td>
	</tr>
{/if}
</table>
</div>

