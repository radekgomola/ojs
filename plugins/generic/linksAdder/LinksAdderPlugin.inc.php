<?php

/**
 * @file plugins/generic/linksAdder/LinksAdderPlugin.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.staticPages
 * @class StaticPagesPlugin
 *
 * StaticPagesPlugin class
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class LinksAdderPlugin extends GenericPlugin {
    
	function getDisplayName() {
		return __('plugins.generic.linksAdder.displayName');
	}

	function getDescription() {
		$description = __('plugins.generic.linksAdder.description');
		return $description;
	}

	/**
	 * Register the plugin, attaching to hooks as necessary.
	 * @param $category string
	 * @param $path string
	 * @return boolean
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				$this->import('LinksAdderDAO');
				if (checkPhpVersion('5.0.0')) { // WARNING: see http://pkp.sfu.ca/wiki/index.php/Information_for_Developers#Use_of_.24this_in_the_constructor
					$linksAdderDao = new LinksAdderDAO($this->getName());
				} else {
					$linksAdderDao =& new LinksAdderDAO($this->getName());
				}
				$returner =& DAORegistry::registerDAO('LinksAdderDAO', $linksAdderDao);
                                
                                HookRegistry::register('Templates::About::Index::People',
					array(&$this, 'callbackLide'));
                                
                                HookRegistry::register('Templates::About::Index::Policies',
					array(&$this, 'callbackPravidla'));
                                
                                HookRegistry::register('Templates::About::Index::Submissions',
					array(&$this, 'callbackPrispevky'));
                                
                                HookRegistry::register('Templates::About::Index::Other',
					array(&$this, 'callbackJine'));
			}
			return true;
		}
		return false;
	}

        function callbackLide($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
                $journal =& Request::getJournal();
                
                $journalId = $journal->getId();
                $umisteni = "lide";
                
                $this->import('LinksAdderDAO');
                
                $linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');

		$rangeInfo =& Handler::getRangeInfo('addedLinks');
		$addedLinks = $linksAdderDao->getAddedLinksByUmisteni($journalId, $umisteni);

                $output = "";
                while (!$addedLinks->eof()) {
                        $addedLink =& $addedLinks->next();
                        
                        $url = trim(strip_tags($addedLink->getAddedLinkLink()));
                        preg_match("/(^https?:\/{2})|^w{3}./i", $url, $test);
                        
                        if (sizeOf($test) > 0){
                            $path = $url;
                        } else {
                            $path = Request::getBaseUrl()."/".$url;
                        }
                        $output .= "<li><a href='".$path."' target='".$addedLink->getTarget()."'>" .$addedLink->getAddedLinkName(). "</a></li>";
                }
                return false;
	}
        
         function callbackPravidla($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
                $journal =& Request::getJournal();
                
                $journalId = $journal->getId();
                $umisteni = "pravidla";
                
                $this->import('LinksAdderDAO');
                
                $linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');

		$rangeInfo =& Handler::getRangeInfo('addedLinks');
		$addedLinks = $linksAdderDao->getAddedLinksByUmisteni($journalId, $umisteni);

                $output = "";
                while (!$addedLinks->eof()) {
                        $addedLink =& $addedLinks->next();
                        
                        $url = trim(strip_tags($addedLink->getAddedLinkLink()));
                        preg_match("/(^https?:\/{2})|^w{3}./i", $url, $test);
                        
                        if (sizeOf($test) > 0){
                            $path = $url;
                        } else {
                            $path = Request::getBaseUrl()."/".$url;
                        }
                        $output .= "<li><a href='".$path."' target='".$addedLink->getTarget()."'>" .$addedLink->getAddedLinkName(). "</a></li>";
                }
                return false;
	}
        
         function callbackPrispevky($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
                $journal =& Request::getJournal();
                
                $journalId = $journal->getId();
                $umisteni = "prispevky";
                
                $this->import('LinksAdderDAO');
                
                $linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');

		$rangeInfo =& Handler::getRangeInfo('addedLinks');
		$addedLinks = $linksAdderDao->getAddedLinksByUmisteni($journalId, $umisteni);

                $output = "";
                while (!$addedLinks->eof()) {
                        $addedLink =& $addedLinks->next();
                        
                        $url = trim(strip_tags($addedLink->getAddedLinkLink()));
                        preg_match("/(^https?:\/{2})|^w{3}./i", $url, $test);
                        
                        if (sizeOf($test) > 0){
                            $path = $url;
                        } else {
                            $path = Request::getBaseUrl()."/".$url;
                        }
                        $output .= "<li><a href='".$path."' target='".$addedLink->getTarget()."'>" .$addedLink->getAddedLinkName(). "</a></li>";
                }
                return false;
	}
         function callbackJine($hookName, $args) {
		$params =& $args[0];
		$smarty =& $args[1];
		$output =& $args[2];
                $journal =& Request::getJournal();
                
                $journalId = $journal->getId();
                $umisteni = "jine";
                
                $this->import('LinksAdderDAO');
                
                $linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');

		$rangeInfo =& Handler::getRangeInfo('addedLinks');
		$addedLinks = $linksAdderDao->getAddedLinksByUmisteni($journalId, $umisteni);

                $output = "";
                while (!$addedLinks->eof()) {
                        $addedLink =& $addedLinks->next();
                        
                        $url = trim(strip_tags($addedLink->getAddedLinkLink()));
                        preg_match("/(^https?:\/{2})|^w{3}./i", $url, $test);
                        
                        if (sizeOf($test) > 0){
                            $path = $url;
                        } else {
                            $path = Request::getBaseUrl()."/".$url;
                        }
                        $output .= "<li><a href='".$path."' target='".$addedLink->getTarget()."'>" .$addedLink->getAddedLinkName(). "</a></li>";
                }
                return false;
	}
	/**
	 * Declare the handler function to process the actual links NAME
	 */
//	function callbackHandleContent($hookName, $args) {
//		$templateMgr =& TemplateManager::getManager();
//
//		$link =& $args[0];
//		$op =& $args[1];
//
//		if ( $link == 'links' ) {
//			define('LINKS_ADDER_PLUGIN_NAME', $this->getName()); // Kludge
//			define('HANDLER_CLASS', 'LinksAdderHandler');
//			$this->import('LinksAdderHandler');
//			return true;
//		}
//		return false;
//	}

        /**
	 * Display verbs for the management interface.
	 */
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
                    $verbs[] = array('settings', __('plugins.generic.staticPages.editAddContent'));
		}
		return parent::getManagementVerbs($verbs);
	}
        
	/**
	 * Perform management functions
	 */
	function manage($verb, $args, &$message, &$messageParams) {
		if (!parent::manage($verb, $args, $message, $messageParams)) return false;

		$templateMgr =& TemplateManager::getManager();
		$templateMgr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));

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

		switch ($verb) {
			case 'settings':
				$journal =& Request::getJournal();

				$this->import('LinksAdderSettingsForm');
				$form = new LinksAdderSettingsForm($this, $journal->getId());

				$templateMgr->assign('pageHierarchy', $pageCrumbs);
				$form->initData();
				$form->display();
				return true;
			case 'edit':
			case 'add':
				$journal =& Request::getJournal();

				$this->import('LinksAdderEditForm');

				$addedLinkId = isset($args[0])?(int)$args[0]:null;
				$form = new LinksAdderEditForm($this, $journal->getId(), $addedLinkId);

				if ($form->isLocaleResubmit()) {
					$form->readInputData();
				} else {
					$form->initData();
				}

				$pageCrumbs[] = array(
					Request::url(null, 'manager', 'plugin', array('generic', $this->getName(), 'settings')),
					$this->getDisplayName(),
					true
				);
				$templateMgr->assign('pageHierarchy', $pageCrumbs);
				$form->display();
				return true;
			case 'save':
				$journal =& Request::getJournal();

				$this->import('LinksAdderEditForm');

				$addedLinkId = isset($args[0])?(int)$args[0]:null;
				$form = new LinksAdderEditForm($this, $journal->getId(), $addedLinkId);

				if (Request::getUserVar('edit')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->save();
						$templateMgr->assign(array(
							'currentUrl' => Request::url(null, null, null, array($this->getCategory(), $this->getName(), 'settings')),
							'pageTitle' => 'plugins.generic.linksAdder.displayName',
							'pageHierarchy' => $pageCrumbs,
							'message' => 'plugins.generic.linksAdder.linkSaved',
							'backLink' => Request::url(null, null, null, array($this->getCategory(), $this->getName(), 'settings')),
							'backLinkLabel' => 'common.continue'
						));
						$templateMgr->display('common/message.tpl');
						exit;
					} else {
						$form->display();
						exit;
					}
				}
				Request::redirect(null, null, 'manager', 'plugins');
				return false;
			case 'delete':
				$journal =& Request::getJournal();
				$addedLinkId = isset($args[0])?(int) $args[0]:null;
				$linksAdderDao =& DAORegistry::getDAO('LinksAdderDAO');
				$linksAdderDao->deleteAddedLinkById($addedLinkId);

				$templateMgr->assign(array(
					'currentUrl' => Request::url(null, null, null, array($this->getCategory(), $this->getName(), 'settings')),
					'pageTitle' => 'plugins.generic.linksAdder.displayName',
					'message' => 'plugins.generic.linksAdder.pageDeleted',
					'backLink' => Request::url(null, null, null, array($this->getCategory(), $this->getName(), 'settings')),
					'backLinkLabel' => 'common.continue'
				));

				$templateMgr->assign('pageHierarchy', $pageCrumbs);
				$templateMgr->display('common/message.tpl');
				return true;
			default:
				// Unknown management verb
				assert(false);
				return false;
		}
	}

	/**
	 * Get the filename of the ADODB schema for this plugin.
	 */
	function getInstallSchemaFile() {
		return $this->getPluginPath() . '/' . 'schema.xml';
	}
}

?>
