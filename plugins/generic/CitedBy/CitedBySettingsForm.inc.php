<?php

/**
 * @file CitedBySettingsForm.inc.php
 *
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CitedBySettingsForm
 * @ingroup plugins_generic_citedby
 *
 * @brief Form for journal managers to modify Cited By plugin settings
 */

// $Id$


import('lib.pkp.classes.form.Form');

class CitedBySettingsForm extends Form {

	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $journalId int
	 */
	function CitedBySettingsForm(&$plugin, $journalId) {
		$this->journalId = $journalId;
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		$this->addCheck(new FormValidator($this, 'cb_user', 'required', 'plugins.generic.CitedBy.manager.settings.usernameRequired'));

		$this->addCheck(new FormValidator($this, 'cb_pass', 'required', 'plugins.generic.CitedBy.manager.settings.passwordRequired'));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;

		$this->_data = array(
			'cb_user' => $plugin->getSetting($journalId, 'cb_user'),
			'cb_pass' => $plugin->getSetting($journalId, 'cb_pass')
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('cb_user', 'cb_user'));
		$this->readUserVars(array('cb_pass', 'cb_pass'));
	}

	/**
	 * Save settings. 
	 */
	function execute() {
		$plugin =& $this->plugin;
		$journalId = $this->journalId;

		$plugin->updateSetting($journalId, 'cb_user', trim($this->getData('cb_user'), "\"\';"), 'string');
		$plugin->updateSetting($journalId, 'cb_pass', trim($this->getData('cb_pass'), "\"\';"), 'string');
	}
}

?>
