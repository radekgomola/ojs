<?php

/**
 * @file plugins/generic/linksAdder/AddedLink.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.addedLink
 * @class AddedLink
 *
 */

class AddedLink extends DataObject {
	//
	// Get/set methods
	//

	/**
	 * Get journal id
	 * @return string
	 */
	function getJournalId(){
		return $this->getData('journalId');
	}

	/**
	 * Set journal Id
	 * @param $journalId int
	 */
	function setJournalId($journalId) {
		return $this->setData('journalId', $journalId);
	}


	/**
	 * Set link name
	 * @param string string
	 * @param locale
	 */
	function setName($name, $locale) {
		return $this->setData('name', $name, $locale);
	}

	/**
	 * Get link name
	 * @param locale
	 * @return string
	 */
	function getName($locale) {
		return $this->getData('name', $locale);
	}

	/**
	 * Get Localized link name
	 * @return string
	 */
	function getAddedLinkName() {
		return $this->getLocalizedData('name');
	}

	/**
	 * Set link link
	 * @param $link string
	 * @param locale
	 */
	function setLink($link, $locale) {
		return $this->setData('link', $link, $locale);
	}

	/**
	 * Get content
	 * @param locale
	 * @return string
	 */
	function getLink($locale) {
		return $this->getData('link', $locale);
	}

	/**
	 * Get "localized" link
	 * @return string
	 */
	function getAddedLinkLink() {
		return $this->getLocalizedData('link');
	}

	/**
	 * Get link umisteni string
	 * @return string
	 */
	function getUmisteni() {
		return $this->getData('umisteni');
	}

	 /**
	  * Set link umisteni string
	  * @param $umisteni string
	  */
	function setUmisteni($umisteni) {
		return $this->setData('umisteni', $umisteni);
	}

        /**
	 * Get link umisteni string
	 * @return string
	 */
	function getTarget() {
		return $this->getData('target');
	}

	 /**
	  * Set link umisteni string
	  * @param $umisteni string
	  */
	function setTarget($target) {
		return $this->setData('target', $target);
	}
        
	/**
	 * Get ID of page.
	 * @return int
	 */
	function getAddedLinkId() {
		if (Config::getVar('debug', 'deprecation_warnings')) trigger_error('Deprecated function.');
		return $this->getId();
	}

	/**
	 * Set ID of page.
	 * @param $staticPageId int
	 */
	function setAddedLinkId($addedLinkId) {
		if (Config::getVar('debug', 'deprecation_warnings')) trigger_error('Deprecated function.');
		return $this->setId($addedLinkId);
	}
}

?>
