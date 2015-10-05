<?php

class ResourceMap {

  protected $tags;	//internal array
  
  public $baseurl;
  public $journal;
  public $issue;
  public $article;
  public $rem;      //the resource map as rdf/xml string

	/**
	 *  Constructor
	 */
  function ResourceMap (&$journal, &$issue, &$article) {
   	$this->baseurl = Request::getIndexUrl();
  	$this->journal =& $journal;
  	$this->issue =& $issue;
  	$this->article =& $article;
  }
  
  function getXml () {
    $issue = $this->issue;
    $article = $this->article;
    $url = $this->makeUrl('rem','resourceMap', array($article->getBestArticleId()));

		$this->tags = array();
    $this->rdfHeader();  //rdf:RDF
    
    // ----- resource map --------------------------------------------------------------------------------------------
    $this->openTag('rdf:Description', array('rdf:about' => $url));
    $this->attrTag('rdf:type', array('rdf:resource' => 'http://www.openarchives.org/ore/terms/ResourceMap'));
		$this->attrTag('ore:describes', array('rdf:resource' => $url . '#Aggregation'));
    $this->closeTag();
    
    // ----- aggregation ---------------------------------------------------------------------------------------------
    $this->openTag('rdf:Description', array('rdf:about' => $url . '#Aggregation'));
		$this->attrTag('rdf:type', array('rdf:resource' => 'http://www.openarchives.org/ore/terms/Aggregation'));
		$this->attrTag('rdf:type', array('rdf:resource' => 'http://purl.org/info:eu-repo/semantics/EnhancedPublication'));
    if ($article->getPublicArticleId()) {
			$this->attrTag('ore:similarTo', array('rdf:resource' => $article->getPublicArticleId()));
		}
		$this->textTag('dcterms:title', $article->getLocalizedTitle());
    if ($article->getLocalizedAbstract()) {
			$this->textTag('dcterms:description', $article->getLocalizedAbstract());
		}
		foreach ($article->getAuthors() as $author) {
			$this->openTag('dcterms:creator');
			$this->openTag('foaf:Person');
			$this->textTag('foaf:familyName', $author->getLastName());
			$this->textTag('foaf:givenName', $author->getFirstName());
			$this->closeTag();
			$this->closeTag();
		}
		if ($article->getDatePublished()) {
			$this->textTag('dcterms:created', substr($article->getDatePublished(),0,10));
		}
		elseif ($issue && $issue->getDatePublished()) {
			$this->textTag('dcterms:created', substr($issue->getDatePublished(),0,10));
		}
		elseif ($issue) {
			$this->textTag('dcterms:created', $issue->getYear());
		}
		$this->textTag('dcterms:format', 'text/xml');
		$this->attrTag('ore:aggregates', array('rdf:resource' => $this->makeUrl('article','view', array($article->getBestArticleId()))));  //was rdf:hasRepresentation
    foreach ($article->getGalleys() as $galley) {
			$this->attrTag('ore:aggregates', array('rdf:resource' => $this->makeUrl('article','view', array($article->getBestArticleId(),$galley->getBestGalleyId($this->journal)))));
		}
		foreach ($article->getSuppFiles() as $suppFile) {
			if ($suppFile->getData('ejme_dans') && $suppFile->getData('ejme_dans') == 2 && $suppFile->getData('ejme_link') != '') {
				$this->attrTag('ore:aggregates', array('rdf:resource' => $suppFile->getData('ejme_link')));
			}
			elseif ($suppFile->getFileId()) {
				$this->attrTag('ore:aggregates', array('rdf:resource' => $this->makeUrl('article','downloadSuppFile', array($article->getBestArticleId(),$suppFile->getBestSuppFileId($this->journal)))));
			}
		}
    $this->closeTag();

    // ----- representation ------------------------------------------------------------------------------------------
    $this->openTag('rdf:Description', array('rdf:about' => $this->makeUrl('article','view', array($article->getBestArticleId()))));
    $this->attrTag('rdf:type', array('rdf:resource' => 'http://purl.org/info:eu-repo/semantics/humanStartPage'));
		$this->textTag('dcterms:format', 'text/html');
		$this->textTag('dcterms:language', $article->getLanguage());
    $this->closeTag();

    // ----- galleys -------------------------------------------------------------------------------------------------
		foreach ($article->getGalleys() as $galley) {
	    $this->openTag('rdf:Description', array('rdf:about' => $this->makeUrl('article','view', array($article->getBestArticleId(),$galley->getBestGalleyId($this->journal)))));
			$this->attrTag('rdf:type', array('rdf:resource' => 'http://purl.org/info:eu-repo/semantics/article'));
			if ($galley->getFileType()) {
				$this->textTag('dcterms:format', $galley->getFileType());
			}
			if ($galley->getPublicGalleyId()) {
				$this->attrTag('ore:similarTo', array('rdf:resource' => $galley->getPublicGalleyId()));
			}
			$this->textTag('dcterms:created', substr($galley->getDateUploaded(),0,10));
			$this->textTag('dcterms:modified', substr($galley->getDateModified(),0,10));
	    $this->closeTag();
		}

    // ----- supplementary files -------------------------------------------------------------------------------------
		foreach ($article->getSuppFiles() as $suppFile) {
			if ($suppFile->getData('ejme_dans') && $suppFile->getData('ejme_dans') == 2 && $suppFile->getData('ejme_link') != '') {
				$this->openTag('rdf:Description', array('rdf:about' => $suppFile->getData('ejme_link')));
				$this->attrTag('ore:similarTo', array('rdf:resource' => $suppFile->getData('ejme_urn')));
			}
			elseif ($suppFile->getFileId()) {
				$this->openTag('rdf:Description', array('rdf:about' => $this->makeUrl('article','downloadSuppFile', array($article->getBestArticleId(),$suppFile->getBestSuppFileId($this->journal)))));
				if ($suppFile->getPublicSuppFileId()) {
					$this->attrTag('ore:similarTo', array('rdf:resource' => $suppFile->getPublicSuppFileId()));
				}
			}
			else continue;
			$this->attrTag('rdf:type', array('rdf:resource' => 'http://purl.org/info:eu-repo/semantics/other'));
			if ($suppFile->getFileType()) {
				$this->textTag('dcterms:format', $suppFile->getFileType());
			}
			$this->textTag('dcterms:title', $suppFile->getSuppFileTitle());
			if ($suppFile->getSuppFileDescription()) {
				$this->textTag('dcterms:description', $suppFile->getSuppFileDescription());
			}
			if ($suppFile->getSuppFileCreator()) {
				$this->openTag('dcterms:creator');
				$this->openTag('foaf:Person');
				$this->textTag('foaf:name', $suppFile->getSuppFileCreator());
				$this->closeTag();
				$this->closeTag();
			}
			$this->textTag('dcterms:language', $suppFile->getLanguage());			
			$this->textTag('dcterms:created', substr($suppFile->getDateCreated(),0,10));
			$this->closeTag();
		}
    $this->closeTag();  //rdf:RDF
    $this->rem .= "\n";
    return $this->rem;
	}
	
	protected function openTag ($tag, $attr = NULL, $close = FALSE) {  //$attr should be hash array
	  $t = '';
	  if (!is_array($this->tags)) $this->tags = array();
	  $this->tags[] = $tag;
	  $t .= "\n";
    for ($j=count($this->tags); $j>1; $j--) $t .= "\t";
	  $t .= "<" . $tag;
	  if (is_array($attr)) foreach ($attr as $k => $v) $t .= " " . $k . '="' . htmlspecialchars($v) . '"';
	  if ($close) { $t .= " />"; array_pop($this->tags); } else $t .= ">";
	  $this->rem .= $t;
	}

  protected function attrTag ($tag, $attr = NULL) {  //$attr should be hash array
    $this->openTag($tag,$attr,true);
  }
  
	protected function addText ($s) {
	  $this->rem .= htmlspecialchars($s);
	}
	  
  protected function textTag ($tag, $text = NULL) {
	  $t = '';
	  if (!is_array($this->tags)) $this->tags = array();
	  $t .= "\n";
    for ($j=count($this->tags); $j>0; $j--) $t .= "\t";
	  $t .= "<" . $tag . ">";
	  if (isset($text)) $t .= htmlspecialchars($text);
	  $t .= "</" . $tag . ">";
	  $this->rem .= $t;
  }
	
	protected function closeTag ($gotoNextLine = 1) {  //put closing tag on new line?
	  $t = '';
	  if (!is_array($this->tags) || count($this->tags) == 0) return;
	  if ($gotoNextLine) {
	    $t .= "\n";
	    for ($j=count($this->tags); $j>1; $j--) $t .= "\t";
	  }
	  $tag = array_pop($this->tags);
	  $t .= "</" . $tag . ">";
	  $this->rem .= $t;
	}

  protected function makeUrl ($page, $op, $path = NULL) {
    $url = $this->baseurl . '/' . $this->journal->getPath() . '/' . $page . '/' . $op;
    if (is_array($path)) {
      foreach ($path as $v) {
        $url .= '/' . $v;
      }
    }
    return $url;
  }
  
	protected function rdfHeader () {
	  $this->tags[] = 'rdf:RDF';
		$this->rem = <<<EOT
<rdf:RDF 
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" 
	xmlns:ore="http://www.openarchives.org/ore/terms/"
	xmlns:eurepo="http://purl.org/info:eu-repo/semantics/"
	xmlns:dcterms="http://purl.org/dc/terms/"
	xmlns:foaf="http://xmlns.com/foaf/0.1/" >
EOT;
  }
	
}
