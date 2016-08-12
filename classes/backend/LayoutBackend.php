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


use HeimrichHannot\Modal\ModalController;

class LayoutBackend extends \Backend
{

	public function getModalOptions()
	{
		return ModalController::getModalTypes();
	}
	
}