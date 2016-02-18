<?php

/**
 * @file ReviewPdfHandler.inc.php
 *
 * Copyright (c) 20015 Munipress
 *
 * @class MunipressDOIFinderPlugin
 * @ingroup plugins_generic_munipressdoifinder
 *
 */

// Import JSON class for use with all AJAX requests.
import('lib.pkp.classes.core.JSONMessage');

import('manager.ManagerHandler');
import('classes.tcpdf.tcpdf.Tcpdf');

class ReviewPdfHandler extends ManagerHandler {
    
        public $_nove = 0;
        public $_chybne = 0;
        public $_stejne = 0;
        public $_zadne = 0;
        
        function reviewPdfGenerator($args) {
		parent::validate();
		parent::setupTemplate(true);
                $templateMgr = &TemplateManager::getManager();
                
                $reviewFormId = isset($args[0]) ? (int)$args[0] : null;
		// create new PDF document
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetPrintHeader(false);
                
                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, 12, PDF_MARGIN_RIGHT);
                
                $pdf->SetHeaderMargin(10);               
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                // set some language-dependent strings (optional)
                if (@file_exists(-'/lang/eng.php')) {
                    require_once(dirname(__FILE__).'/lang/eng.php');
                    $pdf->setLanguageArray($l);
                }

                // ---------------------------------------------------------

                // IMPORTANT: disable font subsetting to allow users editing the document
                $pdf->setFontSubsetting(false);

                $pdf->AddFont('Courier','',dirname(__FILE__).'/fonts/courier.php'); 
                $pdf->AddFont('Courier','B',dirname(__FILE__).'/fonts/courier.php'); 
                
                // set font
                $pdf->SetFont('FreeSans', '', 10, '', false);
                $pdf->rollbackTransaction(true);
                // add a page
                $pdf->AddPage();

                // create some HTML content
                $html = $this->vytvor_vystup($reviewFormId);
                // output the HTML content
//echo $html;
                $pdf->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true);

                // reset pointer to the last page
                $pdf->lastPage();

                // ---------------------------------------------------------

                //Close and output PDF document
                $nazev = "reviewForm-".$reviewFormId;
                $pdf->Output($nazev, 'D');	
	}

         private function vytvor_vystup($reviewFormId){
            $returner = "";

            $journal =& Request::getJournal();
            $reviewFormDao =& DAORegistry::getDAO('ReviewFormDAO');
            $reviewForm =& $reviewFormDao->getReviewForm($reviewFormId, ASSOC_TYPE_JOURNAL, $journal->getId());
            $reviewFormElementDao =& DAORegistry::getDAO('ReviewFormElementDAO');
            $reviewFormElements =& $reviewFormElementDao->getReviewFormElements($reviewFormId);

            if (!isset($reviewForm)) {
                    Request::redirect(null, null, 'reviewForms');
            }

            $returner .= "                    
            <div id=\"reviewFormPreview\">
            <h3>".$reviewForm->getLocalizedTitle()."</h3>
            <p>".$reviewForm->getLocalizedDescription()."</p>
            <form id=\"saveReviewFormResponse\" action=\"#\" enctype=\"multipart/form-data\">";
            foreach($reviewFormElements as $reviewFormElements => $reviewFormElement) {
                    $returner .= "<h4>".$reviewFormElement->getLocalizedQuestion();
                    if ($reviewFormElement->getRequired()){
                        $returner .="*";
                    }
                    $returner .= "</h4>";
                    $returner .= "<p class=\"reviewFormElement-".$reviewFormElement->getId()."\">";
                    if ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_SMALL_TEXT_FIELD){                        
                        $returner .= "<label for=\"text-".$reviewFormElement->getId()."\"> </label>";
                        $returner .= "<table><tr><td style=\"border: 1px solid #000; width: 62px;\">";
                        $returner .= "<input type=\"text\" size=\"10\" maxlength=\"40\" class=\"textField\" value=\" \" name=\"text-".$reviewFormElement->getId()."\" />";
                        $returner .= "</td></tr></table>";
                    } elseif ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_TEXT_FIELD) {
                        $returner .= "<label for=\"text-".$reviewFormElement->getId()."\"> </label>";
                        $returner .= "<table><tr><td style=\"border: 1px solid #000; width: 250px;\">";
                        $returner .= "<input type=\"text\" size=\"40\" maxlength=\"120\" class=\"textField\" value=\" \" name=\"text-".$reviewFormElement->getId()."\" style=\"border: 1px solid #000;\" />";
                        $returner .= "</td></tr></table>";
                    } elseif ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_TEXTAREA) {
                        $returner .= "<label for=\"text-".$reviewFormElement->getId()."\"> </label>";
                        $returner .= "<table><tr><td style=\"border: 1px solid #000; width: 375px;\">";
                        $returner .= "<textarea rows=\"4\" cols=\"60\" class=\"textArea\" name=\"text-".$reviewFormElement->getId()."\" style=\"width:500px\"></textarea><br /><br /><br />";
                        $returner .= "</td></tr></table>";
                    } elseif ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_CHECKBOXES) {
                        $possibleResponses = $reviewFormElement->getLocalizedPossibleResponses();
                        $iterator = 1;
                        foreach ($possibleResponses as $responseId => $responseItem) {
                            $returner .= "<input id=\"check-".trim($responseId)."\" name=\"check-".$reviewFormElement->getId()."-".$iterator."\" type=\"checkbox\" value=\"".$iterator++."\" />";
                            $returner .= "<label for=\"check-".trim($responseId)."\"> ".$responseItem['content']."</label>";
                            $returner .= "<br />";
                        }
                    } elseif ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_RADIO_BUTTONS) {
                        $possibleResponses = $reviewFormElement->getLocalizedPossibleResponses();
                        $iterator = 1;
                        foreach ($possibleResponses as $responseId => $responseItem) {
                            $returner .= "<input id=\"radio-".trim($responseId)."\" name=\"radio-".$reviewFormElement->getId()."\" type=\"radio\" value=\"".$iterator++."\"/>";
                            $returner .= "<label for=\"radio-".trim($responseId)."\"> ".$responseItem['content']."</label>";
                            $returner .= "<br />";
                        }
                    } elseif ($reviewFormElement->getElementType() == REVIEW_FORM_ELEMENT_TYPE_DROP_DOWN_BOX) {
                        $returner .= "<label for=\"select-".$reviewFormElement->getId()."\"> </label>";
                        $returner .= "<select class=\"selectMenu\" name=\"select-".$reviewFormElement->getId()."\">";
                        $possibleResponses = $reviewFormElement->getLocalizedPossibleResponses();
                        $iterator = 1;
                        foreach ($possibleResponses as $responseId => $responseItem) {
                            $returner .= "<option value=\"".$iterator++."\">".$responseItem['content']."</option>";
                        }
                        $returner .= "</select>";
                    }
                    $returner .= "</p>";
            }
            $returner .= "</form>";
            $returner .= "</div>";
            $returner .= "<p><span class=\"formRequired\">".__('common.requiredField')."</span></p>";
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
