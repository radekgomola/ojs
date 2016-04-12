<?php

/**
 * @file plugins/generic/linksAdder/LinksAdderSettingsForm.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.staticPages
 * @class StaticPagesSettingsForm
 *
 * Form for journal managers to modify Static Page content and title
 *
 */

import('lib.pkp.classes.form.Form');

class LinksAdderSettingsForm extends Form {
	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/** $var $errors string */
	var $errors;

	/**
	 * Constructor
	 * @param $journalId int
	 */
	function LinksAdderSettingsForm(&$plugin, $journalId) {

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		$this->journalId = $journalId;
		$this->plugin =& $plugin;
                
		$this->addCheck(new FormValidatorPost($this));
	}


	/**
	 * Initialize form data from current group group.
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;

		$linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');

		$rangeInfo =& Handler::getRangeInfo('addedLinks');
		$addedLinks = $linksAdderDao->getAddedLinksByJournalId($journalId);
		$this->setData('addedLinks', $addedLinks);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('links'));
	}

	/**
	 * Save settings/changes
	 */
	function execute() {
		$plugin =& $this->plugin;
		$journalId = $this->journalId;
	}

}
?>
