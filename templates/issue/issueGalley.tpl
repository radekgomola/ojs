{**
 * templates/issue/issueGalley.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue galley view for PDF files.
 *}
{include file="issue/header.tpl"}


{url|assign:"pdfUrl" op="viewFile" path=$issueId|to_array:$galley->getBestGalleyId($currentJournal)}
{translate|assign:"noPluginText" key='article.pdf.pluginMissing'}
<script type="text/javascript"><!--{literal}
	$(document).ready(function(){
		if ($.browser.webkit) { // PDFObject does not correctly work with safari's built-in PDF viewer
			var embedCode = "<object id='pdfObject' type='application/pdf' data='{/literal}{$pdfUrl|escape:'javascript'}{literal}' width='99%' height='99%'><div id='pluginMissing'>{/literal}{$noPluginText|escape:'javascript'}{literal}</div></object>";
			$("#inlinePdf").html(embedCode);
			if($("#pluginMissing").is(":hidden")) {
				$('#fullscreenShow').show();
				$("#inlinePdf").resizable({ containment: 'parent', handles: 'se' });
			} else { // Chrome Mac hides the embed object, obscuring the text.  Reinsert.
				$("#inlinePdf").html('{/literal}{$noPluginText|escape:"javascript"}{literal}');
			}
		} else {
			var success = new PDFObject({ url: "{/literal}{$pdfUrl|escape:'javascript'}{literal}" }).embed("inlinePdf");
			if (success) {
				// PDF was embedded; enable fullscreen mode and the resizable widget
				$('#fullscreenShow').show();
				$("#inlinePdfResizer").resizable({ containment: 'parent', handles: 'se' });
			}
		}
	});
{/literal}
// -->
</script>
{if (!$subscriptionRequired || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || $subscribedUser || $subscribedDomain)}
        {assign var=hasAccess value=1}
{else}
        {assign var=hasAccess value=0}
{/if}
<p>
{if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}

            <script type="text/javascript">
                
                vypisKdyzIE('<h4>{translate key=IE10.problem}</h4>','<h4>{translate key=IE11.problem}</h4>','<p>{translate key=IE.problem.redirect} <a target="_blank" href="{$pdfUrl}" class="file" >{translate key=click.here}</a></p><p>{translate key=IE.problem.solution}</p>')
            </script>
            {if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
                    {if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || !$galley->isPdfGalley()}
                            <img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
                    {else}
                            <img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
                    {/if}
            {/if}                                                 
{/if}
</p>
<div id="inlinePdfResizer">
    
	<div id="inlinePdf" class="ui-widget-content">
		{translate key="article.pdf.pluginMissing"}
	</div>
</div>
<p>
	{* The target="_parent" is for the sake of iphones, which present scroll problems otherwise. *}
	<a class="action" target="_parent" href="{url op="download" path=$issueId|to_array:$galley->getBestGalleyId($currentJournal)}">{translate key="article.pdf.download"}</a>
	<a class="action" href="#" id="fullscreenShow">{translate key="common.fullscreen"}</a>
	<a class="action" href="#" id="fullscreenHide">{translate key="common.fullscreenOff"}</a>
</p>
{include file="common/footer.tpl"}
