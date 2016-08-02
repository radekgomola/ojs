<?php

/**
 * @file plugins/citationFormats/iso690/Iso690CitationPlugin.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class Iso690CitationPlugin
 * @ingroup plugins_citationFormats_iso690
 *
 * @brief ISO 690 citation format plugin
 */

import('classes.plugins.CitationPlugin');

class Iso690CitationPlugin extends CitationPlugin {
	function register($category, $path) {
		$success = parent::register($category, $path);
		$this->addLocaleData();
		return $success;
	}        
        /**
	 * Display an HTML-formatted citation. We register String::strtoupper modifier
	 * in order to convert author names to uppercase.
	 * @param $article Article
	 * @param $issue Issue
	 * @param $journal Journal
	 */
	function displayCitation(&$article, &$issue, &$journal) {
		$templateMgr =& TemplateManager::getManager();
		$templateMgr->register_modifier('mb_upper', array('String', 'strtoupper'));
		$templateMgr->register_modifier('iso690_date_format_with_day', array($this, 'iso690DateFormatWithDay'));
		return parent::displayCitation($article, $issue, $journal);
	}
        
         /**
	 * Return an HTML-formatted citation. Default implementation displays
	 * an HTML-based citation using the citation.tpl template in the plugin
	 * path.
	 * @param $article object
	 * @param $issue object
	 */
	function fetchCitation(&$article, &$issue, &$journal, &$articleUrl) {
		$templateMgr =& TemplateManager::getManager();
		$templateMgr->register_modifier('mb_upper', array('String', 'strtoupper'));
		$templateMgr->register_modifier('iso690_date_format_with_day', array($this, 'iso690DateFormatWithDay'));
		return parent::fetchCitation($article, $issue, $journal, $articleUrl);
	}

        
        /**
	 * @function iso690DateFormatWithDay Format date taking in consideration ISO 690 abbreviations
	 * @param $string string
	 * @return string
	 */
	function iso690DateFormatWithDay($string) {
		if (is_numeric($string)) {
			// it is a numeric string, we handle it as timestamp
			$timestamp = (int)$string;
		} else {
			$timestamp = strtotime($string);
		}
		$format = "%Y-%m-%d";
                
		return String::strtolower(strftime($format, $timestamp));
	}

	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'Iso690CitationPlugin';
	}

	function getDisplayName() {
		return __('plugins.citationFormats.iso690.displayName');
	}

	function getCitationFormatName() {
		return __('plugins.citationFormats.iso690.citationFormatName');
	}

	function getDescription() {
		return __('plugins.citationFormats.iso690.description');
	}
}

?>
