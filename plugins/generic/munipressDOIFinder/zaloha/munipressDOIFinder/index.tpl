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
	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/common.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/compiled.css" type="text/css" />
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
        .munipress_indicator{
            margin-top: 20px;
        }
        .statistika_doi{
            margin-left: 20px;
        }
        .copyright_uvt{
            text-align: left; margin: 10px auto 20px; border: 1px solid black; width: 630px; height: 50px;
        }
        .copyright_uvt_text{
            width: 530px; float: left; margin-top: 3px; text-align: center;
        }
        .copyright_uvt_img{
            float: right; height: 50px; margin-right: 15px;
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
            <div class="copyright_uvt">
                <div class="copyright_uvt_text">{translate key="plugins.generic.munipressdoifinder.copyrightmessage"}</div>
                <a href="{translate key='footer.uvt.link'}" target="_blank">
                    <img src="https://journals.muni.cz/images/uvt_logo.png" alt="{translate key='footer.uvt'}" class="copyright_uvt_img"/>
                </a>
            </div>
            {url|assign:munipressFinderUrl op="munipressfinder" articleId=$articleId escape=false}

            {load_url_in_div id="munipressFinderContainer" url=$munipressFinderUrl munipressLoadMessage = "plugins.generic.munipressdoifinder.loadingmessage"}
            
    </body>
</html>
    