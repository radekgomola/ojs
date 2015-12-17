<?php

/**
 * @defgroup submission_form
 */

/**
 * @file classes/submission/form/LandingPageSettingForm.inc.php
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class LandingPageSettingForm
 * @ingroup submission_form
 * @see ArticleGalley
 *
 * @brief Article galley editing form.
 */

import('lib.pkp.classes.form.Form');

class LandingPageSettingForm extends Form {
	/** @var int the ID of the article */
	var $articleId;
        
        var $allGalleys;

	/**
	 * Constructor.
	 * @param $articleId int
	 * @param $galleyId int (optional)
	 */
	function LandingPageSettingForm($articleId) {
		parent::Form('submission/layout/landingPageSettingForm.tpl');
		$journal =& Request::getJournal();
		$this->articleId = $articleId;

                $galleyDao =& DAORegistry::getDAO('ArticleGalleyDAO');
                $this->allGalleys = $galleyDao->getGalleysByArticle($articleId);

		// Validation checks for this form
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Display the form.
	 */
	function display() {
		$journal =& Request::getJournal();
                
                $articleDao =& DAORegistry::getDAO('ArticleDAO');
		$article =& $articleDao->getArticle($this->articleId, $journal->getId());

		$templateMgr =& TemplateManager::getManager();

		$templateMgr->assign('articleId', $this->articleId);
		$templateMgr->assign('skipGalleyId', $article->getSkipGalleyId());
                $templateMgr->assign('allGalleys', $this->allGalleys);
		$templateMgr->assign('skipLandingPage', $article->getSkipLandingPage());

		parent::display();
	}

	function initData() {
		parent::initData();
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(
			array(
				'skipGalleyIdNew',
				'skipLandingPageNew'
			)
		);
	}

	/**
	 * Save changes to the article munipress settings.
	 * @return int the skip galley id
	 */
	function execute() {
		import('classes.file.ArticleFileManager');
		$articleFileManager = new ArticleFileManager($this->articleId);

                $journal =& Request::getJournal();

                $articleDao =& DAORegistry::getDAO('ArticleDAO');
		$article =& $articleDao->getArticle($this->articleId, $journal->getId());

                $article->setSkipLandingPage($this->getData('skipLandingPageNew'));
                $article->setSkipGalleyId($this->getData('skipGalleyIdNew'));
		
                parent::execute();

		// Stamp the article modification (for OAI)
		$articleDao->updateArticle($article);

		return $this->getData('skipGalleyIdNew');
	}

}

?>
