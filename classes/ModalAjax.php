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
use HeimrichHannot\Haste\Util\Url;
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
     *
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
     * @param $html     string
     *
     * @throws \Exception
     */
    public function __construct(ModalModel $objModal, array $arrConfig = [], $html = '')
    {
        $this->arrConfig = $arrConfig;
        $this->objModal  = $objModal;
        $this->html      = $html;
    }

    public function show()
    {
        global $objPage;

        $blnAjax  = false;
        $objModal = new Modal($this->objModal, $this->arrConfig);
        $strLocation = html_entity_decode(Request::getGet('location'));
        $objModal->setBackLink($strLocation);

        $strUrl = AjaxAction::removeAjaxParametersFromUrl(Url::removeQueryString(['location'], Request::getInstance()->getRequestUri()));

        if ($objModal->keepGetParams)
        {
            $arrQuery = explode('?', $strLocation);
            $strQuery = is_array($arrQuery) && count($arrQuery) > 1 ? $arrQuery[1] : '';

            if ($strQuery)
            {
                $strUrl = Url::addQueryString($strQuery, $strUrl);
            }
        } else
        {
            $strUrl = Url::removeAllParametersFromUri($strUrl);
        }

        $objResponse = new ResponseSuccess();
        $objResponse->setResult(new ResponseData(\Controller::replaceInsertTags($objModal->generate(), false), ['id' => $this->objModal->id]));

        $objResponse->setUrl($strUrl);

        return $objResponse;
    }
}