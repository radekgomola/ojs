<?php

/**
 * @file MailchimpLinkPlugin.inc.php

 * @class MailchimpLinkPlugin
 * @ingroup plugins_generic_mailchimplink
 *
 */

import('classes.plugins.GenericPlugin');

class MailchimpLinkPlugin extends GenericPlugin {

	function getName() {
		return 'MailchimpLinkPlugin';
	}

	function getDisplayName() {
		return __('plugins.generic.mailchimplink.displayName');
	}

	function getDescription() {
		return __('plugins.generic.mailchimplink.description');
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
				HookRegistry::register('Templates::Manager::Index::ManagementPages2',
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
		$url = "http://www.mailchimp.com";
		$output = "<li><a href='{$url}' target='_blank'>" . __('plugins.generic.mailchimplink.linklabel') . "</a></li>";
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
