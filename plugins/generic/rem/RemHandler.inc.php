<?php

/**
 * @file RemHandler.inc.php
 *
 */

import('pages.article.ArticleHandler');

class RemHandler extends ArticleHandler {
  
	/**
	 * Constructor
	 * @param $request Request
	 */
	function RemHandler (&$request) {
		parent::ArticleHandler($request);
	}

	/**
	 * Return a RDF-formatted Resource Map for article
	 * @param $args array ($articleId)
	 * 
	 */
	function resourceMap ($args, &$request) {
		$router =& $request->getRouter();
		$articleId = isset($args[0]) ? $args[0] : 0;
		$this->validate($request, $articleId);
		
		$journal =& $router->getContext($request);

		//no template needed...
    header('Content-type: application/rdf+xml');
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?" . ">\n";
    $rem = new ResourceMap($journal, $this->issue, $this->article);
    echo $rem->getXml();
	}
}

?>
