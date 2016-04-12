<?php

/**
 * @file LinksAdderEditForm.inc.php
 *
 * @package plugins.generic.linksAdder
 * @class LinksAdderEditForm
 *
 * Form for journal managers to view and modify static pages
 *
 */

import('lib.pkp.classes.form.Form');

class LinksAdderEditForm extends Form {
	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/** @var $staticPageId **/
	var $addedLinkId;

	/** $var $errors string */
	var $errors;

	/**
	 * Constructor
	 * @param $journalId int
	 */
	function LinksAdderEditForm(&$plugin, $journalId, $addedLinkId = null) {

		parent::Form($plugin->getTemplatePath() . 'editLinksAdderForm.tpl');

		$this->journalId = $journalId;
		$this->plugin =& $plugin;
		$this->addedLinkId = isset($addedLinkId)? (int) $addedLinkId: null;

                $this->addCheck(new FormValidatorLocale($this, 'name', 'required', 'plugins.generic.linkAdder.settings.nameRequired'));
                $this->addCheck(new FormValidator($this, 'umisteni', 'required', 'plugins.generic.linkAdder.settings.umisteniRequired'));
                $this->addCheck(new FormValidatorLocale($this, 'link', 'required', 'plugins.generic.linkAdder.settings.urlRequired'));
                $this->addCheck(new FormValidatorUrl($this, 'link', 'required', 'plugins.generic.linkAdder.settings.urlInvalid'));
                $this->addCheck(new FormValidatorPost($this));

	}

	/**
	 * Initialize form data from current group group.
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;

		if (isset($this->addedLinkId)) {
			$linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');
			$addedLink =& $linksAdderDao->getAddedLink($this->addedLinkId);

			if ($addedLink != null) {
				$this->_data = array(
					'addedLinkId' => $addedLink->getId(),
					'umisteni' => $addedLink->getUmisteni(),
                                        'target' => $addedLink->getTarget(),
					'name' => $addedLink->getName(null),
					'link' => $addedLink->getLink(null)
				);
			} else {
				$this->addedLinkId = null;
			}
		}
	}

	

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('addedLinkId', 'umisteni', 'target', 'name', 'link'));
	}

	/**
	 * Get the names of localized fields
	 * @return array
	 */
	function getLocaleFieldNames() {
		return array('name', 'link');
	}

	/**
	 * Save link into DB
	 */
	function save() {
		$plugin =& $this->plugin;
		$journalId = $this->journalId;

		$plugin->import('AddedLink');
		$linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');
		if (isset($this->addedLinkId)) {
			$addedLink =& $linksAdderDao->getAddedLink($this->addedLinkId);
		}

		if (!isset($addedLink)) {
			$addedLink = new AddedLink();
		}

		$addedLink->setJournalId($journalId);
		$addedLink->setUmisteni($this->getData('umisteni'));
                $addedLink->setTarget($this->getData('target'));

		$addedLink->setName($this->getData('name'), null);		// Localized
		$addedLink->setLink($this->getData('link'), null);	// Localized

		if (isset($this->addedLinkId)) {
			$linksAdderDao->updateAddedLink($addedLink);
		} else {
			$linksAdderDao->insertAddedLink($addedLink);
		}
	}

	function display() {
		$templateMgr =& TemplateManager::getManager();

		parent::display();
	}

}
?>
