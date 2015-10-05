{**
 * @file plugins/generic/munipressDOIFinder/index.php
 *
 * Copyright (c) 2015 Munipress
 *
 *
 *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs-CZ" xml:lang="cs-CZ">
<head>
   
        
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Vyhledaní DOI pomocí "parscit-to-doi"</title>
    {literal}
    <style>
        .nove_doi{
            background-color: #b9ffc3;
        }
        .jine_doi{
            background-color: #ffb9b9;
        }
        .stejne_doi{
            background-color: #b9ddff;
        }
    </style>
    {/literal}
    
    <!-- Base Jquery -->
    {if $allowCDN}<script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <script type="text/javascript">{literal}
                    <!--
                    // Provide a local fallback if the CDN cannot be reached
                    if (typeof google == 'undefined') {
                            document.write(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
                            document.write(unescape("%3Cscript src='{/literal}{$baseUrl}{literal}/lib/pkp/js/lib/jquery/plugins/jqueryUi.min.js' type='text/javascript'%3E%3C/script%3E"));
                    } else {
                            google.load("jquery", "{/literal}{$smarty.const.CDN_JQUERY_VERSION}{literal}");
                            google.load("jqueryui", "{/literal}{$smarty.const.CDN_JQUERY_UI_VERSION}{literal}");
                    }
                    // -->
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
</head>
<body>

    