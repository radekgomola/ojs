{**
 * templates/article/header.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article View -- Header component.
 *}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">
<head>
	<title>{$article->getLocalizedTitle()|strip_tags|escape} | {$article->getFirstAuthor(true)|strip_tags|escape} | {$currentJournal->getLocalizedTitle()|strip_tags|escape}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$defaultCharset|escape}" />
	<meta name="description" content="{$article->getLocalizedTitle()|strip_tags|escape}" />
        {$metaCustomHeaders}
	{if $article->getLocalizedSubject()}
		<meta name="keywords" content="{$article->getLocalizedSubject()|escape}" />
	{/if}

	{if $displayFavicon}<link rel="icon" href="{$faviconDir}/{$displayFavicon.uploadName|escape:"url"}" type="{$displayFavicon.mimeType|escape}" />{/if}

	{include file="article/dublincore.tpl"}
	{include file="article/googlescholar.tpl"}
	{call_hook name="Templates::Article::Header::Metadata"}

	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/pkp.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/common.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/common.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/compiled.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/articleView.css" type="text/css" />
	{if $journalRt && $journalRt->getEnabled()}
		<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/rtEmbedded.css" type="text/css" />
	{/if}

	{call_hook|assign:"leftSidebarCode" name="Templates::Common::LeftSidebar"}
	{call_hook|assign:"rightSidebarCode" name="Templates::Common::RightSidebar"}
	{if $leftSidebarCode || $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/sidebar.css" type="text/css" />{/if}
	<!--{if $leftSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/leftSidebar.css" type="text/css" />{/if}-->
	{if $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/rightSidebar.css" type="text/css" />{/if}
	  {if $currentJournal}
  	{if !$currentJournal->getSetting('useMuniStyle') && $leftSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/leftSidebar.css" type="text/css" />{/if}
  	{if !$currentJournal->getSetting('useMuniStyle') && $leftSidebarCode && $rightSidebarCode}<link rel="stylesheet" href="{$baseUrl}/styles/bothSidebars.css" type="text/css" />{/if}
  {/if}
	<!--použitelné pouze pro muni press-->
  {foreach from=$stylesheets item=cssUrl}
    
    {if $currentJournal} 
       {if $currentJournal->getSetting('useMuniStyle')}
          {if $cssUrl|strstr:"sitestyle.css"}
            <link rel="stylesheet" href="{$cssUrl}" type="text/css" />
          {/if}
       {else}
        {if $cssUrl|strstr:"sitestyle.css"}
        {else}
		      <link rel="stylesheet" href="{$cssUrl}" type="text/css" />
        {/if}
      {/if}
    {else}
      {if $cssUrl|strstr:"sitestyle.css"}
        <link rel="stylesheet" href="{$cssUrl}" type="text/css" />
      {/if}
    {/if}

	{/foreach}

	<!-- Base Jquery -->
	{if $allowCDN}<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">{literal}
		// Provide a local fallback if the CDN cannot be reached
		if (typeof google == 'undefined') {
			document.write(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
			document.write(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/plugins/jqueryUi.min.js' type='text/javascript'%3E%3C/script%3E"));
		} else {
			google.load("jquery", "{/literal}{$smarty.const.CDN_JQUERY_VERSION}{literal}");
			google.load("jqueryui", "{/literal}{$smarty.const.CDN_JQUERY_UI_VERSION}{literal}");
		}
	{/literal}</script>
	{else}
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/lib/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="{$baseUrl}/lib/pkp/js/lib/jquery/plugins/jqueryUi.min.js"></script>
	{/if}

	<!-- Compiled scripts -->
	{if $useMinifiedJavaScript}
		<script type="text/javascript" src="{$baseUrl}/js/pkp.min.js"></script>
	{else}
		{include file="common/minifiedScripts.tpl"}
	{/if}
        <script type="text/javascript">
            {literal}
                function detectIE() {
                    var ua = window.navigator.userAgent;
                    var msie = ua.indexOf('MSIE ');
                    var trident = ua.indexOf('Trident/');

                    if (msie > 0) {
                        // IE 10 or older => return version number
                        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
                    }

                    if (trident > 0) {
                        // IE 11 (or newer) => return version number
                        var rv = ua.indexOf('rv:');
                        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
                    }

                    // other browser
                    return false;
                } 
                function vypisKdyzIE(IE10,IE11,vypis){
                    if(detectIE()==10){
                        document.write(IE10);
                        document.write(vypis);
                    }
                    if(detectIE()==11){
                        document.write(IE11);
                        document.write(vypis);
                    }
                }
            {/literal}
        </script>
	{$additionalHeadData}
</head>
<body id="pkp-{$pageTitle|replace:'.':'-'}">

<div id="container">
{translate|assign:"help" key="languages.help"}
    <span id="{if $help == "cestina"}help_cz{else}help_en{/if}"></span>
<div id="header">
<div id="headerTitle">
    <h1>
    <a href="{url page="index"}" class="header_link" style="text-decoration:none; outline:none;">

{if $displayPageHeaderLogo && is_array($displayPageHeaderLogo)}
<div class="header_logo">
	<img src="{$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}" width="{$displayPageHeaderLogo.width|escape}" height="{$displayPageHeaderLogo.height|escape}" {if $displayPageHeaderLogoAltText != ''}alt="{$displayPageHeaderLogoAltText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} />
</div>
{/if}
{if $displayPageHeaderTitle && is_array($displayPageHeaderTitle)}
	<img src="{$publicFilesDir}/{$displayPageHeaderTitle.uploadName|escape:"url"}" style="display: inline" width="{$displayPageHeaderTitle.width|escape}" height="{$displayPageHeaderTitle.height|escape}" {if $displayPageHeaderTitleAltText != ''}alt="{$displayPageHeaderTitleAltText|escape}"{else}alt="{translate key="common.pageHeader.altText"}"{/if} />
{elseif $displayPageHeaderTitle}
	{$displayPageHeaderTitle}
{elseif $alternatePageHeader}
	{$alternatePageHeader}
{elseif $siteTitle}
	{$siteTitle}
{else}
	{$siteTitle}
{/if}
    </h1>
{*<h1>
{if $displayPageHeaderLogo && is_array($displayPageHeaderLogo)}
	<img src="{$publicFilesDir}/{$displayPageHeaderLogo.uploadName|escape:"url"}" width="{$displayPageHeaderLogo.width|escape}" height="{$displayPageHeaderLogo.height|escape}" {if $displayPageHeaderLogoAltText != ''}alt="{$displayPageHeaderLogoAltText|escape}"{else}alt="{translate key="common.pageHeaderLogo.altText"}"{/if} />
{/if}
{if $displayPageHeaderTitle && is_array($displayPageHeaderTitle)}
	<img src="{$publicFilesDir}/{$displayPageHeaderTitle.uploadName|escape:"url"}" width="{$displayPageHeaderTitle.width|escape}" height="{$displayPageHeaderTitle.height|escape}" {if $displayPageHeaderTitleAltText != ''}alt="{$displayPageHeaderTitleAltText|escape}"{else}alt="{translate key="common.pageHeader.altText"}"{/if} />
{elseif $displayPageHeaderTitle}
	{$displayPageHeaderTitle}
{elseif $alternatePageHeader}
	{$alternatePageHeader}
{elseif $siteTitle}
	{$siteTitle}
{else}
	{$applicationName}
{/if}*}
</h1>
</div>
</div>

<div id="body">

{if $currentJournal}
  	<div id="sidebar"> 
      {if $rightSidebarCode}   
  			<div id="rightSidebar">
  				{$rightSidebarCode}
  			</div>
      {/if}
      {if !$currentJournal->getSetting('useMuniStyle') && $leftSidebarCode}
        <div id="leftSidebar">
    			{$leftSidebarCode}
    		</div>
      {/if}
  	</div>
{else}
  {if $rightSidebarCode}
    <div id="sidebar">    
			<div id="rightSidebar">
				{$rightSidebarCode}
			</div>
  	</div>
  {/if}
{/if}



<div id="main">

{include file="common/navbar2.tpl"}

<div id="breadcrumb">
	<a href="{url page="index"}" target="_parent">{translate key="navigation.home"}</a> &gt;
	{if $issue}<a href="{url page="issue" op="view" path=$issue->getBestIssueId($currentJournal)}" target="_parent">{$issue->getIssueIdentification(false,true)|escape}</a> &gt;{/if}
	<a href="{url page="article" op="view" path=$articleId|to_array:$galleyId}" class="current" target="_parent">
  {if $article->getLocalizedTitle()|strlen >= 70}
    {$article->getLocalizedTitle()|truncate:70|strip_tags|escape}
  {else}
    {$article->getLocalizedTitle()|strip_tags|escape}
  {/if}
  </a>
</div>

<div id="content">

