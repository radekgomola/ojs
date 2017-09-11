{**
 * templates/submission/reviewForm/reviewFormResponse.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Review Form to enter responses/comments/answers.
 *
 *}
{strip}
{if $editorPreview}
	{include file="submission/comment/header.tpl"}
{else}
	{translate|assign:"pageTitleTranslated" key="submission.reviewFormResponse"}
	{assign var="pageCrumbTitle" value="submission.reviewFormResponse"}
	{include file="common/header.tpl"}
	{include file="common/formErrors.tpl"}
{/if}
{/strip}

{assign var=disabled value=0}
{if $isLocked || $editorPreview}
	{assign var=disabled value=1}
{/if}
<div id="reviewFormResponse">
<h3>{$reviewForm->getLocalizedTitle()}</h3>
<p>{$reviewForm->getLocalizedDescription()}</p>
	{foreach from=$reviewFormElements name=reviewFormElements key=elementId item=reviewFormElement}
            <div class="reviewFormElement-{$reviewFormElement->getId()}}">
		<h4>{$reviewFormElement->getLocalizedQuestion()} {if $reviewFormElement->getRequired() == 1}*{/if}</h4>
		<p>
			{if $reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_SMALL_TEXT_FIELD}
				{$reviewFormResponses[$elementId]|escape}
			{elseif $reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_TEXT_FIELD}
				{$reviewFormResponses[$elementId]|escape}
			{elseif $reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_TEXTAREA}
				{$reviewFormResponses[$elementId]|escape}
			{elseif $reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_CHECKBOXES}
				{assign var=possibleResponses value=$reviewFormElement->getLocalizedPossibleResponses()}
				{foreach name=responses from=$possibleResponses key=responseId item=responseItem}
					<input {if $disabled}disabled="disabled" {/if}type="checkbox" name="reviewFormResponses[{$elementId}][]" id="reviewFormResponses-{$elementId}-{$smarty.foreach.responses.iteration}" value="{$smarty.foreach.responses.iteration}"{if !empty($reviewFormResponses[$elementId]) && in_array($smarty.foreach.responses.iteration, $reviewFormResponses[$elementId])} checked="checked"{/if} /><label for="reviewFormResponses-{$elementId}-{$smarty.foreach.responses.iteration}">{$responseItem.content}</label><br/>
				{/foreach}
			{elseif $reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_RADIO_BUTTONS}
				{assign var=possibleResponses value=$reviewFormElement->getLocalizedPossibleResponses()}
				{foreach name=responses from=$possibleResponses key=responseId item=responseItem}
					<input {if $disabled}disabled="disabled" {/if}type="radio"  name="reviewFormResponses[{$elementId}]" id="reviewFormResponses-{$elementId}-{$smarty.foreach.responses.iteration}" value="{$smarty.foreach.responses.iteration}"{if $smarty.foreach.responses.iteration == $reviewFormResponses[$elementId]} checked="checked"{/if}/><label for="reviewFormResponses-{$elementId}-{$smarty.foreach.responses.iteration}">{$responseItem.content}</label><br/>
				{/foreach}
			{elseif $reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_DROP_DOWN_BOX}
				<select {if $disabled}disabled="disabled" {/if}name="reviewFormResponses[{$elementId}]" id="reviewFormResponses-{$elementId}" size="1" class="selectMenu">
					<option label="" value=""></option>
					{assign var=possibleResponses value=$reviewFormElement->getLocalizedPossibleResponses()}
					{foreach name=responses from=$possibleResponses key=responseId item=responseItem}
						<option label="{$responseItem.content}" value="{$smarty.foreach.responses.iteration}"{if $smarty.foreach.responses.iteration == $reviewFormResponses[$elementId]} selected="selected"{/if}>{$responseItem.content}</option>
					{/foreach}
				</select>
			{/if}
		</p>
            </div>
	{/foreach}

	<br />

	{if $editorPreview}
		<p><input type="button" value="{translate key="common.close"}" class="button defaultButton" onclick="window.close()" /></p>
	{/if}

	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
{include file="submission/comment/footer.tpl"}

