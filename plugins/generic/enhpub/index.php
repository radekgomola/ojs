<?php

/**
 * @file plugins/oaiMetadata/enhpub/index.php
 *
 * Copyright (c) 2003-2011 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_oaiMetadata
 * @brief Wrapper for the OAI enhpub-rdf-xml format plugin.
 *
 */

require_once('OAIMetadataFormatPlugin_EnhPub.inc.php');
require_once('OAIMetadataFormat_EnhPub.inc.php');

return new OAIMetadataFormatPlugin_EnhPub();

?>
