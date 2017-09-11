<?php

/**
 * @file classes/submission/form/ReviewFormResponseFormPrint.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ReviewFormResponseForm
 * @ingroup submission_form
 * @see ReviewFormResponse
 *
 * @brief Peer review form response form.
 *
 */

import('lib.pkp.classes.form.Form');

class ReviewFormResponseFormPrint extends Form {

	/** @var int the ID of the review */
	var $reviewId;

	/** @var int the ID of the review form */
	var $reviewFormId;

	/**
	 * Constructor.
	 * @param $reviewId int
	 * @param $reviewFormId int
	 * @param $type string
	 */
	function ReviewFormResponseFormPrint($reviewId, $reviewFormId) {
		parent::Form('submission/reviewForm/reviewFormResponsePrint.tpl');

		$this->reviewId = $reviewId;
		$this->reviewFormId = $reviewFormId;

		$reviewFormElementDao =& DAORegistry::getDAO('ReviewFormElementDAO');
		$requiredReviewFormElementIds = $reviewFormElementDao->getRequiredReviewFormElementIds($this->reviewFormId);

		$this->addCheck(new FormValidatorCustom($this, 'reviewFormResponses', 'required', 'reviewer.article.reviewFormResponse.form.responseRequired', create_function('$reviewFormResponses, $requiredReviewFormElementIds', 'foreach ($requiredReviewFormElementIds as $requiredReviewFormElementId) { if (!isset($reviewFormResponses[$requiredReviewFormElementId]) || $reviewFormResponses[$requiredReviewFormElementId] == \'\') return false; } return true;'), array($requiredReviewFormElementIds)));
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Display the form.
	 */
	function display() {
		$journal =& Request::getJournal();
		$reviewFormDao =& DAORegistry::getDAO('ReviewFormDAO');
		$reviewForm =& $reviewFormDao->getReviewForm($this->reviewFormId, ASSOC_TYPE_JOURNAL, $journal->getId());
		$reviewFormElementDao =& DAORegistry::getDAO('ReviewFormElementDAO');
		$reviewFormElements =& $reviewFormElementDao->getReviewFormElements($this->reviewFormId);
		$reviewFormResponseDao =& DAORegistry::getDAO('ReviewFormResponseDAO');
		$reviewFormResponses =& $reviewFormResponseDao->getReviewReviewFormResponseValues($this->reviewId);
		$reviewAssignmentDao =& DAORegistry::getDAO('ReviewAssignmentDAO');
		$reviewAssignment = $reviewAssignmentDao->getById($this->reviewId);

		$templateMgr =& TemplateManager::getManager();
		$templateMgr->assign('pageTitle', 'submission.reviewFormResponse');
		$templateMgr->assign_by_ref('reviewForm', $reviewForm);
		$templateMgr->assign('reviewFormElements', $reviewFormElements);
		$templateMgr->assign('reviewFormResponses', $reviewFormResponses);
		$templateMgr->assign('reviewId', $this->reviewId);
		$templateMgr->assign('articleId', $reviewAssignment->getSubmissionId());
		$templateMgr->assign('isLocked', isset($reviewAssignment) && $reviewAssignment->getDateCompleted() != null);
		$templateMgr->assign('editorPreview', Request::getRequestedPage() != 'reviewer');

		parent::display();
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(
			array(
				'reviewFormResponses'
			)
		);
	}
}

?>
