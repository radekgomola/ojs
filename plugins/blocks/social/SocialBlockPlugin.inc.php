<?php

/**
 * @file plugins/blocks/user/SocialBlockPlugin.inc.php
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class SocialBlockPlugin
 * @ingroup plugins_blocks_social
 *
 * @brief Class for Social block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class SocialBlockPlugin extends BlockPlugin {

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
		return __('plugins.block.social.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.social.description');
	}

	function getContents(&$templateMgr) {
		$journal =& Request::getJournal();
		if (!$journal) return '';

		$templateMgr->assign('socFacebook', $journal->getLocalizedSetting('socialFacebook'));
		$templateMgr->assign('socTwitter', $journal->getLocalizedSetting('socialTwitter'));
		$templateMgr->assign('socGplus', $journal->getLocalizedSetting('socialGplus'));
		return parent::getContents($templateMgr);
	}
}

?>
