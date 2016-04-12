<?php
/**
 * @file plugins/generic/linksAdder/LinksAdderDAO.inc.php
 *

 * @package plugins.generic.linksAdder
 * @class LinksAdderDAO
 *
 * Operations for retrieving and modifying LinksAdder objects.
 *
 */
import('lib.pkp.classes.db.DAO');

class LinksAdderDAO extends DAO {
	/** @var $parentPluginName Name of parent plugin */
	var $parentPluginName;

	/**
	 * Constructor
	 */
	function LinksAdderDAO($parentPluginName) {
		$this->parentPluginName = $parentPluginName;
		parent::DAO();
	}

	function getAddedLink($addedLinkId) {
		$result =& $this->retrieve(
			'SELECT * FROM munipress_added_links WHERE added_link_id = ?', $addedLinkId
		);

		$returner = null;
		if ($result->RecordCount() != 0) {
			$returner =& $this->_returnAddedLinkFromRow($result->GetRowAssoc(false));
		}
		$result->Close();
		return $returner;
	}

	function &getAddedLinksByJournalId($journalId, $rangeInfo = null) {
		$result =& $this->retrieveRange(
			'SELECT * FROM munipress_added_links WHERE journal_id = ?', $journalId, $rangeInfo
		);

		$returner = new DAOResultFactory($result, $this, '_returnAddedLinkFromRow');
		return $returner;
	}

	function getAddedLinksByUmisteni($journalId, $umisteni) {
		$result =& $this->retrieve(
			'SELECT * FROM munipress_added_links WHERE journal_id = ? AND umisteni = ?', array($journalId, $umisteni)
		);

		$returner = new DAOResultFactory($result, $this, '_returnAddedLinkFromRow');
		return $returner;
	}

	function insertAddedLink(&$addedLink) {
		$this->update(
			'INSERT INTO munipress_added_links
				(journal_id, umisteni, target)
				VALUES
				(?, ?, ?)',
			array(
				$addedLink->getJournalId(),
				$addedLink->getUmisteni(),
                                $addedLink->getTarget()
			)
		);

		$addedLink->setId($this->getInsertAddedLinkId());
		$this->updateLocaleFields($addedLink);

		return $addedLink->getId();
	}

	function updateAddedLink(&$addedLink) {
		$returner = $this->update(
			'UPDATE munipress_added_links
				SET
					journal_id = ?,
					umisteni = ?,
                                        target = ?
				WHERE added_link_id = ?',
				array(
					$addedLink->getJournalId(),
					$addedLink->getUmisteni(),
                                        $addedLink->getTarget(),
					$addedLink->getId()
					)
			);
		$this->updateLocaleFields($addedLink);
		return $returner;
	}

	function deleteAddedLinkById($addedLinkId) {
		$returner = $this->update(
			'DELETE FROM munipress_added_links WHERE added_link_id = ?', $addedLinkId
		);
		return $this->update(
			'DELETE FROM munipress_added_link_settings WHERE added_link_id = ?', $addedLinkId
		);
	}

	function &_returnAddedLinkFromRow(&$row) {
		$linksAdderPlugin =& PluginRegistry::getPlugin('generic', $this->parentPluginName);
		$linksAdderPlugin->import('AddedLink');

		$addedLink = new AddedLink();
		$addedLink->setId($row['added_link_id']);
		$addedLink->setUmisteni($row['umisteni']);
		$addedLink->setJournalId($row['journal_id']);
                $addedLink->setTarget($row['target']);

		$this->getDataObjectSettings('munipress_added_link_settings', 'added_link_id', $row['added_link_id'], $addedLink);
		return $addedLink;
	}

	function getInsertAddedLinkId() {
		return $this->getInsertId('munipress_added_links', 'added_link_id');
	}

	/**
	 * Get field names for which data is localized.
	 * @return array
	 */
	function getLocaleFieldNames() {
		return array('name', 'link');
	}

	/**
	 * Update the localized data for this object
	 * @param $author object
	 */
	function updateLocaleFields(&$addedLink) {
		$this->updateDataObjectSettings('munipress_added_link_settings', $addedLink, array(
			'added_link_id' => $addedLink->getId()
		));
	}

	/**
	 * Find duplicate path
	 * @param $path String
	 * @param journalId int
	 * @param $staticPageId	int
	 * @return boolean
	 */
//	function duplicatePathExists ($path, $journalId, $staticPageId = null) {
//		$params = array(
//					$journalId,
//					$path
//					);
//		if (isset($staticPageId)) $params[] = $staticPageId;
//
//		$result = $this->retrieve(
//			'SELECT *
//				FROM static_pages
//				WHERE journal_id = ?
//				AND path = ?' .
//				(isset($staticPageId)?' AND NOT (static_page_id = ?)':''),
//				$params
//			);
//
//		if($result->RecordCount() == 0) {
//			// no duplicate exists
//			$returner = false;
//		} else {
//			$returner = true;
//		}
//		return $returner;
//	}
}
?>
