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


use HeimrichHannot\Ajax\AjaxAction;
use HeimrichHannot\Ajax\Response\ResponseData;
use HeimrichHannot\Ajax\Response\ResponseSuccess;
use HeimrichHannot\Request\Request;

class ModalAjax
{
	/**
	 * The Modal Model
	 *
	 * @var ModalModel
	 */
	protected $objModal;
	
	/**
	 * The Modal Config data
	 * @var array
	 */
	protected $arrConfig;
	
	/**
	 * HTML for the response object
	 *
	 * @var string
	 */
	protected $html;
	
	/**
	 * Get the current action
	 *
	 * @param $objModal ModalModel
	 * @param $html string
	 *
	 * @throws \Exception
	 */
	public function __construct(ModalModel $objModal, array $arrConfig = array(), $html = '')
	{
		$this->arrConfig = $arrConfig;
		$this->objModal = $objModal;
		$this->html = $html;
	}
	
	public function show()
	{
		global $objPage;
		
		$back = \Controller::generateFrontendUrl($objPage->row(), null, null, true);
		$blnAjax = false;
		$objModal = new Modal($this->objModal, $this->arrConfig);
		$objModal->setBackLink($back);
		$objResponse = new ResponseSuccess();
		$objResponse->setResult(new ResponseData($objModal->generate(), array('id' => $this->objModal->id)));
		$objResponse->setUrl(AjaxAction::removeAjaxParametersFromUrl(Request::getInstance()->getRequestUri()));
		return $objResponse;
	}
}