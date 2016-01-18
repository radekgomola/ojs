<?php

/**
 * @file plugins/blocks/cz_en_blok_small/Cz_en_blok_smallBlockPlugin.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class Cz_en_blokBlockPlugin
 * @ingroup plugins_blocks_cz_en_blok_small
 *
 * @brief Class for language selector block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

class Cz_en_blok_smallBlockPlugin extends BlockPlugin {
	/**
	 * Determine whether the plugin is enabled. Overrides parent so that
	 * the plugin will be displayed during install.
	 */
	function getEnabled() {
		if (!Config::getVar('general', 'installed')) return true;
		return parent::getEnabled();
	}

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
	 * Get the block context. Overrides parent so that the plugin will be
	 * displayed during install.
	 * @return int
	 */
	function getBlockContext() {
		if (!Config::getVar('general', 'installed')) return BLOCK_CONTEXT_RIGHT_SIDEBAR;
		return parent::getBlockContext();
	}

	/**
	 * Determine the plugin sequence. Overrides parent so that
	 * the plugin will be displayed during install.
	 */
	function getSeq() {
		if (!Config::getVar('general', 'installed')) return 2;
		return parent::getSeq();
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.cz_en_blok_small.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.cz_en_blok_small.description');
	}

	/**
	 * Get the HTML contents for this block.
	 */
	function getContents(&$templateMgr) {
		$templateMgr->assign('isPostRequest', Request::isPost());
                $locale = AppLocale::getLocale();
		if (!defined('SESSION_DISABLE_INIT')) {
                    $journal =& Request::getJournal();
			if (isset($journal)) {
				$locales =& $journal->getSupportedLocaleNames();

			} else {
				$locales['cs_CZ']= "Czech";
                                $locales['en_US']= "English";
			}
		} else {
			$locales =& AppLocale::getAllLocales();
			$templateMgr->assign('languageToggleNoUser', true);
		}
                
                if (isset($locales) && count($locales) > 1) {
                    $templateMgr->assign('enableCz_en_blok_small', true);
                }
                
                $templateMgr->assign('jazyk', $locale);
		return parent::getContents($templateMgr);
	}
}

?>
