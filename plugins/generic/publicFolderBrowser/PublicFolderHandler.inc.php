<?php

/**
 * @file PublicFolderHandler.inc.php
 *
 * Copyright (c) 2003-2008 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PublicFolderHandler
 * @ingroup plugins/generic/publicFolderBrowser
 *
 * @brief Handle requests for public folder browser functions. 
 */

// $Id: PublicFolderHandler.inc.php,v 1.15 2008/07/01 01:16:12 asmecher Exp $

import('manager.ManagerHandler');
class PublicFolderHandler extends ManagerHandler {

	/**
	 * Display the contents of public folder associated with a journal.
	 */
	function publicFolder($args) {
		parent::validate();
		parent::setupTemplate(true);

		import('file.FileManager');

		$templateMgr = &TemplateManager::getManager();
		$templateMgr->assign('pageHierarchy', array(array(Request::url(null, 'manager'), 'manager.journalManagement')));

		PublicFolderHandler::parseDirArg($args, $currentDir, $parentDir);
		$currentPath = PublicFolderHandler::getRealFilesDir($currentDir);

		if (@is_file($currentPath)) {
			$fileMgr = &new FileManager();
			if (Request::getUserVar('download')) {
				$fileMgr->downloadFile($currentPath);
			} else {
				$fileMgr->viewFile($currentPath, PublicFolderHandler::fileMimeType($currentPath));
			}

		} else {
			$files = array();
			if ($dh = @opendir($currentPath)) {
				while (($file = readdir($dh)) !== false) {
					if ($file != '.' && $file != '..') {
						$filePath = $currentPath . '/'. $file;
						$isDir = is_dir($filePath);
						$info = array(
							'name' => $file,
							'isDir' => $isDir,
							'mimetype' => $isDir ? '' : PublicFolderHandler::fileMimeType($filePath),
							'mtime' => filemtime($filePath),
							'size' => $isDir ? '' : FileManager::getNiceFileSize(filesize($filePath)),
						);
						$files[$file] = $info;
					}
				}
				closedir($dh);
			}
			ksort($files);
			$templateMgr->assign_by_ref('files', $files);
			$templateMgr->assign('currentDir', $currentDir);
			$templateMgr->assign('parentDir', $parentDir);
			$templateMgr->assign('helpTopicId','journal.managementPages.fileBrowser');
			$plugin =& Registry::get('plugin');
			$templateMgr->display($plugin->getTemplatePath() . 'index.tpl');
		}
	}

	/**
	 * Upload a new file.
	 */
	function publicFileUpload($args) {
		parent::validate();

		PublicFolderHandler::parseDirArg($args, $currentDir, $parentDir);
		$currentPath = PublicFolderHandler::getRealFilesDir($currentDir);

		import('file.FileManager');
		$fileMgr = &new FileManager();
		if ($fileMgr->uploadedFileExists('file')) {
			$destPath = $currentPath . '/' . PublicFolderHandler::cleanFileName($fileMgr->getUploadedFileName('file'));
			@$fileMgr->uploadFile('file', $destPath);
		}

		Request::redirect(null, null, 'publicFolder', explode('/', $currentDir));

	}

	/**
	 * Create a new directory
	 */
	function publicFileMakeDir($args) {
		parent::validate();

		PublicFolderHandler::parseDirArg($args, $currentDir, $parentDir);

		if ($dirName = Request::getUserVar('dirName')) {
			$currentPath = PublicFolderHandler::getRealFilesDir($currentDir);
			$newDir = $currentPath . '/' . PublicFolderHandler::cleanFileName($dirName);

			import('file.FileManager');
			$fileMgr = &new FileManager();
			@$fileMgr->mkdir($newDir);
		}

		Request::redirect(null, null, 'publicFolder', explode('/', $currentDir));
	}

	function publicFileDelete($args) {
		parent::validate();

		PublicFolderHandler::parseDirArg($args, $currentDir, $parentDir);
		$currentPath = PublicFolderHandler::getRealFilesDir($currentDir);

		import('file.FileManager');
		$fileMgr = &new FileManager();

		if (@is_file($currentPath)) {
			$fileMgr->deleteFile($currentPath);
		} else {
			// TODO Use recursive delete (rmtree) instead?
			@$fileMgr->rmdir($currentPath);
		}

		Request::redirect(null, null, 'publicFolder', explode('/', $parentDir));
	}


	//
	// Helper functions
	// FIXME Move some of these functions into common class (FileManager?)
	//

	function parseDirArg($args, &$currentDir, &$parentDir) {
		$pathArray = array_filter($args, array('PublicFolderHandler', 'fileNameFilter'));
		$currentDir = join($pathArray, '/');
		array_pop($pathArray);
		$parentDir = join($pathArray, '/');
	}

	function getRealFilesDir($currentDir) {
		$journal = &Request::getJournal();
		return Config::getVar('files', 'public_files_dir') . '/journals/' . $journal->getJournalId() .'/' . $currentDir;
	}

	function fileNameFilter($var) {
		return (!empty($var) && $var != '..' && $var != '.');
	}

	function cleanFileName($var) {
		$var = String::regexp_replace('/[^\w\-\.]/', '', $var);
		if (!PublicFolderHandler::fileNameFilter($var)) {
			$var = time() . '';
		}
		return $var;
	}

	function fileMimeType($filePath) {
		return String::mime_content_type($filePath);
	}

}
?>
