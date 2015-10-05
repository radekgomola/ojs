<?php

/**
 * @file plugins/generic/rem/RemPlugin.inc.php
 *
 * @class RemPlugin
 * @ingroup plugins_generic_rem
 *
 * @brief Rem plugin
 */


import('lib.pkp.classes.plugins.GenericPlugin');

class RemPlugin extends GenericPlugin {
	
  function register($category, $path) {
    if (parent::register($category, $path)) {
      if ($this->getEnabled()) {
        
        HookRegistry::register('TemplateManager::display', array(&$this, 'displayTemplate'));
				HookRegistry::register('LoadHandler', array(&$this, 'handleRequest'));

      }
      // $this->addLocaleData();
      return true;
    }
    return false;
  }


/* =============== plugin housekeeping functions =============== */


  /**
   * Get the display name of this plugin
   * @return string
   */
  function getDisplayName() {
    return __('plugins.generic.rem.displayName');
  }


  /**
   * Get the description of this plugin
   * @return string
   */
  function getDescription() {
    return __('plugins.generic.rem.description');
  }


  /**
   * Display verbs for the management interface.
   */
  function getManagementVerbs() {
    $verbs = array();
    if ($this->getEnabled()) {
      $verbs[] = array(
        'disable',
        __('manager.plugins.disable')
      );
/*
			$verbs[] = array(
        'settings',
        Locale::translate('plugins.generic.rem.settings')
      );
*/
		} else {
      $verbs[] = array(
        'enable',
        __('manager.plugins.enable')
      );
    }
    return $verbs;
  }


  /*
   * Execute a management verb on this plugin
   * @param $verb string
   * @param $args array
   * @param $message string Location for the plugin to put a result msg
   * @return boolean
   */
  function manage($verb, $args, &$message) {
    $returner = true;
    $journal =& Request::getJournal();
    $this->addLocaleData();

    switch ($verb) {
      case 'enable':
        $this->updateSetting($journal->getId(), 'enabled', true);
        $returner = false;
        break;
      case 'disable':
        $this->updateSetting($journal->getId(), 'enabled', false);
        $returner = false;
        break;
    }
    return $returner;
  }


  /**
   * TemplateManager hook to replace regular templates
   */
  function displayTemplate($hookName, $params) {
		if ($params[1] == 'rt/metadata.tpl')	{
			$params[1] = $this->getTemplatePath() . 'metadata.tpl';
		}
		return false;
	}

	function handleRequest($hookName, $params) {
		$page =& $params[0];
		$op =& $params[1];
		$handler =& $params[2];

		if ($page == 'rem') {
			switch ($op) {
			case 'resourceMap':
				define('HANDLER_CLASS', 'RemHandler');
				$handler = $this->getPluginPath() . '/' . 'RemHandler.inc.php';
				$this->import('ResourceMap');
				$this->import('RemHandler');
				return true;
			}
		}
		return false;
	}

}

?>
