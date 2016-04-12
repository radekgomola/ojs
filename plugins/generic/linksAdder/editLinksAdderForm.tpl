{**
* plugins/generic/linksAdder/editLinksAdderForm.tpl
*
* Copyright (c) 2013-2016 Simon Fraser University Library
* Copyright (c) 2003-2016 John Willinsky
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Form for editing an Added link
*
*}
{strip}
    {if $addedLinkId}
        {assign var="pageTitle" value="plugins.generic.addedLink.editAddedLink"}
    {else}
        {assign var="pageTitle" value="plugins.generic.addedLink.addAddedLink"}
    {/if}
    {include file="common/header.tpl"}
{/strip}

{translate key="plugins.generic.addedLink.editInstructions"}
<br />
<br />

<form method="post" id="editLinksAdderForm" action="{if $addedLinkId}{plugin_url path="save"|to_array:$addedLinkId}{else}{plugin_url path="save"}{/if}" >
    <input type="hidden" name="edit" value="1" />
    {if $addedLinkId}
        <input type="hidden" name="addedLinkId" value="{$addedLinkId}" />
    {/if}

    {include file="common/formErrors.tpl"}

    <table class="data" width="100%">
        {if count($formLocales) > 1}
            <tr valign="top">
                <td width="20%" class="label">{fieldLabel name="formLocale" key="form.formLanguage"}</td>
                <td width="80%" class="value">
                    {if $addedLinkId}{plugin_url|assign:"addedLinkEditUrl" path="edit"|to_array:$addedLinkId}
                {else}{plugin_url|assign:"addedLinkEditUrl" path="edit"|to_array:$addedLinkId}{/if}
                {form_language_chooser form="editLinksAdderForm" url=$addedLinkEditUrl}
                <span class="instruct">{translate key="form.formLanguage.description"}</span>
            </td>
        </tr>
    {/if}
    <tr>
        <td width="20%" class="label">{fieldLabel required="true" name="umisteni" key="plugins.generic.linksAdder.umisteni"}</td>
        {*<td width="80%" class="value" ><input type="text" name="umisteni" value="{$umisteni|escape}" size="40" id="umisteni" maxlength="50" class="textField" /></td>*}
        <td width="80%" class="value" >
            <select name="umisteni" size="0">
                <option value="">{translate key="plugins.generic.linksAdder.nenastaveno"}</option>
                <option value="lide" {if $umisteni == "lide"} selected="selected"{/if}>{translate key="plugins.generic.linksAdder.umisteni.lide"}</option>
                <option value="pravidla" {if $umisteni == "pravidla"} selected="selected"{/if}>{translate key="plugins.generic.linksAdder.umisteni.pravidla"}</option>
                <option value="prispevky" {if $umisteni == "prispevky"} selected="selected"{/if}>{translate key="plugins.generic.linksAdder.umisteni.prispevky"}</option>
                <option value="jine" {if $umisteni == "jine"} selected="selected"{/if}>{translate key="plugins.generic.linksAdder.umisteni.jine"}</option>
            </select>
        </td>        
    </tr>
    <tr>
        <td width="20%" class="label">{fieldLabel required="true" name="name" key="plugins.generic.linksAdder.titulek"}</td>
        <td width="80%" class="value" ><input type="text" name="name[{$formLocale|escape}]" value="{$name[$formLocale]|escape}" size="40" id="name" maxlength="50" class="textField" /></td>
    </tr>
    <tr>
        <td width="20%" class="label" valign="top">{fieldLabel required="true" name="link" key="plugins.generic.linksAdder.odkaz"}</td>
        <td width="80%" class="value" ><input type="text" name="link[{$formLocale|escape}]" value="{$link[$formLocale]|escape}" size="40" id="link" maxlength="50" class="textField" /></td>
    </tr>
    <tr>
        <td width="20%" class="label" valign="top">{fieldLabel name="target" key="plugins.generic.linksAdder.cil"}</td>
        <td width="80%" class="value" >
            <select name="target" size="0">
                <option value="" >{translate key="plugins.generic.linksAdder.nenastaveno"}</option>
                <option value="_self" {if $target == "_self"} selected="selected"{/if}>{translate key="plugins.generic.linksAdder.target.stejneOkno"}</option>
                <option value="_blank" {if $target == "_blank"} selected="selected"{/if}>{translate key="plugins.generic.linksAdder.target.noveOkno"}</option>
            </select>
        </td>
    </tr>
</table>

<p><input type="submit" value="{translate key="common.save"}" class="button defaultButton" />
    <input type="button" value="{translate key="common.cancel"}" class="button" onclick="document.location.href = '{plugin_url path="settings"}'" /></p>

</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

{include file="common/footer.tpl"}
