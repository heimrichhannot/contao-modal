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


use HeimrichHannot\Modal\ModalModel;

class PageBackend extends \Backend
{
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