<?php

/**
 * @file ReviewPdfGeneratorPlugin.inc.php
 *
 * Copyright (c) 20015 Munipress
 *
 * @class ReviewPdfGeneratorPlugin
 * @ingroup plugins_generic_reviewpdfgenerator
 *
 */

import('classes.plugins.GenericPlugin');

class ReviewPdfGeneratorPlugin extends GenericPlugin {

	function getName() {
		return 'ReviewPdfGeneratorPlugin';
	}

	function getDisplayName() {
		return __('plugins.generic.reviewpdfgenerator.displayName');
	}

	function getDescription() {
		return __('plugins.generic.reviewpdfgenerator.description');
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
				HookRegistry::register('Templates::Manager::ReviewForms::PDFGenerator',
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

                $url = Request::url(null, 'manager', 'reviewPdfGenerator', array($params['id']));

                $output = "<a href='{$url}' class='action' >" . __('plugins.generic.reviewpdfgenerator.linklabel') . "</a>&nbsp;|";
		return false;
	}
        
        function handleRequest($hookName, $args) {
		$page =& $args[0];
		$op =& $args[1];
		$sourceFile =& $args[2];

		// If the request is for the log analyzer itself, handle it.
		$op_arr = array('reviewPdfGenerator');
		if ($page === 'manager' && in_array($op, $op_arr)) {
			$this->import('ReviewPdfHandler');
			Registry::set('plugin', $this);
			define('HANDLER_CLASS', 'ReviewPdfHandler');
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
