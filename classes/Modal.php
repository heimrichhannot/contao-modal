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


class Modal extends \Frontend
{
	const MODAL_NAME = 'modal';
	
	protected $objModel;

	protected $objConfig;

	protected $backLink;

	protected $strTemplate;

	/**
	 * Current record
	 * @var array
	 */
	protected $arrData = array();

	public function __construct(\Model $objModel, array $arrConfig)
	{
		$this->objModel = $objModel;
		$this->objConfig = (object) $arrConfig;
		$this->arrData = $objModel->row();

		$arrHeadline = deserialize($objModel->headline);
		$this->headline = is_array($arrHeadline) ? $arrHeadline['value'] : $arrHeadline;
		$this->hl = is_array($arrHeadline) ? $arrHeadline['unit'] : 'h1';
		$this->strTemplate = $this->objConfig->template;
	}

	public function generate()
	{
		global $objPage;
		
		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->Template->setData($this->arrData);
		
		$this->Template->title = ''; // title should be empty by default, use headline or pageTitle instead

		$arrClasses = array();

		if($this->objConfig->activeClass)
		{
			$arrClasses[] = $this->objConfig->activeClass;
		}
		
		if ($this->Template->headline == '')
		{
			$this->Template->headline = $this->headline;
		}

		if ($this->Template->hl == '')
		{
			$this->Template->hl = $this->hl;
		}

		$this->Template->class = implode(' ', $arrClasses);
		$this->Template->back = $this->getBackLink();
		$this->Template->redirectBack = $this->getRedirectBack();

		$this->compile();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['generateModal']) && is_array($GLOBALS['TL_HOOKS']['generateModal']))
		{
			foreach ($GLOBALS['TL_HOOKS']['generateModal'] as $callback)
			{
				static::importStatic($callback[0])->{$callback[1]}($this->Template, $this->objModel, $this->objConfig, $this);
			}
		}

		return \Controller::replaceInsertTags($this->Template->parse());
	}

	protected function compile()
	{
		if($this->objConfig->header)
		{
			$this->Template->showHeader = true;
		}

		$id = $this->id;

		$this->Template->body = function () use ($id)
		{
			$strText = '';
			$objElement = \ContentModel::findPublishedByPidAndTable($id, 'tl_modal');

			if ($objElement !== null)
			{
				while ($objElement->next())
				{
					$strContent = $this->getContentElement($objElement->current());
					
					// HOOK: add custom logic
					if (isset($GLOBALS['TL_HOOKS']['getModalContentElement']) && is_array($GLOBALS['TL_HOOKS']['getModalContentElement']))
					{
						foreach ($GLOBALS['TL_HOOKS']['getModalContentElement'] as $callback)
						{
							$strContent = static::importStatic($callback[0])->{$callback[1]}($objElement->current(), $strContent, $this->Template, $this->objModel, $this->objConfig, $this);
						}
					}
					
					$strText .= $strContent;
				}
			}

			return $strText;
		};

		$this->Template->hasBody = (\ContentModel::countPublishedByPidAndTable($this->id, 'tl_modal') > 0);

		if($this->objConfig->footer && $this->addFooter)
		{
			$this->Template->showFooter = true;
		}
	}

	/**
	 * Set an object property
	 *
	 * @param string $strKey
	 * @param mixed  $varValue
	 */
	public function __set($strKey, $varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	/**
	 * Return an object property
	 *
	 * @param string $strKey
	 *
	 * @return mixed
	 */
	public function __get($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			return $this->arrData[$strKey];
		}

		return parent::__get($strKey);
	}


	/**
	 * Check whether a property is set
	 *
	 * @param string $strKey
	 *
	 * @return boolean
	 */
	public function __isset($strKey)
	{
		return isset($this->arrData[$strKey]);
	}

	public function setBackLink($varValue)
	{
		$this->backLink = $varValue;
	}

	public function getBackLink()
	{
		return $this->backLink;
	}
	
	public function getRedirectBack()
	{
		
	}

}