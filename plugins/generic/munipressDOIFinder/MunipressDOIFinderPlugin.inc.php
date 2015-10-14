<?php

/**
 * @file MunipressDOIFinderPlugin.inc.php
 *
 * Copyright (c) 20015 Munipress
 *
 * @class MunipressDOIFinderPlugin
 * @ingroup plugins_generic_munipressdoifinder
 *
 */

import('classes.plugins.GenericPlugin');

class MunipressDOIFinderPlugin extends GenericPlugin {

	function getName() {
		return 'MunipressDOIFinderPlugin';
	}

	function getDisplayName() {
		return __('plugins.generic.munipressdoifinder.displayName');
	}

	function getDescription() {
		return __('plugins.generic.munipressdoifinder.description');
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
				HookRegistry::register('Templates::Editor::Index::DOIFinder',
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

                $requestedArgs = Request::getRequestedArgs();

                $url = Request::url(null, 'editor', 'munipressDOI',array($requestedArgs[0]));
                $url_help = Request::url(null, 'editor', 'munipressDOIinfo');

                $output = "<a href=\"javascript:openWindowDoi('{$url}',1000,800)\" class='tlacitko_doi'>" . __('plugins.generic.munipressdoifinder.linklabel') . "</a>"
                         ."&nbsp;&nbsp;<a href=\"https://journals.muni.cz/tutorials/doi_finder.htm\" class='tlacitko_doi' target=\"_blank\">" . __('plugins.generic.munipressdoifinder.linklabel.info') . "</a>";
		return false;
	}
        
        function handleRequest($hookName, $args) {
		$page =& $args[0];
		$op =& $args[1];
		$sourceFile =& $args[2];

		// If the request is for the log analyzer itself, handle it.
		$op_arr = array('munipressDOI','munipressDOIinfo','munipressfinder');
		if ($page === 'editor' && in_array($op, $op_arr)) {
			$this->import('MunipressDOIHandler');
			Registry::set('plugin', $this);
			define('HANDLER_CLASS', 'MunipressDOIHandler');
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
				__('manager.plugins.disable')
			);
		} else {
			$verbs[] = array(
				'enable',
				__('manager.plugins.enable')
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
