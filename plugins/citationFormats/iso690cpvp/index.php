<?php

/**
 * @defgroup plugins_citationFormats_iso690cpvp
 */
 
/**
 * @file plugins/citationFormats/iso690cpvp/index.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_citationFormats_iso690cpvp
 * @brief Wrapper for ISO690cpvp citation plugin.
 *
 */

require_once('Iso690cpvpCitationPlugin.inc.php');

return new Iso690cpvpCitationPlugin();

?>
