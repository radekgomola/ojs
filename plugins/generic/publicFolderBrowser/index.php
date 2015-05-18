<?php

/**
 * @defgroup plugins_generic_publicfolderbrowser
 */
 
/**
 * @file plugins/generic/publicFolderBrowser/index.php
 *
 * Copyright (c) 2008 Mahmoud Saghaei
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING..
 *
 * @ingroup plugins_generic_publicFolderBrowser
 * @brief Enables browsing of the journal public folder
 *
 */

require_once('PublicFolderBrowserPlugin.inc.php');

return new PublicFolderBrowserPlugin();

?>
