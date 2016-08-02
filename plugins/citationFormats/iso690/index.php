<?php

/**
 * @defgroup plugins_citationFormats_iso690
 */
 
/**
 * @file plugins/citationFormats/iso690/index.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_citationFormats_iso690
 * @brief Wrapper for ISO690 citation plugin.
 *
 */

require_once('Iso690CitationPlugin.inc.php');

return new Iso690CitationPlugin();

?>
