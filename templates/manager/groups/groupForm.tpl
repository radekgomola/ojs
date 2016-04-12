{**
* templates/manager/groups/groupForm.tpl
*
* Copyright (c) 2013-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Group form under journal management.
*
*}
{strip}
    {assign var="pageId" value="manager.groups.groupForm"}
    {assign var="pageCrumbTitle" value=$pageTitle}
    {include file="common/header.tpl"}
{/strip}
<div id="groupFormDiv">
    {if $group}
        <ul class="menu">
            <li class="current"><a href="{url op="editGroup" path=$group->getId()}">{translate key="manager.groups.editTitle"}</a></li>
            <li><a href="{url op="groupMembership" path=$group->getId()}">{translate key="manager.groups.membership"}</a></li>
        </ul>
    {/if}

    <br/>

    <form id="groupForm" method="post" action="{url op="updateGroup"}">
        {if $group}
            <input type="hidden" name="groupId" value="{$group->getId()}"/>
        {/if}

        {include file="common/formErrors.tpl"}
        <table class="data" width="100%">
            {if count($formLocales) > 1}
                <tr valign="top">
                    <td width="20%" class="label">{fieldLabel name="formLocale" key="form.formLanguage"}</td>
                    <td width="80%" class="value">
                        {if $group}{url|assign:"groupFormUrl" op="editGroup" path=$group->getId() escape=false}
                        {else}{url|assign:"groupFormUrl" op="createGroup" escape=false}
                        {/if}
                        {form_language_chooser form="groupForm" url=$groupFormUrl}
                        <span class="instruct">{translate key="form.formLanguage.description"}</span>
                    </td>
                </tr>
            {/if}
            <tr valign="top">
                <td width="20%" class="label">{fieldLabel name="title" required="true" key="manager.groups.title"}</td>
                <td width="80%" class="value"><input type="text" name="title[{$formLocale|escape}]" value="{$title[$formLocale]|escape}" size="35" maxlength="80" id="title" class="textField" /></td>
            </tr>
            
            <tr valign="top">
                <td width="20%" class="label">{fieldLabel name="description" key="manager.groups.description"}</td>
                <td width="80%" class="value">
                    <textarea name="groupDescription[{$formLocale|escape}]" id="groupDescription" rows="12" cols="60" class="textArea">{$groupDescription[$formLocale]|escape}</textarea>
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="label"><input type="radio" name="groupSetupTopDown" id="groupSetupTopDown-0" value="0"{if not $groupSetupTopDown} checked="checked"{/if} /> {fieldLabel name="groupSetupTopDown-0" key="manager.setup.useTopDescription"}</td>
            </tr>
            <tr valign="top" class="">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="label"><input type="radio" name="groupSetupTopDown" id="groupSetupTopDown-1" value="1"{if $groupSetupTopDown} checked="checked"{/if} /> {fieldLabel name="groupSetupTopDown-1" key="manager.setup.useDownDescription"}</td>
            </tr>
            <tr>
                <td class="separator-border" width="100%" colspan="2">&nbsp;</td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="value">
                    <input type="checkbox" name="publishEmail" value="1" {if $publishEmail}checked="checked" {/if} id="publishEmail" />&nbsp;
                    {fieldLabel name="publishEmail" key="manager.groups.publishEmails"}
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="value">
                    <input type="checkbox" name="publishEmailList" value="1" {if $publishEmailList}checked="checked" {/if} id="publishEmailList" />&nbsp;
                    {fieldLabel name="publishEmailList" key="manager.groups.publishEmailList"}
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="value">
                    <input type="checkbox" name="publishUrlList" value="1" {if $publishUrlList}checked="checked" {/if} id="publishUrlList" />&nbsp;
                    {fieldLabel name="publishUrlList" key="manager.groups.publishUrlList"}
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="value">
                    <input type="checkbox" name="allowMedailon" value="1" {if $allowMedailon}checked="checked" {/if} id="allowMedailon" />&nbsp;
                    {fieldLabel name="allowMedailon" key="manager.groups.allowMedailon"}
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="value">
                    <input type="checkbox" name="opacnyTvarJmena" value="1" {if $opacnyTvarJmena}checked="checked" {/if} id="opacnyTvarJmena" />&nbsp;
                    {fieldLabel name="opacnyTvarJmena" key="manager.groups.opacnyTvarJmena"}
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">&nbsp;</td>
                <td width="80%" class="value">
                    <input type="checkbox" name="fullProfile" value="1" {if $fullProfile}checked="checked" {/if} id="fullProfile" />&nbsp;
                    {fieldLabel name="fullProfile" key="manager.groups.fullProfile"}
                </td>
            </tr>
            <tr valign="top">
                <td width="20%" class="label">{translate key="common.type"}</td>
                <td width="80%" class="value">
                    {foreach from=$groupContextOptions item=groupContextOptionKey key=groupContextOptionValue}
                        <input type="radio" name="context" value="{$groupContextOptionValue|escape}" {if $context == $groupContextOptionValue}checked="checked" {/if} id="context-{$groupContextOptionValue|escape}" />&nbsp;
                        {fieldLabel name="context-"|concat:$groupContextOptionValue key=$groupContextOptionKey}<br />
                    {/foreach}
                </td>
            </tr>
        </table>

        <p><input type="submit" value="{translate key="common.save"}" class="button defaultButton" /> <input type="button" value="{translate key="common.cancel"}" class="button" onclick="document.location.href = '{url op="groups" escape=false}'" /></p>
    </form>

    <p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</div>
{include file="common/footer.tpl"}

