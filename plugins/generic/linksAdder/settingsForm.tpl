{**
 * plugins/generic/staticPages/settingsForm.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Form for Static Pages plugin settings.
 *
 *}
{assign var="pageTitle" value="plugins.generic.linkAdder.displayName"}
{include file="common/header.tpl"}

{translate key="plugins.generic.linkAdder.settingInstructions"}
<br />
{translate key="plugins.generic.linkAdder.viewInstructions" pagesPath=$pagesPath|replace:"REPLACEME":"%PATH%"}

<br />
<br />

<form method="post" action="{plugin_url path="edit"}">

{include file="common/formErrors.tpl"}

<table width="100%" class="listing">
	<tr><td colspan="4" class="headseparator">&nbsp;</td></tr>
	<tr class="heading" valign="bottom">
                <td width="20%">{translate key="plugins.generic.linkAdder.name"}</td>
		<td width="20%">{translate key="plugins.generic.linkAdder.umisteni"}</td>
		<td width="25%">{translate key="plugins.generic.linkAdder.link"}</td>
		<td width="35%">{translate key="common.action"}</td>
	</tr>
	<tr><td colspan="4" class="headseparator">&nbsp;</td></tr>

        {$addedLinks->getCount()}
{iterate from=addedLinks item=addedLink}
	<tr valign="top">
                <td width="20%" class="label">{$addedLink->getAddedLinkName()|strip_tags|truncate:40:"..."}</td>
		<td width="20%" class="label">{$addedLink->getUmisteni()|escape}</td>
		<td width="35%" class="value" >{$addedLink->getAddedLinkLink()|strip_tags|truncate:50:"..."}</td>
		<td width="25%"><a href="{plugin_url path="edit"|to_array:$addedLink->getId()}" class="action">{translate key="common.edit"}</a> | <a href="{plugin_url path="delete"|to_array:$addedLink->getId()}" class="action">{translate key="common.delete"}</a></td>
	</tr>
	<tr>
		<td colspan="4" class="{if $addedLinks->eof()}end{/if}separator">&nbsp;</td>
	</tr>
{/iterate}
{if $addedLinks->wasEmpty()}
	<tr>
		<td colspan="4" class="nodata">{translate key="plugins.generic.addedLink.noneExist"}</td>
	</tr>
	<tr>
		<td colspan="4" class="endseparator">&nbsp;</td>
	</tr>
{/if}

</table>
<a class="action" href={plugin_url path="add"}>{translate key="plugins.generic.addedLink.addNewLink"}</a>

<p><input type="button" value="{translate key="common.done"}" class="button defaultButton" onclick="document.location.href='{url page="manager" op="plugins" escape=false}'" /></p>

</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

{include file="common/footer.tpl"}
