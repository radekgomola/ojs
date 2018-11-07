<?php

/**
 * @file pages/index/IndexHandler.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class IndexHandler
 * @ingroup pages_index
 *
 * @brief Handle site index requests.
 */

import('classes.handler.Handler');

class IndexHandler extends Handler {
	/**
	 * Constructor
	 **/
	function IndexHandler() {
		parent::Handler();
	}

	/**
	 * If no journal is selected, display list of journals.
	 * Otherwise, display the index page for the selected journal.
	 * @param $args array
	 * @param $request Request
	 */
	function index($args, &$request) {
		$this->validate();
		$this->setupTemplate();

		$router =& $request->getRouter();
		$templateMgr =& TemplateManager::getManager();
		$journalDao =& DAORegistry::getDAO('JournalDAO');
		$journalPath = $router->getRequestedContextPath($request);
		$templateMgr->assign('helpTopicId', 'user.home');
		$journal =& $router->getContext($request);
		if ($journal) {
			// Assign header and content for home page
			$templateMgr->assign('displayPageHeaderTitle', $journal->getLocalizedPageHeaderTitle(true));
			$templateMgr->assign('displayPageHeaderLogo', $journal->getLocalizedPageHeaderLogo(true));
			$templateMgr->assign('displayPageHeaderTitleAltText', $journal->getLocalizedSetting('homeHeaderTitleImageAltText'));
			$templateMgr->assign('displayPageHeaderLogoAltText', $journal->getLocalizedSetting('homeHeaderLogoImageAltText'));
			$templateMgr->assign('additionalHomeContent', $journal->getLocalizedSetting('additionalHomeContent'));
			$templateMgr->assign('homepageImage', $journal->getLocalizedSetting('homepageImage'));
			$templateMgr->assign('homepageImageAltText', $journal->getLocalizedSetting('homepageImageAltText'));
			$templateMgr->assign('journalDescription', $journal->getLocalizedSetting('description'));
                        $templateMgr->assign('homePage', TRUE);

			$displayCurrentIssue = $journal->getSetting('displayCurrentIssue');
			$issueDao =& DAORegistry::getDAO('IssueDAO');
			$issue =& $issueDao->getCurrentIssue($journal->getId(), true);
			if ($displayCurrentIssue && isset($issue)) {
				import('pages.issue.IssueHandler');
				// The current issue TOC/cover page should be displayed below the custom home page.
				IssueHandler::_setupIssueTemplate($request, $issue);
			}

			$enableAnnouncements = $journal->getSetting('enableAnnouncements');
			if ($enableAnnouncements) {
				$enableAnnouncementsHomepage = $journal->getSetting('enableAnnouncementsHomepage');
				if ($enableAnnouncementsHomepage) {
					$numAnnouncementsHomepage = $journal->getSetting('numAnnouncementsHomepage');
					$announcementDao =& DAORegistry::getDAO('AnnouncementDAO');
					$announcements =& $announcementDao->getNumAnnouncementsNotExpiredByAssocId(ASSOC_TYPE_JOURNAL, $journal->getId(), $numAnnouncementsHomepage);
					$templateMgr->assign('announcements', $announcements);
					$templateMgr->assign('enableAnnouncementsHomepage', $enableAnnouncementsHomepage);
				}
			}
                        $templateMgr->assign_by_ref('journal', $journal);
			$templateMgr->display('index/journal.tpl');
		} else {
			$site =& Request::getSite();

			if ($site->getRedirect() && ($journal = $journalDao->getById($site->getRedirect())) != null) {
				$request->redirect($journal->getPath());
			}

			$templateMgr->assign('intro', $site->getLocalizedIntro());
			$templateMgr->assign('journalFilesPath', $request->getBaseUrl() . '/' . Config::getVar('files', 'public_files_dir') . '/journals/');

			// If we're using paging, fetch the parameters
			$usePaging = $site->getSetting('usePaging');
			if ($usePaging) $rangeInfo =& $this->getRangeInfo('journals');
			else $rangeInfo = null;
			$templateMgr->assign('usePaging', $usePaging);

			// Fetch the alpha list parameters
			$searchInitial = Request::getUserVar('searchInitial');
			$templateMgr->assign('searchInitial', $searchInitial);
                        
                        $searchFaculty = Request::getUserVar('searchFaculty');
			$templateMgr->assign('searchFaculty', $searchFaculty);
                        
                        $searchDatabase = Request::getUserVar('searchDatabase');
			$templateMgr->assign('searchDatabase', $searchDatabase);

			$templateMgr->assign('useAlphalist', $site->getSetting('useAlphalist'));

                        if($searchFaculty && $searchFaculty != ""){
                            $journals =& $journalDao->getJournals(
                                    true,
                                    $rangeInfo,
                                    JOURNAL_FIELD_SEQUENCE,
                                    $searchFaculty?JOURNAL_FIELD_FACULTY:null,
                                    $searchFaculty?'contains':null,
                                    $searchFaculty
                            );
                        } else if($searchDatabase && $searchDatabase != ""){
                            $journals =& $journalDao->getJournals(
                                    true,
                                    $rangeInfo,
                                    JOURNAL_FIELD_SEQUENCE,
                                    $searchDatabase?JOURNAL_FIELD_DATABASE:null,
                                    $searchDatabase?'contains':null,
                                    $searchDatabase
                            );
                        } else {
                            $journals =& $journalDao->getJournals(
                                    true,
                                    $rangeInfo,
                                    $searchInitial?JOURNAL_FIELD_TITLE:JOURNAL_FIELD_SEQUENCE,
                                    $searchInitial?JOURNAL_FIELD_TITLE:null,
                                    $searchInitial?'startsWith':null,
                                    $searchInitial
                            );
                        }
                        
                        
			$templateMgr->assign_by_ref('journals', $journals);
			$templateMgr->assign_by_ref('site', $site);

			$templateMgr->assign('alphaList', explode(' ', __('common.alphaList')));
                        $templateMgr->assign('databaseList', explode(';', __('common.databaseList')));
                        $templateMgr->assign('facultyList', explode(';', __('common.facultyList')));

			$templateMgr->setCacheability(CACHEABILITY_PUBLIC);
			$templateMgr->display('index/site.tpl');
		}
	}
}

?>
