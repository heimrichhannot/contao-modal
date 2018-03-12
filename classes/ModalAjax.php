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


use Contao\LayoutModel;
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
     * @var LayoutModel
     */
    protected $objLayout;

    /**
     * ModalAjax constructor.
     * @param ModalModel $objModal
     * @param array $arrConfig
     * @param LayoutModel $objLayout
     * @param string $html
     */
    public function __construct(ModalModel $objModal, array $arrConfig = [], LayoutModel $objLayout, $html = '')
    {
        $this->arrConfig = $arrConfig;
        $this->objModal  = $objModal;
        $this->objLayout = $objLayout;
        $this->html      = $html;
    }

    public function show()
    {
        $objModal    = new Modal($this->objModal, $this->arrConfig, $this->objLayout);
        $strLocation = html_entity_decode(Request::getGet('location'));
        $objModal->setBackLink($strLocation);

        $strUrl = AjaxAction::removeAjaxParametersFromUrl(Url::removeQueryString(['location'], Request::getInstance()->getRequestUri()));

        if ($objModal->keepGetParams) {
            $arrQuery = explode('?', $strLocation);
            $strQuery = is_array($arrQuery) && count($arrQuery) > 1 ? $arrQuery[1] : '';

            if ($strQuery) {
                $strUrl = Url::addQueryString($strQuery, $strUrl);
            }
        } else {
            $strUrl = Url::removeAllParametersFromUri($strUrl);
        }

        $objResponse = new ResponseSuccess();
        $objResponse->setResult(new ResponseData(\Controller::replaceInsertTags($objModal->generate(), false), ['id' => $this->objModal->id]));

        $objResponse->setUrl($strUrl);

        return $objResponse;
    }
}