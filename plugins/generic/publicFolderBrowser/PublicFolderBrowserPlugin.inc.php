<?php

/**
 * @file PublicFolderBrowserPlugin.inc.php
 *
 * Copyright (c) 2008 Mahmoud Saghaei
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING..
 *
 * @class PublicFolderBrowserPlugin
 * @ingroup plugins_generic_publicfolderbrowser
 *
 * @brief Enables browsing of the journal public folder
 */

import('classes.plugins.GenericPlugin');

class PublicFolderBrowserPlugin extends GenericPlugin {

	function getName() {
		return 'PublicFolderBrowserPlugin';
	}

	function getDisplayName() {
		return Locale::translate('plugins.generic.publicfolderbrowser.displayName');
	}

	function getDescription() {
		return Locale::translate('plugins.generic.publicfolderbrowser.description');
	}   

	/**
	 * Called as a plugin is registered to the registry
	 * @param @category String Name of category plugin was registered to
	 * @return boolean True iff plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register('Templates::Manager::Index::ManagementPages',
					array(&$this, 'callback'));
				HookRegistry::register('LoadHandler', array(&$this, 'handleRequest'));
			}
			$this->addLocaleData();
			return true;
		}
		return false;
	}


	function callback($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
		$url = Request::url(null, 'manager', 'publicFolder');
		$output = "<li>&#187; <a href='{$url}'>" . Locale::translate("plugins.generic.publicfolderbrowser.linklabel") . "</a></li>";
		return false;
	}

	function handleRequest($hookName, $args) {
		$page =& $args[0];
		$op =& $args[1];
		$sourceFile =& $args[2];

		// If the request is for the log analyzer itself, handle it.
		$op_arr = array('publicFolder', 'publicFileUpload', 'publicFileMakeDir', 'publicFileDelete');
		if ($page === 'manager' && in_array($op, $op_arr)) {
			$this->import('PublicFolderHandler');
			Registry::set('plugin', $this);
			define('HANDLER_CLASS', 'PublicFolderHandler');
			return true;
		}

		return false;
	}

	/**
	 * Determine whether or not this plugin is enabled.
	 */
	function getEnabled() {
		$journal = &Request::getJournal();
		if (!$journal) return false;
		return $this->getSetting($journal->getJournalId(), 'enabled');
	}

	/**
	 * Set the enabled/disabled state of this plugin
	 */
	function setEnabled($enabled) {
		$journal = &Request::getJournal();
		if ($journal) {
			$this->updateSetting($journal->getJournalId(), 'enabled', $enabled ? true : false);
			return true;
		}
		return false;
	}

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array(
				'disable',
				Locale::translate('manager.plugins.disable')
			);
		} else {
			$verbs[] = array(
				'enable',
				Locale::translate('manager.plugins.enable')
			);
		}
		return $verbs;
	}

	/**
	 * Perform management functions
	 */
	function manage($verb, $args) {

		switch ($verb) {
			case 'enable':
				$this->setEnabled(true);
				break;
			case 'disable':
				$this->setEnabled(false);
				break;
		}
	}

}

?>
