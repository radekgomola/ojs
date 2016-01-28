<?php

/**
 * @file plugins/blocks/top10articles/Cz_en_blok_smallBlockPlugin.inc.php
 */

import('lib.pkp.classes.plugins.BlockPlugin');

import('classes.plugins.ReportPlugin');

class Top10articlesBlockPlugin extends BlockPlugin {
	/**
	 * Determine whether the plugin is enabled. Overrides parent so that
	 * the plugin will be displayed during install.
	 */
	function getEnabled() {
		if (!Config::getVar('general', 'installed')) return true;
		return parent::getEnabled();
	}

	/**
	 * Install default settings on system install.
	 * @return string
	 */
	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Install default settings on journal creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the block context. Overrides parent so that the plugin will be
	 * displayed during install.
	 * @return int
	 */
	function getBlockContext() {
		if (!Config::getVar('general', 'installed')) return BLOCK_CONTEXT_RIGHT_SIDEBAR;
		return parent::getBlockContext();
	}

	/**
	 * Determine the plugin sequence. Overrides parent so that
	 * the plugin will be displayed during install.
	 */
	function getSeq() {
		if (!Config::getVar('general', 'installed')) return 2;
		return parent::getSeq();
	}

	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.top10articles.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.top10articles.description');
	}

	/**
	 * Get the HTML contents for this block.
	 */
	function getContents(&$templateMgr) {
                $journal =& Request::getJournal();

		$issueDao =& DAORegistry::getDAO('IssueDAO');
		$publishedArticleDao =& DAORegistry::getDAO('PublishedArticleDAO');
		$metricsDao =& DAORegistry::getDAO('MetricsDAO'); /* @var $metricsDao MetricsDAO */

		$columns = array(
		__('plugins.reports.views.articleId'),
		__('plugins.reports.views.articleTitle'),
		__('issue.issue'),
		__('plugins.reports.views.datePublished'),
		__('plugins.reports.views.abstractViews'),
		__('plugins.reports.views.galleyViews'),
		);
		$abstractViewCounts = array();
		$articleTitles = array();
		$firstTime = true;
		$result = array();
                
                $totalViews = array();
                $vystup = array();
		$page = 1;
                $count = 20;
                $finalCount = 10;
                
                $metricType = OJS_METRIC_TYPE_COUNTER;
                
		while (true) {
                        import('classes.db.DBResultRange');
                        $dbResultRange = new DBResultRange(STATISTICS_MAX_ROWS);
			$dbResultRange->setPage($page);
                        $dbResultRange->setCount($count);
                        
			$result = $metricsDao->getMetrics($metricType,
				array(STATISTICS_DIMENSION_ASSOC_ID),
				array(STATISTICS_DIMENSION_ASSOC_TYPE => ASSOC_TYPE_ARTICLE, STATISTICS_DIMENSION_CONTEXT_ID => $journal->getId()),
				array(STATISTICS_METRIC => STATISTICS_ORDER_DESC),
				$dbResultRange);
                        
			foreach ($result as $record) {                            
				$articleId = $record[STATISTICS_DIMENSION_ASSOC_ID];
				$publishedArticle =& $publishedArticleDao->getPublishedArticleByArticleId($articleId, null, true);
				if (!is_a($publishedArticle, 'PublishedArticle')) {
					continue;
				}
				$issueId = $publishedArticle->getIssueId();
				if (!$issueId) {
					continue;
				}
				$articleTitles[$articleId] = $publishedArticle->getArticleTitle();
				// Store the abstract view count, making
				// sure both metric types will be counted.
				if (isset($abstractViewCounts[$articleId])) {
					$abstractViewCounts[$articleId] += $record[STATISTICS_METRIC];
				} else {
					$abstractViewCounts[$articleId] = $record[STATISTICS_METRIC];
				} 
                                
				// Clean up
				unset($publishedArticle);
			}

			$firstTime = false;
			if (count($result) < STATISTICS_MAX_ROWS) break;
		}

                arsort($abstractViewCounts);
                $abstractViewCounts = array_slice($abstractViewCounts, 0, $finalCount, true);
		foreach ($abstractViewCounts as $articleId => $abstractViewCount) {
			$vystup[] = array(
				$articleId,
				$articleTitles[$articleId],$abstractViewCount);
		}
                $templateMgr->assign('top10articles', $vystup);
		return parent::getContents($templateMgr);
	}
}

?>
