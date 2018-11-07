<?php

/**
 * @file classes/manager/form/JournalSiteSettingsForm.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class JournalSiteSettingsForm
 * @ingroup admin_form
 *
 * @brief Form for site administrator to edit basic journal settings.
 */

import('lib.pkp.classes.db.DBDataXMLParser');
import('lib.pkp.classes.form.Form');

class JournalSiteSettingsForm extends Form {

	/** The ID of the journal being edited */
	var $journalId;

	/**
	 * Constructor.
	 * @param $journalId omit for a new journal
	 */
	function JournalSiteSettingsForm($journalId = null) {
		parent::Form('admin/journalSettings.tpl');

		$this->journalId = isset($journalId) ? (int) $journalId : null;

		// Validation checks for this form
		$this->addCheck(new FormValidatorLocale($this, 'title', 'required', 'admin.journals.form.titleRequired'));
		$this->addCheck(new FormValidator($this, 'journalPath', 'required', 'admin.journals.form.pathRequired'));
		$this->addCheck(new FormValidatorAlphaNum($this, 'journalPath', 'required', 'admin.journals.form.pathAlphaNumeric'));
		$this->addCheck(new FormValidatorCustom($this, 'journalPath', 'required', 'admin.journals.form.pathExists', create_function('$path,$form,$journalDao', 'return !$journalDao->journalExistsByPath($path) || ($form->getData(\'oldPath\') != null && $form->getData(\'oldPath\') == $path);'), array(&$this, DAORegistry::getDAO('JournalDAO'))));
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Display the form.
	 */
	function display($test = null) {
                $baseUrl =& Request::getBaseUrl();
                $templateMgr =& TemplateManager::getManager();
                if (isset($this->journalId)) {
			$journalDao =& DAORegistry::getDAO('JournalDAO');
                        $journal =& $journalDao->getById($this->journalId);      
                        $templateMgr->assign('thumbnail', $journal->getSetting('journalThumbnail'));
                        $publicFileManager = new PublicFileManager();
                        $templateMgr->assign('publicFilesDirCasopis', $baseUrl . '/' . $publicFileManager->getJournalFilesPath($this->journalId));
                }
		
		$templateMgr->assign('journalId', $this->journalId);
		$templateMgr->assign('helpTopicId', 'site.siteManagement');   
		parent::display();
	}

	/**
	 * Initialize form data from current settings.
	 */
	function initData() {

		if (isset($this->journalId)) {
			$journalDao =& DAORegistry::getDAO('JournalDAO');
			$journal =& $journalDao->getById($this->journalId);

			if ($journal != null) {
				$this->_data = array(
					'title' => $journal->getSetting('title', null), // Localized
					'description' => $journal->getSetting('description', null), // Localized
					'journalPath' => $journal->getPath(),
                                        'journalThumbnail' => $journal->getSetting('journalThumbnail', null),
                                    	'externiCasopis' => $journal->getSetting('externiCasopis'),
                                        'odkazCasopis' => $journal->getSetting('odkazCasopis'),
                                        'odkazCislo' => $journal->getSetting('odkazCislo'),
                                        'databaze' => $journal->getSetting('databaze'),
                                        'fakulta' => $journal->getSetting('fakulta'),
					'enabled' => $journal->getEnabled()
				);

			} else {
				$this->journalId = null;
			}
		}
		if (!isset($this->journalId)) {
			$this->_data = array(
				'enabled' => 1
			);
		}
                if (!isset($this->journalId)) {
			$this->_data = array(
				'externiCasopis' => 0
			);
		}
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('title', 'description', 'journalPath', 'enabled', 'formLocale', 'journalThumbnail', 'externiCasopis','odkazCasopis','odkazCislo','databaze', 'fakulta'));
		$this->setData('enabled', (int)$this->getData('enabled'));
                $this->setData('externiCasopis', (int)$this->getData('externiCasopis'));

		if (isset($this->journalId)) {
			$journalDao =& DAORegistry::getDAO('JournalDAO');
			$journal =& $journalDao->getById($this->journalId);
			$this->setData('oldPath', $journal->getPath());
		}
	}

	/**
	 * Get a list of field names for which localized settings are used
	 * @return array
	 */
	function getLocaleFieldNames() {
		return array('title', 'description');
	}

	/**
	 * Save journal settings.
	 */
	function execute() {
		$site =& Request::getSite();
		$journalDao =& DAORegistry::getDAO('JournalDAO');

                $deleteJournalThumbnail =& Request::getUserVar('deleteJournalThumbnail');
                $uploadJournalThumbnail =& Request::getUserVar('uploadJournalThumbnail');

		$formLocale = $this->getData('formLocale');
                
                $form = new Form();
                if($deleteJournalThumbnail){
                    $this->deleteImage('journalThumbnail', $formLocale);
                    $editData = true;
                }else if ($uploadJournalThumbnail) {
                    if ($this->uploadImage('journalThumbnail', $formLocale)) {
                            $editData = true;
                    } else {
                            $form->addError('journalThumbnail', __('manager.setup.journalThumbnailInvalid'));
                    }
                }else{                
                    if (isset($this->journalId)) {
                            $journal =& $journalDao->getById($this->journalId);
                    }

                    if (!isset($journal)) {
                            $journal = new Journal();
                    }

                    $journal->setPath($this->getData('journalPath'));
                    $journal->setEnabled($this->getData('enabled'));

                    if ($journal->getId() != null) {
                            $isNewJournal = false;
                            $journalDao->updateJournal($journal);
                            $section = null;
                    } else {
                            $isNewJournal = true;

                            // Give it a default primary locale
                            $journal->setPrimaryLocale ($site->getPrimaryLocale());

                            $journalId = $journalDao->insertJournal($journal);
                            $journalDao->resequenceJournals();

                            // Make the site administrator the journal manager of newly created journals
                            $sessionManager =& SessionManager::getManager();
                            $userSession =& $sessionManager->getUserSession();
                            if ($userSession->getUserId() != null && $userSession->getUserId() != 0 && !empty($journalId)) {
                                    $role = new Role();
                                    $role->setJournalId($journalId);
                                    $role->setUserId($userSession->getUserId());
                                    $role->setRoleId(ROLE_ID_JOURNAL_MANAGER);

                                    $roleDao =& DAORegistry::getDAO('RoleDAO');
                                    $roleDao->insertRole($role);
                            }

                            // Make the file directories for the journal
                            import('lib.pkp.classes.file.FileManager');
                            $fileManager = new FileManager();
                            $fileManager->mkdir(Config::getVar('files', 'files_dir') . '/journals/' . $journalId);
                            $fileManager->mkdir(Config::getVar('files', 'files_dir'). '/journals/' . $journalId . '/articles');
                            $fileManager->mkdir(Config::getVar('files', 'files_dir'). '/journals/' . $journalId . '/issues');
                            $fileManager->mkdir(Config::getVar('files', 'public_files_dir') . '/journals/' . $journalId);

                            // Install default journal settings
                            $journalSettingsDao =& DAORegistry::getDAO('JournalSettingsDAO');
                            $titles = $this->getData('title');
                            AppLocale::requireComponents(LOCALE_COMPONENT_OJS_DEFAULT, LOCALE_COMPONENT_APPLICATION_COMMON);
                            $journalSettingsDao->installSettings($journalId, 'registry/journalSettings.xml', array(
                                    'indexUrl' => Request::getIndexUrl(),
                                    'journalPath' => $this->getData('journalPath'),
                                    'primaryLocale' => $site->getPrimaryLocale(),
                                    'journalName' => $titles[$site->getPrimaryLocale()]
                            ));

                            // Install the default RT versions.
                            import('classes.rt.ojs.JournalRTAdmin');
                            $journalRtAdmin = new JournalRTAdmin($journalId);
                            $journalRtAdmin->restoreVersions(false);

                            // Create a default "Articles" section
                            $sectionDao =& DAORegistry::getDAO('SectionDAO');
                            $section = new Section();
                            $section->setJournalId($journal->getId());
                            $section->setTitle(__('section.default.title'), $journal->getPrimaryLocale());
                            $section->setAbbrev(__('section.default.abbrev'), $journal->getPrimaryLocale());
                            $section->setMetaIndexed(true);
                            $section->setMetaReviewed(true);
                            $section->setPolicy(__('section.default.policy'), $journal->getPrimaryLocale());
                            $section->setEditorRestricted(false);
                            $section->setHideTitle(false);
                            $sectionDao->insertSection($section);
                    }
                    $journal->updateSetting('supportedLocales', $site->getSupportedLocales());
                    $journal->updateSetting('title', $this->getData('title'), 'string', true);
                    $journal->updateSetting('description', $this->getData('description'), 'string', true);
                    $journal->updateSetting('externiCasopis', (boolean)$this->getData('externiCasopis'), 'bool');
                    $journal->updateSetting('odkazCasopis', $this->getData('odkazCasopis'), 'string');
                    $journal->updateSetting('odkazCislo', $this->getData('odkazCislo'), 'string');
                    $journal->updateSetting('databaze', $this->getData('databaze'), 'string');
                    $journal->updateSetting('fakulta', $this->getData('fakulta'), 'string');
                }
                
                if (!isset($editData) && $form->validate()) {

                    // Make sure all plugins are loaded for settings preload
                    PluginRegistry::loadAllPlugins();

                    HookRegistry::call('JournalSiteSettingsForm::execute', array(&$this, &$journal, &$section, &$isNewJournal));
                } else {
                        $this->display("tohle je test");
                }
	}
        
        /**
	 * Deletes a journal image.
	 * @param $settingName string setting key associated with the file
	 * @param $locale string
	 */
	function deleteImage($settingName, $locale = null) {
                $journalDao =& DAORegistry::getDAO('JournalDAO');
		$journal =& $journalDao->getById($this->journalId);
		$settingsDao =& DAORegistry::getDAO('JournalSettingsDAO');
		$setting = $settingsDao->getSetting($journal->getId(), $settingName);

		import('classes.file.PublicFileManager');
		$fileManager = new PublicFileManager();
		if ($fileManager->removeJournalFile($journal->getId(), $locale !== null ? $setting[$locale]['uploadName'] : $setting['uploadName'] )) {
			$returner = $settingsDao->deleteSetting($journal->getId(), $settingName, $locale);

			// Ensure page header is refreshed
			if ($returner) {
				$templateMgr =& TemplateManager::getManager();
				$templateMgr->assign(array(
					'displayPageHeaderTitle' => $journal->getLocalizedPageHeaderTitle(),
					'displayPageHeaderLogo' => $journal->getLocalizedPageHeaderLogo()
				));
			}
			return $returner;
		} else {
			return false;
		}
	}
        
        /**
	 * Uploads a journal image.
	 * @param $settingName string setting key associated with the file
	 * @param $locale string
	 */
	function uploadImage($settingName, $locale) {
		$journalDao =& DAORegistry::getDAO('JournalDAO');
		$journal =& $journalDao->getById($this->journalId);
		$settingsDao =& DAORegistry::getDAO('JournalSettingsDAO');

		import('classes.file.PublicFileManager');
		$fileManager = new PublicFileManager();
		if ($fileManager->uploadedFileExists($settingName)) {
			$type = $fileManager->getUploadedFileType($settingName);
			$extension = $fileManager->getImageExtension($type);
			if (!$extension) {
				return false;
			}

			$uploadName = $settingName . '_' . $locale . $extension;
			if ($fileManager->uploadJournalFile($journal->getId(), $settingName, $uploadName)) {
				// Get image dimensions
				$filePath = $fileManager->getJournalFilesPath($journal->getId());
				list($width, $height) = getimagesize($filePath . '/' . $uploadName);

				$value = $journal->getSetting($settingName);
				$newImage = empty($value[$locale]);

				$value[$locale] = array(
					'name' => $fileManager->getUploadedFileName($settingName, $locale),
					'uploadName' => $uploadName,
					'width' => $width,
					'height' => $height,
					'mimeType' => $type,
					'dateUploaded' => Core::getCurrentDate()
				);

				$journal->updateSetting($settingName, $value, 'object', true);

				if ($newImage) {
					$altText = $journal->getSetting($settingName.'AltText');
					if (!empty($altText[$locale])) {
						$this->setData($settingName.'AltText', $altText);
					}
				}

				return true;
			}
		}

		return false;
	}

}

?>
