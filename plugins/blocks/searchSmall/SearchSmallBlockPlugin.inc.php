<?php

/**
 * @file plugins/blocks/searchSmall/SearchSmallBlockPlugin.inc.php
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SearchSmallBlockPlugin
 * @ingroup plugins_blocks_small
 *
 * @brief Class for search block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class SearchSmallBlockPlugin extends BlockPlugin {
	/**
	 * Install default settings on system install.
	 * @return string
	 */
	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Install default settings on journal creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.searchSmall.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.searchSmall.description');
	}

	/**
	 * Get the contents for this block.
	 * @param $templateMgr object
	 * @return string
	 */
	function getContents(&$templateMgr) {
		$templateMgr->assign('articleSearchByOptions', array(
			'query' => 'search.allFields',
			'authors' => 'search.author',
			'title' => 'article.title',
			'abstract' => 'search.abstract'
		));
		return parent::getContents($templateMgr);
	}
}

?>
