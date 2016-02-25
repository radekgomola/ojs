{**
 * plugins/generic/webFeed/templates/block.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Feed plugin navigation sidebar.
 *
 *}
<div class="block" id="sidebarWebFeed">
{*	<span class="blockTitle">{translate key="journal.currentIssue"}</span>*}
        <span class="blockTitle">{translate key="journal.newsletter"}</span>
	<a href="{url page="gateway" op="plugin" path="WebFeedGatewayPlugin"|to_array:"atom"}" target="_blank">
	<img src="{$baseUrl}/plugins/generic/webFeed/templates/images/atom.png" alt="{translate key="plugins.generic.webfeed.atom.altText"}" border="0" class="link_img" style="margin-left:5px; width: 42px;" /></a>
	{*<br/>*}
	<a href="{url page="gateway" op="plugin" path="WebFeedGatewayPlugin"|to_array:"rss2"}" target="_blank">
	<img src="{$baseUrl}/plugins/generic/webFeed/templates/images/rss.png" alt="{translate key="plugins.generic.webfeed.rss2.altText"}" border="0" class="link_img" style="margin-left: 15px;width: 42px;"/></a>
	{*<br/>
	<a href="{url page="gateway" op="plugin" path="WebFeedGatewayPlugin"|to_array:"rss"}">
	<img src="{$baseUrl}/plugins/generic/webFeed/templates/images/rss10_logo.gif" alt="{translate key="plugins.generic.webfeed.rss1.altText"}" border="0" /></a>*}
</div>
