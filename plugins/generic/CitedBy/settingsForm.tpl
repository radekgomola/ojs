{**
 * settingsForm.tpl
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Cited By plugin settings
 *
 * $Id$
 *}
{strip}
{assign var="pageTitle" value="plugins.generic.CitedBy.manager.CitedBySettings"}
{include file="common/header.tpl"}
{/strip}
<div id="CitedBySettings">
<div id="description">{translate key="plugins.generic.CitedBy.manager.settings.description"}</div>

<div class="separator"></div>

<br />

<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

<table width="100%" class="data">
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="citedbyusername" required="true" key="plugins.generic.CitedBy.manager.settings.username"}</td>
		<td width="80%" class="value"><input type="text" name="cb_user" id="cb_user" value="{$cb_user|escape}" size="15" maxlength="35" class="textField" /></td>
	</tr>
	<tr valign="top">
		<td width="20%" class="label">{fieldLabel name="cutedbypassword" required="true" key="plugins.generic.CitedBy.manager.settings.password"}</td>
		<td width="80%" class="value"><input type="password" name="cb_pass" id="cb_pass" value="{$cb_pass|escape}" size="15" maxlength="35" class="textField" /></td>
	</tr>
</table>

<br/>

<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/><input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
{include file="common/footer.tpl"}
