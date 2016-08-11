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


use HeimrichHannot\Ajax\Ajax;
use HeimrichHannot\Ajax\AjaxAction;

class ModalController extends \Controller
{
	/**
	 * Replace modal inserttags
	 *
	 * @param $strTag
	 * @param $blnCache
	 * @param $strCacheTag
	 * @param $flags
	 * @param $tags
	 * @param $arrCache
	 * @param $index
	 * @param $count
	 *
	 * @return string
	 */
	public function replaceModalInsertTags($strTag, $blnCache, $strCacheTag, $flags, $tags, $arrCache, $index, $count)
	{
		$params = preg_split('/::/', $strTag);
		
		if (!is_array($params) || empty($params) || !\HeimrichHannot\Haste\Util\StringUtil::startsWith($params[0], 'modal_')) {
			return false;
		}
		
		switch ($params[0]) {
			case 'modal_link':
				$objModal = ModalModel::findPublishedByIdOrAlias($params[1]);
				
				if ($objModal === null) {
					return '';
				}
				
				return static::generateModalLink($objModal->current(), $params[2], $params[3]);
				break;
			case 'modal_link_open':
				$objModal = ModalModel::findPublishedByIdOrAlias($params[1]);
				
				if ($objModal === null) {
					return '';
				}
				
				$strBuffer = static::generateModalLink($objModal->current(), $params[2], '');
				preg_match('/(?P<start>.*<a[^<]+[^<]+ *[^\/?]>)(?P<stop>.*)/is', $strBuffer, $matches);
				
				if (is_array($matches) && isset($matches['start']) && isset($matches['stop'])) {
					return $matches['start'];
				}
				
				return false;
				break;
			case 'modal_link_close':
				$objModal = ModalModel::findPublishedByIdOrAlias($params[1]);
				
				if ($objModal === null) {
					return '';
				}
				
				$strBuffer = static::generateModalLink($objModal->current(), $params[2], '');
				
				preg_match('/(?P<start>.*<a[^<]+[^<]+ *[^\/?]>)(?P<stop>.*)/is', $strBuffer, $matches);
				
				if (is_array($matches) && isset($matches['start']) && isset($matches['stop'])) {
					return $matches['stop'];
				}
				
				return false;
				break;
			case 'modal_url':
				$objModal = ModalModel::findPublishedByIdOrAlias($params[1]);
				
				if ($objModal === null) {
					return '';
				}
				
				return static::generateModalUrl($objModal->row(), $params[2]);
				break;
		}
	}
	
	/**
	 * Get the default Modal Type
	 *
	 * @param bool $blnReference
	 *
	 * @return mixed|string
	 */
	public function getDefaultModalType($blnReference = false)
	{
		$strReturn = '';
		$arrModals = static::getModalTypes();
		
		if (empty($arrModals)) {
			return $strReturn;
		}
		
		$default   = $arrModals[0];
		$strReturn = $default;
		
		if ($blnReference && $GLOBALS['TL_LANG']['modals'][$default]) {
			$strReturn = $GLOBALS['TL_LANG']['modals'][$default];
		}
		
		return $strReturn;
	}
	
	/**
	 * Get a list of modal types from configuration
	 *
	 * @return array List of modal types
	 */
	public function getModalTypes()
	{
		if (!is_array($GLOBALS['TL_MODALS'])) {
			return array();
		}
		
		return array_keys($GLOBALS['TL_MODALS']);
	}
	
	public function setModalAutoItem($objPage, $objLayout, &$objPageRegular)
	{
		// Set the item from the auto_item parameter
		if (!isset($_GET['modals']) && \Config::get('useAutoItem') && isset($_GET['auto_item'])) {
			\Input::setGet('modals', \Input::get('auto_item'));
		}
		
		// if modal was not found by alias, unset the $_GET Parameter
		if (($objModal = ModalModel::findPublishedByIdOrAlias(\Input::get('modals'))) === null) {
			unset($_GET['modals']);
			
			return false;
		}
		
		$arrConfig = static::getModalConfig($objModal->current(), $objLayout, $objPage);
		
		Ajax::runActiveAction(Modal::MODAL_NAME, 'redirect', new ModalAjax($objModal->current(), $arrConfig));
		Ajax::runActiveAction(Modal::MODAL_NAME, 'show', new ModalAjax($objModal->current(), $arrConfig));
		
		// if modal not found by alias, unset auto_item $_GET Parameter as other modules may look for it
		unset($_GET['auto_item']);
		
		return true;
	}
	
	
	/**
	 * Hook upon PageRegular to register modal and render them
	 *
	 * @param $objPage
	 * @param $objLayout
	 * @param $objPageRegular
	 *
	 * @return void
	 */
	public function generatePageWithModal($objPage, $objLayout, &$objPageRegular)
	{
		// Do not index or cache the page if no modal item has been specified
		if (!\Input::get('modals')) {
			/** @var \PageModel $objPage */
			global $objPage;
			
			$objPage->noSearch = 1;
			$objPage->cache    = 0;
			
			return;
		}
		
		$objModel = ModalModel::findPublishedByIdOrAlias(\Input::get('modals'));
		$blnCheck = true;
		
		if ($objModel === null) {
			$blnCheck = false;
		}
		
		$objModel  = $objModel->current();
		$arrConfig = static::getModalConfig($objModel, $objLayout, $objPage);
		
		if (empty($arrConfig)) {
			$blnCheck = false;
		}
		
		if (!$blnCheck) {
			/** @var \PageError404 $objHandler */
			$objHandler = new $GLOBALS['TL_PTY']['error_404']();
			$objHandler->generate($objPage->id);
		}
		
		$back = \Controller::generateFrontendUrl($objPage->row(), null, null, true);
		
		$objModal = new Modal($objModel, $arrConfig);
		$objModal->setBackLink($back);
		// render modal within main, as it is the most commonly used region and enabled within contao by default
		$strBuffer = $objModal->generate();
		
		$objPageRegular->Template->main .= $strBuffer;
	}
	
	/**
	 * Get the config for a given modal
	 *
	 * @param ModalModel $objModal
	 * @param null       $objLayout
	 *
	 * @return array The configuration array
	 */
	public static function getModalConfig(ModalModel $objModal, $objLayout = null)
	{
		$default   = static::getDefaultModalType();
		$arrTypes  = $GLOBALS['TL_MODALS'];
		$arrConfig = array();
		$strType   = '';
		
		// default config
		if (isset($arrTypes[$default])) {
			$strType = $default;
		}
		
		// overwrite by tl_layout
		if ($objLayout->customModal && isset($arrTypes[$objLayout->modal])) {
			$strType = $objLayout->modal;
		}
		
		// overwrite by tl_modal_archive
		if (($objArchive = $objModal->getRelated('pid')) !== null && $objArchive->customModal && isset($arrTypes[$objArchive->modal])) {
			$strType = $objArchive->modal;
		}
		
		// overwrite by tl_modal
		if ($objModal->customModal && isset($arrTypes[$objModal->modal])) {
			$strType = $objModal->modal;
		}
		
		if (is_array($arrTypes[$strType])) {
			$arrConfig = array_merge(array('type' => $strType), $arrTypes[$strType]);
		}
		
		return $arrConfig;
	}
	
	
	/**
	 * Generate a modal link depending on the current rewriteURL setting
	 *
	 * @param \Model $objModal
	 * @param mixed  $jumpTo
	 * @param mixed  $linkText
	 *
	 * @return string
	 */
	public static function generateModalLink(\Model $objModal, $jumpTo = null, $linkText = null)
	{
		$arrConfig = static::getModalConfig($objModal);
		
		if (!is_array($arrConfig)) {
			return '';
		}
		
		$objLink = new ModalLink($objModal, $arrConfig);
		$objLink->setJumpTo($jumpTo);
		
		if ($linkText !== null) {
			$objLink->setLinkText($linkText);
		}
		
		return $objLink->generate();
	}
	
	
	/**
	 * Generate an modal URL depending on the current rewriteURL setting
	 *
	 * @param array $arrRow An array of modal parameters
	 * @param mixed $jumpTo An optional jumpTo Page
	 * @param boolean $blnAjax Determine if ajax request, can be overwritten
	 *
	 * @return string An URL that can be used in the front end
	 */
	public static function generateModalUrl(array $arrRow, $jumpTo = null, &$blnAjax = true)
	{
		global $objPage;
		$strUrl    = '';
		$strParams = ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/' : '/modals/');
		$strParams .= ((!\Config::get('disableAlias') && $arrRow['alias'] != '') ? $arrRow['alias'] : $arrRow['id']);
		
		if ($jumpTo !== null && ($objJumpTo = \PageModel::findPublishedByIdOrAlias($jumpTo)) !== null)
		{
			$strUrl = $objJumpTo->current()->getFrontendUrl($strParams);
		}
		else if ($objPage !== null)
		{
			$strUrl = $objPage->getFrontendUrl($strParams);
		}

		if($blnAjax)
		{
			// trigger ajax action if jumpto is same page
			if($objJumpTo === null && $objPage->id != $objJumpTo->id)
			{
				$strUrl = AjaxAction::generateUrl(Modal::MODAL_NAME, 'show', array(), false, $strUrl);
			}
			// force redirect to new page and set ajax to false
			else
			{
				$blnAjax = false;
			}
		}
		
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateModalUrl']) && is_array($GLOBALS['TL_HOOKS']['generateModalUrl'])) {
			foreach ($GLOBALS['TL_HOOKS']['generateModalUrl'] as $callback) {
				$strUrl = static::importStatic($callback[0])->{$callback[1]}($arrRow, $strParams, $strUrl, $blnAjax);
			}
		}
		
		return $strUrl;
	}
	
	/**
	 * Add dynamic modal types javascript
	 *
	 * @param $strBuffer
	 *
	 * @return mixed
	 */
	public function hookReplaceDynamicScriptTags($strBuffer)
	{
		global $objPage;
		
		$arrConfigurations = $GLOBALS['TL_MODALS'];
		
		if (!is_array($arrConfigurations) || $objPage === null) {
			return $strBuffer;
		}
		
		foreach ($arrConfigurations as $arrConfig) {
			if (!is_array($arrConfig['js'])) {
				continue;
			}
			
			foreach ($arrConfig['js'] as $strFile) {
				if (!file_exists(TL_ROOT . '/' . ltrim($strFile, '/'))) {
					continue;
				}
				
				$GLOBALS['TL_JAVASCRIPT'][standardize($strFile)] = $strFile . '|static';
			}
			
			
		}
		
		return $strBuffer;
	}
	
}