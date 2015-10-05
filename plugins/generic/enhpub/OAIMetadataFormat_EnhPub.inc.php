<?php

/**
 * @defgroup oai_format
 */

/**
 * @file plugins/oaiMetadata/dc/OAIMetadataFormat_EnhPub.inc.php
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormat_EnhPub
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- Enhanced Publications.
 */

import('plugins.generic.rem.ResourceMap');

class OAIMetadataFormat_EnhPub extends OAIMetadataFormat {
	/**
	 * @see OAIMetadataFormat#toXML
	 * in lib\pkp\classes\oai\OAIStruct.inc.php
	 */
	function toXml(&$record, $format = null) {
		$response = null;

		$article =& $record->getData('article');
		$journal =& $record->getData('journal');
		$issue =& $record->getData('issue');

		Locale::requireComponents(array(LOCALE_COMPONENT_APPLICATION_COMMON));

    $rem = new ResourceMap($journal,$issue,$article);
    $response = $rem->getXml();
		return $response;  // = 1 record
	}
}

?>
