{**
 * templates/manager/plugins/plugins.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * List available import/export plugins.
 *}
{strip}
{include file="common/header.tpl"}
{/strip}

{if $mainPage}
	<p>{translate key="manager.plugins.description"}</p>
	<ul id="plugins" class="plain">
		{foreach from=$plugins item=plugin}  
			{if $plugin->getCategory() != $category}
				{assign var=category value=$plugin->getCategory()}
        {if $isSiteAdmin || $category=="blocks" || $category=="generic"}
				  <li>&#187; <a href="{url path=$category|escape}">{translate key="plugins.categories.$category"}</a></li>
        {/if}
			{/if}
		{/foreach}

		{if $isSiteAdmin && !$preventManagerPluginManagement}
			<li>&nbsp;</li>
			<li><b><a href="{url op="managePlugins" path=install}">{translate key="manager.plugins.install"}</a></b></li>
		{/if}
	</ul>

	{if $isSiteAdmin && !$preventManagerPluginManagement}
		<ul id="pluginManagement">
			<li><b><a href="{url op="managePlugins" path=install}">{translate key="manager.plugins.install"}</a></b></li>
		</ul>
	{/if}
{else}
	{foreach from=$plugins item=plugin}
		{if $plugin->getCategory() != $category}
			{assign var=category value=$plugin->getCategory()}
			<div id="{$category|escape}">
			<p>{translate key="plugins.categories.$category.description"}</p>
			</div>
		{/if}
	{/foreach}

	<ul id="plugins">
		{foreach from=$plugins item=plugin}
			{if !$plugin->getHideManagement()}
			{if $plugin->getCategory() != $category}
				{assign var=category value=$plugin->getCategory()}
				<div id="{$category|escape}">
				<h3>{translate key="plugins.categories.$category"}</h3>
				<p>{translate key="plugins.categories.$category.description"}</p>
				</div>
			{/if}
      {assign var=name value=$plugin->getName()}

      {if $isSiteAdmin || $category=="blocks" || $name=="webfeedplugin" || $name=="announcementfeedplugin" || $name=="customblockmanagerplugin" || $name=="staticpagesplugin" || $name=="PublicFolderBrowserPlugin" || $name=="pdfjsviewerplugin" || $name=="stopforumspamplugin" || $name=="MunipressDOIFinderPlugin" || $name == "ReviewPdfGeneratorPlugin"}
			<li><h4>{$plugin->getDisplayName()|escape}</h4>
			<p>
			{$plugin->getDescription()}<br />
			{assign var=managementVerbs value=$plugin->getManagementVerbs()}
			{if $managementVerbs && $plugin->isSitePlugin() && !$isSiteAdmin}
				<em>{translate key="manager.plugins.sitePlugin"}</em>
			{elseif $managementVerbs}
				{foreach from=$managementVerbs item=verb}
					<a class="action" href="{url op="plugin" path=$category|to_array:$plugin->getName():$verb[0]}">{$verb[1]|escape}</a>&nbsp;
				{/foreach}
			{/if}

			{if $plugin->getCurrentVersion() && !$preventManagerPluginManagement && $isSiteAdmin}
				{assign var=pluginInstallName value=$plugin->getPluginPath()|basename}
				<a class="action" href="{url op="managePlugins" path="upgrade"|to_array:$category:$pluginInstallName}">{translate key="manager.plugins.upgrade"}</a>&nbsp;
				<a class="action" href="{url op="managePlugins" path="delete"|to_array:$category:$pluginInstallName}">{translate key="manager.plugins.delete"}</a>&nbsp;
			{/if}
			</p></li>
      {/if}
			{/if}
		{/foreach}
	</ul>
{/if}

{include file="common/footer.tpl"}

