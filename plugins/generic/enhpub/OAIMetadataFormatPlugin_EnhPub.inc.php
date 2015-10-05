<?php

/**
 * @file plugins/oaiMetadata/dc/OAIMetadataFormatPlugin_DC.inc.php
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormatPlugin_EnhPub
 * @ingroup oai_format
 * @see OAI
 *
 * @brief enhpub-rdf-xml metadata format plugin for OAI.
 * @see http://wiki.surffoundation.nl/display/vp/Discovery+and+Transport
 */

import('classes.plugins.OAIMetadataFormatPlugin');

class OAIMetadataFormatPlugin_EnhPub extends OAIMetadataFormatPlugin {
	/**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'OAIMetadataFormatPlugin_EnhPub';
	}

	function getDisplayName() {
		return __('plugins.oaiMetadata.enhpub.displayName');
	}

	function getDescription() {
		return __('plugins.oaiMetadata.enhpub.description');
	}

	function getFormatClass() {
		return 'OAIMetadataFormat_EnhPub';
	}

	function getMetadataPrefix() {
	  return 'oai_ore';
		//return 'enhpub-rdf-xml';
	}

	function getSchema() {
		return 'http://www.w3.org/2000/07/rdf.xsd';
		//return 'http://purl.org/REP/enhpub-rdf-xml.xsd';
	}

	function getNamespace() {
		return 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
		//return 'http://purl.org/REP/enhpub-rdf-xml/';
	}
}

?>
