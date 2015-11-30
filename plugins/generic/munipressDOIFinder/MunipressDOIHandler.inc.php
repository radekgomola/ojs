<?php

/**
 * @file MunipressDOIFinderPlugin.inc.php
 *
 * Copyright (c) 20015 Munipress
 *
 * @class MunipressDOIFinderPlugin
 * @ingroup plugins_generic_munipressdoifinder
 *
 */

import('editor.EditorHandler');

// Import JSON class for use with all AJAX requests.
import('lib.pkp.classes.core.JSONMessage');

require_once 'classes/simple_html_dom.php';
class MunipressDOIHandler extends EditorHandler {
    
        public $_nove = 0;
        public $_chybne = 0;
        public $_stejne = 0;
        public $_zadne = 0;

	function munipressDOI($args) {
		parent::validate();
		parent::setupTemplate(true);
                $templateMgr = &TemplateManager::getManager();
                
		import('file.FileManager');
                $articleId = $args[0];
                $templateMgr->assign('pageHierarchy', array(array(Request::url(null, 'editor'), 'editor.metadataEdit')));
                $plugin =& Registry::get('plugin');
                $templateMgr->assign('articleId', $articleId);
                
		$templateMgr->display($plugin->getTemplatePath() . 'index.tpl');
	}

    
        function munipressfinder(){
            $articleId = Request::getUserVar('articleId');
            $citationDao =& DAORegistry::getDAO('CitationDAO'); /* @var $citationDao CitationDAO */
            $citationFactory =& $citationDao->getObjectsByAssocId(ASSOC_TYPE_ARTICLE, $articleId);

            $citace = $this->factory2citace($citationFactory);  
            $zpracovane = "";
            foreach($citace as $cit){
                $zpracovane .= $this->zpracuj_html($this->curl_parsit2doi($cit));
            }
//            $returner = $this->vytvor_vystup($zpracovane[0], $zpracovane[1], $zpracovane[2], $zpracovane[3], $zpracovane[4]);
            
            $returner = $this->vytvor_vystup($zpracovane);
            
            $json = new JSONMessage(true, $returner);
            return $json->getString();
            
        }
        function munipressDOIinfo($args) {
                parent::validate();
		parent::setupTemplate(true);
                $templateMgr = &TemplateManager::getManager();
                $plugin =& Registry::get('plugin');
                
                $templateMgr->display($plugin->getTemplatePath() . '/templates/munipressDOIinfo.tpl');            
        }
        
        function curl_parsit2doi($citace){
                if (!function_exists('curl_init')){
                    die('Sorry cURL is not installed!');
                }
                $url = 'https://kic-internal.ics.muni.cz/apps/parscit-to-doi/';

                $data = array('mode' => 'ref_strings', 'ref_strings' => $citace);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_TIMEOUT, 3000);

                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
        }
        
        function zpracuj_html($string){
            $html = str_get_html($string);
            $vystup = "";
            foreach($html->find('ol') as $element){
                
                $blok_citaci = str_get_html($element);
                $i=1;
                foreach($blok_citaci->find('li') as $citace){
                    $typCitace = $this->zkontroluj_citaci($citace, $i);
                    
                    switch ($typCitace){
                        case 1;
                            $citace->class="nove_doi";
                            $this->_nove++;
                            break;
                        case 2:
                            $citace->class="jine_doi";
                            $this->_chybne++;
                            break;
                        case 3:
                            $citace->class="stejne_doi";
                            $this->_stejne++;
                            break;
                        default:
                            $citace->class="zadne_doi";
                            $this->_zadne++;
                            break;
                    }
                    $id = str_pad($i, 5, "0", STR_PAD_LEFT); 
                    $doi = 'span[id=doi-ref-'.$id.']';
                    $html = str_get_html($citace);
                    $ref_doi = $html->find($doi);
                    
                     if (preg_match("/^.*(10\.[^\s]+).*$/i", $ref_doi[0])>0){
                        $apa_citace = "APA formát: <a href='http://dx.doi.org/".strip_tags($ref_doi[0])."' target='_blank'><span id=\"doi-ref-".$id."\" style=\"color: green\">http://dx.doi.org/".strip_tags($ref_doi[0])."</span></a>";
                        $citace->innertext = $citace->innertext ."<br />\n". $apa_citace;
                     }
                    $vystup .= $citace."<br />\n";
                    $i++;
                }
            }
            $returner = $vystup;

            return $returner;
        }
        
        
        function zkontroluj_citaci($citace, $i){
            //echo $citace;
            $returner = 0;
            $id = str_pad($i, 5, "0", STR_PAD_LEFT); 
            $html = str_get_html($citace);
            $retezec = 'span[id=ref-'.$id.']';
            $doi = 'span[id=doi-ref-'.$id.']';
            
            $link = $html->find('a');
            $ref = $html->find($retezec);
            $ref_doi = $html->find($doi);
            if(preg_match("/^.*doi(?:(?:\s*[:-]?\s*)|(?:\s+))(10\.[^\s]+).*$/i", $ref[0])>0 || preg_match("/^.*(?:http:\/\/)?dx.doi.org\/(10\.[^\s]+).*$/i", $ref[0])>0) {
                $ref[0] = str_replace("&amp;", "&", $ref[0]);
                if(strpos($ref[0],strip_tags($ref_doi[0]))>0){
                    $returner = 3;
                } else{
                    $returner = 2;
                }
            } elseif (isset($link[0]->href)){
                $returner = 1;
            }
            return $returner;
        }
        
        
        private function factory2citace($citationFactory){
            $citation = $citationFactory->next();
            
            $string = $citation->getRawCitation();
            $references = $this->zpracuj_citace($string);

            return $references;
        }
                
        private function zpracuj_citace($string){
            $html = str_get_html($string);
            $citace = "";
            $i = 0;
            $returner = array();
            foreach ($html->find('ul') as $blok) {
                $blok_citaci = str_get_html($blok);
                foreach($blok_citaci->find('li') as $element){

                    $element = str_replace("&amp;", "&", $element);

                    $element = $this->clearBr($element);
    //                $test = $this->zpracuj_html($element);
                    $citace .= strip_tags($element). "\n\n";
                    $i++;
                    if($i >=80){
                        $returner[] = $citace;
                        $citace = "";
                        $i = 0;
                    }
                }
            }
            foreach ($html->find('ol') as $blok) {
                $blok_citaci = str_get_html($blok);
                foreach($blok_citaci->find('li') as $element){

                    $element = str_replace("&amp;", "&", $element);

                    $element = $this->clearBr($element);
    //                $test = $this->zpracuj_html($element);
                    $citace .= strip_tags($element). "\n\n";
                    $i++;
                    if($i >=80){
                        $returner[] = $citace;
                        $citace = "";
                        $i = 0;
                    }
                }
            }
            foreach($html->find('p') as $element){

                $element = str_replace("&amp;", "&", $element);

                $element = $this->clearBr($element);
//                $test = $this->zpracuj_html($element);
                $citace .= strip_tags($element). "\n\n";
                $i++;
                if($i >=80){
                    $returner[] = $citace;
                    $citace = "";
                    $i = 0;
                }
            }
            

            if(!empty($citace)) $returner[] = $citace;
            return $returner;
        }
        
         private function vytvor_vystup($string){
            $statistika = "
                <table width=\"600px\" class=\"statistika_doi\">\n
                    <tr><td></td><td>Celkem</td>
                    <tr class=\"nove_doi\"><td>U citace bylo nalezeno nové doi.</td><td>".$this->_nove."</td></tr>\n
                    <tr class=\"jine_doi\"><td>Bylo nalezeno DOI, ale neshoduje se s DOI obsaženém v citaci.</td><td>".$this->_chybne."</td></tr>\n
                    <tr class=\"stejne_doi\"><td>Citace již stejné DOI obsahuje.</td><td>".$this->_stejne."</td></tr>\n
                    <tr class=\"zadne_doi\"><td>U citace nebylo nalezeno doi.</td><td>".$this->_zadne."</td></tr>\n
                </table>\n";
            $citace = "<ol>\n".$string."</ol>\n";
            $returner = $statistika."<hr />".$citace;
            
            return $returner;
        }
        
        function br2nl($string)
        {
            return preg_replace('#<br\s*?/?>#i', "\n", $string);  
        } 
        
        function clearBr($string){
            return preg_replace('#<br\s*?/?>#i', " ", $string);
        }
}
?>
