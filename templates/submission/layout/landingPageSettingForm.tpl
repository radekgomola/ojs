{**
 * templates/submission/layout/skipLandingPageForm.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Form to add/edit a galley.
 *}
{strip}
{assign var="pageTitle" value="submission.skipLandingPage"}
{include file="common/header.tpl"}
{/strip}
<div id="landingPageForm">
{translate key="submission.skipLandingPage.description"}<br /><br />
<form method="post" action="{url op="setSkipLandingPageSettings" path=$articleId}" enctype="multipart/form-data">
{include file="common/formErrors.tpl"}
<input type="checkbox" name="skipLandingPageNew" value="1" {if $skipLandingPage}checked{/if}/> {translate key="submission.layout.skipLandingPage"}<br />
<h4>{translate key="submission.layout.skipLandingPage.vypisSazebnic"}</h4>
{foreach from=$allGalleys item=oneGalley} 
   <input type="radio" name="skipGalleyIdNew" value="{$oneGalley->getId()|escape}" {if $oneGalley->getId()==$skipGalleyId}checked{/if}/>
    {$oneGalley->getGalleyLabel()}<br />
    
{/foreach}<br />
<input type="submit" value="{translate key="common.save"}" class="button defaultButton" /> <input type="button" value="{translate key="common.cancel"}" class="button" onclick="document.location.href='{url op="submissionEditing" path=$articleId escape=false}'" />
</form>
<br />
</div>
{include file="common/footer.tpl"}

