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


class ModalLink extends Modal
{
    protected $arrLinkAttributes = array();

    protected $linkText;

    protected $jumpTo;

    public function __construct(\Model $objModel, array $arrConfig)
    {
        parent::__construct($objModel, $arrConfig);

        $this->setLinkText($this->title);
    }

    public function generate()
    {
        $this->strTemplate = 'modallink_' . $this->objConfig->type;

        if (is_array($this->objConfig->link['attributes']))
        {
            $this->setLinkAttributes($this->objConfig->link['attributes']);
        }

        return parent::generate();
    }


    protected function compile()
    {
        $this->Template->href           = ModalController::generateModalUrl($this->objModel->row(), $this->getJumpTo());
        $this->Template->link           = $this->getLinkText();
        $this->Template->linkAttributes = $this->getLinkAttributes(true);
        $this->Template->linkTitle      = $this->title;
    }


    public function setLinkAttributes($arrData, $delimiter = " ")
    {
        // set from string
        if (!is_array($arrData))
        {
            $arrData = trimsplit($delimiter, $arrData);

            if (is_array($arrData))
            {
                foreach (array_keys($this->arrLinkAttributes) as $strKey)
                {
                    $this->arrLinkAttributes[$strKey] = $arrData[$strKey];
                }
            }

            return;
        }

        $this->arrLinkAttributes = $arrData;
    }

    public function getLinkAttributes($blnReturnString = false)
    {
        if (!$blnReturnString)
        {
            return $this->arrLinkAttributes;
        }

        $strAttributes = '';

        foreach (array_keys($this->arrLinkAttributes) as $strKey)
        {
            $strAttributes .= sprintf('%s="%s"', $strKey, $this->arrLinkAttributes[$strKey]);
        }

        return $strAttributes;
    }

    public function addLinkAttribute($key, $value)
    {
        $this->arrLinkAttributes[$key] = $value;
    }

    public function removeLinkAttribute($key)
    {
        unset($this->arrLinkAttributes);
    }

    /**
     * @return mixed
     */
    public function getLinkText()
    {
        return $this->linkText;
    }

    /**
     * @param mixed $linkText
     */
    public function setLinkText($linkText)
    {
        $this->linkText = $linkText;
    }

    /**
     * @return mixed
     */
    public function getJumpTo()
    {
        return $this->jumpTo;
    }

    /**
     * @param mixed $jumpTo
     */
    public function setJumpTo($jumpTo)
    {
        $this->jumpTo = $jumpTo;
    }


}