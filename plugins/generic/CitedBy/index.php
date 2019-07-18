<?php

/**
 * @defgroup plugins_generic_citedby
 */
 
/**
 * @file plugins/generic/citedby/index.php
 *
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_citedby
 * @brief Wrapper for Cited By plugin.
 *
 */

// $Id$


require_once('CitedByPlugin.inc.php');

return new CitedByPlugin();

?>
