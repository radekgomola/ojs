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

import('manager.ManagerHandler');
require_once 'classes/simple_html_dom.php';
class MunipressDOIHandler extends ManagerHandler {

//	function munipressDOI($args) {
//		parent::validate();
//		parent::setupTemplate(true);
//
//		import('file.FileManager');
//
//                $articleId = $args[0];
//                $citationDao =& DAORegistry::getDAO('CitationDAO'); /* @var $citationDao CitationDAO */
//                $citationFactory =& $citationDao->getObjectsByAssocId(ASSOC_TYPE_ARTICLE, $articleId);
//                
//                $citace = $this->factory2citace($citationFactory);       
//                $zpracovane = $this->zpracuj_html($this->curl_parsit2doi($citace));
//                
//                $output = $this->vytvor_vystup($zpracovane[0], $zpracovane[1], $zpracovane[2], $zpracovane[3], $zpracovane[4]);
//                
//		$templateMgr = &TemplateManager::getManager();
//		$templateMgr->assign('pageHierarchy', array(array(Request::url(null, 'manager'), 'manager.journalManagement')));
//                $plugin =& Registry::get('plugin');
//                $templateMgr->assign('output', $output);
//                $templateMgr->display($plugin->getTemplatePath() . 'index.tpl');
//		
//	}
        
        function munipressDOI($args) {
		parent::validate();
		parent::setupTemplate(true);

		import('file.FileManager');

//                $articleId = $args[0];
//                $citationDao =& DAORegistry::getDAO('CitationDAO'); /* @var $citationDao CitationDAO */
//                $citationFactory =& $citationDao->getObjectsByAssocId(ASSOC_TYPE_ARTICLE, $articleId);
//                
//                $citace = $this->factory2citace($citationFactory);       
//                $zpracovane = $this->zpracuj_html($this->curl_parsit2doi($citace));
//                
//                $output = $this->vytvor_vystup($zpracovane[0], $zpracovane[1], $zpracovane[2], $zpracovane[3], $zpracovane[4]);
                
		$templateMgr = &TemplateManager::getManager();
		$templateMgr->assign('pageHierarchy', array(array(Request::url(null, 'manager'), 'manager.journalManagement')));
                $plugin =& Registry::get('plugin');
                $templateMgr->assign('output', $output);
                $templateMgr->display($plugin->getTemplatePath() . 'index.tpl');
		
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
            $returner = array();
            $vystup = "";
            $nove = 0;
            $stejne = 0;
            $chybne = 0;
            $zadne = 0;
            
            foreach($html->find('ol') as $element){
                
                $blok_citaci = str_get_html($element);
                $i=1;
                foreach($blok_citaci->find('li') as $citace){
                    $typCitace = $this->zkontroluj_citaci($citace, $i);
                    
                    switch ($typCitace){
                        case 1;
                            $citace->class="nove_doi";
                            $nove++;
                            break;
                        case 2:
                            $citace->class="jine_doi";
                            $chybne++;
                            break;
                        case 3:
                            $citace->class="stejne_doi";
                            $stejne++;
                            break;
                        default:
                            $citace->class="zadne_doi";
                            $zadne++;
                            break;
                    }
                    $vystup .= $citace. "<br />\n";
                    $i++;
                }
            }
            $returner[] = $vystup;
            $returner[] = $nove;
            $returner[] = $stejne;
            $returner[] = $chybne;
            $returner[] = $zadne;
            
            return $returner;
        }
        
        
        function zkontroluj_citaci($citace, $i){
            $returner = 0;
            $id = str_pad($i, 5, "0", STR_PAD_LEFT); 
            $html = str_get_html($citace);
            $retezec = 'span[id=ref-'.$id.']';
            $doi = 'span[id=doi-ref-'.$id.']';
            
            $link = $html->find('a');
            if(isset($link[0]->href)){
                $ref = $html->find($retezec);
                $ref_doi = $html->find($doi);
                if(preg_match("/doi:(\s|)\d\d\.\d\d\d\d\//i", $ref[0])>0) {
                    if(strpos($ref[0],strip_tags($ref_doi[0]))>0){
                        $returner = 3;
                    } else{
                        $returner = 2;
                    }
                } else{
                    $returner = 1;
                }
            }
            return $returner;
        }
        
        
        private function factory2citace($citationFactory){
            $i=0;
            $citation = $citationFactory->next();
            
            $string = $citation->getRawCitation();
            $references = $this->zpracuj_citace($string);
            $i++;
            return $references;
        }
                
        private function zpracuj_citace($string){
            $html = str_get_html($string);
            $citace = "";
            foreach($html->find('p') as $element){
                
                $element = str_replace("&amp;", "&", $element);
                
                $element = $this->clearBr($element);
                $test = $this->zpracuj_html($element);
                $citace .= strip_tags($element). "\n\n";
            }

            return $citace;
        }
        
        private function vytvor_vystup($string, $nove, $stejne, $chybne, $zadne){
            $statistika = "
                <table width=\"600px\">\n
                    <tr><td></td><td>Celkem</td>
                    <tr class=\"nove_doi\"><td>U citace bylo nalezeno nové doi.</td><td>".$nove."</td></tr>\n
                    <tr class=\"jine_doi\"><td>Bylo nalezeno DOI, ale neshoduje se s DOI obsaženém v citaci.</td><td>".$chybne."</td></tr>\n
                    <tr class=\"stejne_doi\"><td>Citace již stejné DOI obsahuje.</td><td>".$stejne."</td></tr>\n
                    <tr class=\"zadne_doi\"><td>U citace nebylo nalezeno doi.</td><td>".$zadne."</td></tr>\n
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
