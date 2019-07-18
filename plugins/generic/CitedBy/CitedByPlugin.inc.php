<?php

/**
 * @file CitedByPlugin.inc.php
 *
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class CitedByPlugin
 * @ingroup plugins_generic_citedby
 *
 * @brief Cited By plugin class
 */

// $Id$


import('lib.pkp.classes.plugins.GenericPlugin');

class CitedByPlugin extends GenericPlugin {
	/**
	 * Called as a plugin is registered to the registry
	 * @param $category String Name of category plugin was registered to
	 * @return boolean True if plugin initialized successfully; if false,
	 * 	the plugin will not be registered.
	 */
	function register($category, $path) {
		
		$success = parent::register($category, $path);
		if (!Config::getVar('general', 'installed') || defined('RUNNING_UPGRADE')) return true;
		if ($success && $this->getEnabled()) {
			
			// 1. Testing purposes only
			HookRegistry::register('Templates::Common::Footer::PageFooter', array($this, 'insertList'));
			// 2. Insert Cited-By linking to article.tpl
			HookRegistry::register('citedby', array($this, 'insertList'));
			
		}
		return $success;
	}
        function getName() {
		return 'CitedByPlugin';
	}

	function getDisplayName() {
		return __('plugins.generic.citedby.displayName');
	}

	function getDescription() {
		return __('plugins.generic.citedby.description');
	}

	/**
	 * Extend the {url ...} smarty to support this plugin.
	 */
	function smartyPluginUrl($params, &$smarty) {
		$path = array($this->getCategory(), $this->getName());
		if (is_array($params['path'])) {
			$params['path'] = array_merge($path, $params['path']);
		} elseif (!empty($params['path'])) {
			$params['path'] = array_merge($path, array($params['path']));
		} else {
			$params['path'] = $path;
		}

		if (!empty($params['id'])) {
			$params['path'] = array_merge($params['path'], array($params['id']));
			unset($params['id']);
		}
		return $smarty->smartyUrl($params, $smarty);
	}

	/**
	 * Set the page's breadcrumbs, given the plugin's tree of items
	 * to append.
	 * @param $subclass boolean
	 */
	function setBreadcrumbs($isSubclass = false) {
		$templateMgr =& TemplateManager::getManager();
		$pageCrumbs = array(
			array(
				Request::url(null, 'user'),
				'navigation.user'
			),
			array(
				Request::url(null, 'manager'),
				'user.role.manager'
			)
		);
		if ($isSubclass) $pageCrumbs[] = array(
			Request::url(null, 'manager', 'plugins'),
			'manager.plugins'
		);

		$templateMgr->assign('pageHierarchy', $pageCrumbs);
	}

	/**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('plugins.generic.CitedBy.manager.settings'));
		}
		return parent::getManagementVerbs($verbs);
	}
	

	/**
	 * Insert Cited-By linking
	 */
	function insertList($hookName, $params) {
		$smarty =& $params[1];
		$output =& $params[2];
		$templateMgr =& TemplateManager::getManager();
		$currentJournal = $templateMgr->get_template_vars('currentJournal');
		
		$article = $templateMgr->get_template_vars('article');
		
		if (!empty($currentJournal)) {
			
			if (Request::getRequestedPage() == 'article' && $article) {
				
				$journal =& Request::getJournal();
				$journalId = $journal->getId();
				
				$doi = $article->getPubId('doi');
				$user = $this->getSetting($journalId, 'cb_user');
				$pass = $this->getSetting($journalId, 'cb_pass');
				
				$array = $this->GetXML($doi, $user, $pass);
				
				if($array[1]['JOURNAL_TITLE']){
					$this->ShowList($array);
				}
			}
		}
		return false;
	}
	
	/*
 	 * Get XML from CrossRef
 	 * @param $doi string
 	 * @param $user string
	 * @param $pass strng
 	 * @return array
 	 */
	function GetXML($doi, $user, $pass){
		
		$xmlfile = 'http://doi.crossref.org/servlet/getForwardLinks?usr='.$user.'&pwd='.$pass.'&doi='.$doi.'&startDate=2007-01-01&endDate='.date('Y').'-12-31';
		$xmlparser = xml_parser_create();
		
		// open a file and read data
                
		$xmldata = file_get_contents($xmlfile);
		xml_parse_into_struct($xmlparser,$xmldata,$values);
		xml_parser_free($xmlparser);
		
		$j = 0;
		$i = 0;
		$z = 0;
		$filt = array();
		foreach($values as $key => $item){
		    
		    if($item['tag'] == 'FORWARD_LINK' && $item['type'] == 'open') $j = 1;                    		    
		    if($j == 1){
			if($item['tag'] && $item['value']){
			    if($item['tag'] == 'GIVEN_NAME'){
				$filt[$i]['GIVEN_NAME'][$z] = $item['value'];
			    }
			    elseif($item['tag'] == 'SURNAME'){
				$filt[$i]['GIVEN_NAME'][$z] .= ' '.$item['value'];
				$z++;
			    } else {
				$filt[$i][$item['tag']] = $item['value'];
			    }
			}
		    }
		    if($item['tag'] == 'FORWARD_LINK' && $item['type'] == 'open'){
			$j = 1;
			$i++;
		    }
		}
		
		return $filt;
	}
	
	/*
 	 * Generates and shows output
 	 * @param $array array
 	 * @return boolean
 	 */
	function ShowList($array){
		
		print '<p>&nbsp;</p>';
		print '<div id="articleTitle"><h4><b>'.__('plugins.generic.citedby.displayTitle').'</b></h4></div>';
		
		$no = 1;
		foreach($array as $key => $item){
			if($item['ARTICLE_TITLE'] && $item['GIVEN_NAME'] && $item['DOI'] ){
				print '<p>'.$no.'. ';
					print '<b>'.$item['ARTICLE_TITLE'].'</b><br />';
					$i = 0;
					print '<span style="font-size: 0.9em;">';
					foreach($item['GIVEN_NAME'] as $key2 => $name){
					    if($i > 0) print ', ';
					    print $name;
					    $i++;
					}
					print '</span>';
					print '<br />';
					if($item['JOURNAL_TITLE']) print '<b><i>'.$item['JOURNAL_TITLE'].'</i></b>&nbsp;&nbsp;';
					if($item['VOLUME']) print __('plugins.generic.citedby.vol').'&nbsp;'.$item['VOLUME'].',&nbsp;&nbsp;';
					if($item['ISSUE']) print __('plugins.generic.citedby.issue').'&nbsp;'.$item['ISSUE'].',&nbsp;&nbsp;';
					if($item['FIRST_PAGE']) print __('plugins.generic.citedby.firstpage').'&nbsp;'.$item['FIRST_PAGE'].',&nbsp;&nbsp;';
					if($item['YEAR']) print __('plugins.generic.citedby.year').'&nbsp;'.$item['YEAR'].'&nbsp;&nbsp;';
					if($item['DOI']) print '<br /><a href="https://doi.org/'.$item['DOI'].'" target="_blank">https://doi.org/'.$item['DOI'].'</a>';
				print '<p>';
				$no++;
			}elseif($item['VOLUME_TITLE'] && $item['GIVEN_NAME'] && $item['DOI']){
                                print '<p>'.$no.'. ';
					print '<b>'.$item['VOLUME_TITLE'].'</b><br />';
					$i = 0;
					print '<span style="font-size: 0.9em;">';
					foreach($item['GIVEN_NAME'] as $key2 => $name){
					    if($i > 0) print ', ';
					    print $name;
					    $i++;
					}
					print '</span>';
					print '<br />';
					if($item['ISBN']) print '<b><i>ISBN&nbsp;'.$item['ISBN'].'</i></b>&nbsp;&nbsp;';
					if($item['COMPONENT_NUMBER']) print $item['COMPONENT_NUMBER'].',&nbsp;&nbsp;';
					if($item['FIRST_PAGE']) print __('plugins.generic.citedby.firstpage').'&nbsp;'.$item['FIRST_PAGE'].',&nbsp;&nbsp;';
					if($item['YEAR']) print __('plugins.generic.citedby.year').'&nbsp;'.$item['YEAR'].'&nbsp;&nbsp;';
					if($item['DOI']) print '<br /><a href="https://doi.org/'.$item['DOI'].'" target="_blank">https://doi.org/'.$item['DOI'].'</a>';
				print '<p>';
				$no++;
                        }
		}
	}

 	/*
 	 * Execute a management verb on this plugin
 	 * @param $verb string
 	 * @param $args array
	 * @param $message string Location for the plugin to put a result msg
 	 * @return boolean
 	 */
	function manage($verb, $args, &$message) {
		if (!parent::manage($verb, $args, $message)) return false;

		switch ($verb) {
			case 'settings':
				$templateMgr =& TemplateManager::getManager();
				$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));
				$journal =& Request::getJournal();
				
				$this->import('CitedBySettingsForm');
				$form = new CitedBySettingsForm($this, $journal->getId());
				if (Request::getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						Request::redirect(null, 'manager', 'plugin');
						return false;
					} else {
						$this->setBreadCrumbs(true);
						$form->display();
					}
				} else {
					$this->setBreadCrumbs(true);
					$form->initData();
					$form->display();
				}
				return true;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
	}
}
?>
