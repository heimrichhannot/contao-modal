<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Modal\Elements;


use HeimrichHannot\Modal\ModalController;
use HeimrichHannot\Modal\ModalModel;

class Teaser extends ModalController
{



	/**
	 * @param \HeimrichHannot\Teaser\ContentLinkTeaser $objElement
	 * @param bool $blnShowMore
	 *
	 * @return bool true if teaser modal link is valid
 	 */
	public function generateModalTeaserLink(&$objElement, $blnShowMore)
	{
		if($objElement->source != 'modal' && !$objElement->modal)
		{
			return $blnShowMore;
		}

		$objModal = ModalModel::findByPk($objElement->modal);

		if($objModal === null)
		{
			return false;
		}

		$arrConfig = static::getModalConfig($objModal);

		$objElement->setHref(ModalController::generateModalUrl($objModal->row(), $objElement->jumpTo));
		$objElement->setTitle($objModal->title);

		if(is_array($arrConfig['link']['attributes']))
		{
			$objElement->setLinkAttributes($arrConfig['link']['attributes']);
		}


		return true;
	}

}