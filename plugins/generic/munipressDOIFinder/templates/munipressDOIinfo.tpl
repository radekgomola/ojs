{**
 * @file plugins/generic/munipressDOIFinder/index.php
 *
 * Copyright (c) 2008 Munipress
 *
 *
 *}
{include file="common/header.tpl"}
    
    <body>        
            {url|assign:reportGeneratorUrl router=$smarty.const.ROUTE_COMPONENT component="editor.munipressDOIHandler"}
            {load_url_in_div id="reportGeneratorContainer" url="$reportGeneratorUrl"}
    </body>
</html>
    