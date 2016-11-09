<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Modal;

class Hooks
{
	public function parseArticlesHook(&$objTemplate, $arrArticle, $objModule)
	{
		if(!(($objModule->useModal && $arrArticle['source'] == 'default')))
        {
            return false;
        }

		$objJumpTo = \PageModel::findPublishedById($objTemplate->archive->jumpTo);

		if ($objJumpTo === null || !$objJumpTo->linkModal) {
			return false;
		}

		$objModal = ModalModel::findPublishedByIdOrAlias($objJumpTo->modal);
		
		if ($objModal === null) {
			return false;
		}
		
		$objJumpTo = \PageModel::findWithDetails($objJumpTo->id);

		$arrConfig = ModalController::getModalConfig($objModal->current(), $objJumpTo->layout);
		
		$blnAjax = true;
		$blnRedirect = true;

		$objTemplate->link         = ModalController::generateModalUrl($arrArticle, $objTemplate->archive->jumpTo, $blnAjax, $blnRedirect);
		$objTemplate->linkHeadline = ModalController::convertLinkToModalLink($objTemplate->linkHeadline, $objTemplate->link, $arrConfig, $blnRedirect);
		$objTemplate->more         = ModalController::convertLinkToModalLink($objTemplate->more, $objTemplate->link, $arrConfig, $blnRedirect);
	}
	
	public function showDisclaimerHook(HeimrichHannot\Disclaimer\DisclaimerModel $objDisclaimer, $blnAccepted)
	{
		if ($objDisclaimer->source !== 'modal') {
			return;
		}
		
		if ($blnAccepted) {
			return;
		}
		
		if (($objModal = ModalModel::findPublishedByIdOrAlias($objDisclaimer->modal)) === null) {
			return false;
		}
		
		$blnAjax = false;
		$strUrl  = ModalController::generateModalUrl($objModal->row(), $objDisclaimer->jumpTo, $blnAjax);
		
		\Controller::redirect($strUrl);
	}
}