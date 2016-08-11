<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\Modal\Backend;


use HeimrichHannot\Disclaimer\DisclaimerModel;
use HeimrichHannot\Modal\ModalModel;

class Disclaimer extends \Backend
{
	
	public function modifyPalette(\DataContainer $dc)
	{
		$objDisclaimer = DisclaimerModel::findByPk($dc->id);
		
		if($objDisclaimer === null || $objDisclaimer->source != 'modal')
		{
			return false;
		}
		
		$GLOBALS['TL_DCA']['tl_disclaimer']['fields']['jumpTo']['label'] = &$GLOBALS['TL_LANG']['tl_disclaimer']['modalJumpTo'];
		$GLOBALS['TL_DCA']['tl_disclaimer']['fields']['jumpTo']['eval']['mandatory'] = false;
		$GLOBALS['TL_DCA']['tl_disclaimer']['fields']['jumpTo']['eval']['tl_class'] = 'clr w50';
		
	}
	
	/**
	 * Add the source options depending on the allowed fields
	 *
	 * @param array           $arrOptions
	 * @param  \DataContainer $dc
	 *
	 * @return array
	 */
	public function addSourceOptions(array $arrOptions = array(), \DataContainer $dc)
	{
		if (\BackendUser::getInstance()->isAdmin)
		{
			$arrOptions[] = 'modal';
			
			return $arrOptions;
		}
		
		// Add the "modal" option
		if (\BackendUser::getInstance()->hasAccess('tl_disclaimer::modal', 'alexf'))
		{
			$arrOptions[] = 'modal';
		}
		
		return $arrOptions;
	}
	
	/**
	 * Return all modals grouped by archive
	 *
	 * @param  \DataContainer $dc
	 *
	 * @return array
	 */
	public function getModalOptions(\DataContainer $dc)
	{
		$arrOptions = array();
		
		$objModal = ModalModel::findAll();
		
		if($objModal === null)
		{
			return $arrOptions;
		}
		
		while($objModal->next())
		{
			if(($objArchive = $objModal->getRelated('pid')) === null)
			{
				continue;
			}
			
			$arrOptions[$objArchive->title][$objModal->id] = $objModal->title;
		}
		
		return $arrOptions;
	}
	
}