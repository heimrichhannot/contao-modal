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


use HeimrichHannot\Disclaimer\DisclaimerModel;

class Hooks
{

	public function showDisclaimerHook(DisclaimerModel $objDisclaimer, $blnAccepted)
	{
		if($objDisclaimer->source !== 'modal')
		{
			return;
		}
		
		if($blnAccepted)
		{
			return;
		}
		
		if(($objModal = ModalModel::findPublishedByIdOrAlias($objDisclaimer->modal)) === null)
		{
			return false;
		}
		
		$blnAjax = false;
		$strUrl = ModalController::generateModalUrl($objModal->row(), $objDisclaimer->jumpTo, $blnAjax);
		
		\Controller::redirect($strUrl);
	}
}