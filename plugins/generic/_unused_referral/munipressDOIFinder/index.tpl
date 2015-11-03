{**
 * @file plugins/generic/munipressDOIFinder/index.php
 *
 * Copyright (c) 2008 Munipress
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
</head>
    
    <body>        
            {url|assign:test router=$smarty.const.ROUTE_COMPONENT component="editor.MunipressHandler" op="test" params=$additionalArgs escape=false}

            {$test}

            {load_url_in_div id="sidebarContainer" url=$test}
            {*{$output}*}
    </body>
</html>
    