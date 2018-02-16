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
use HeimrichHannot\Ajax\Response\Response404;
use HeimrichHannot\Haste\Util\Url;
use HeimrichHannot\Request\Request;

class ModalController extends \Controller
{
    private static $modalCache;

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
    public function replaceModalInsertTags($strTag, $blnCache, $strCacheTag, $flags, $tags, &$arrCache, $index, $count)
    {
        $params = preg_split('/::/', $strTag);

        if (!is_array($params) || empty($params) || !\HeimrichHannot\Haste\Util\StringUtil::startsWith($params[0], 'modal_')) {
            return false;
        }

        switch ($params[0]) {
            case 'modal_link':
                if (null === ($objModal = $this->getModalModelFromCache($params[1], $blnCache))) {
                    return '';
                }

                $arrRowOverride = null;

                // set auto_item
                if (isset($params[4])) {
                    $arrRowOverride = is_numeric($params[4]) ? ['id' => $params[4]] : ['alias' => $params[4]];
                }

                $arrCache[$strTag] = static::generateModalLink($objModal->current(), $params[2], $params[3], $arrRowOverride);
                return $arrCache[$strTag];
                break;
            case 'modal_link_open':
                if (null === ($objModal = $this->getModalModelFromCache($params[1], $blnCache))) {
                    return '';
                }

                $arrRowOverride = null;

                // set auto_item
                if (isset($params[3])) {
                    $arrRowOverride = is_numeric($params[3]) ? ['id' => $params[3]] : ['alias' => $params[3]];
                }

                $strBuffer = static::generateModalLink($objModal->current(), $params[2], '', $arrRowOverride);
                preg_match('/(?P<start>.*<a[^<]+[^<]+ *[^\/?]>)(?P<stop>.*)/is', $strBuffer, $matches);

                if (is_array($matches) && isset($matches['start']) && isset($matches['stop'])) {
                    $arrCache[$strTag] = $matches['start'];
                    return $arrCache[$strTag];
                }

                return false;
                break;
            case 'modal_link_close':
                if (null === ($objModal = $this->getModalModelFromCache($params[1], $blnCache))) {
                    return '';
                }

                $strBuffer = static::generateModalLink($objModal->current(), $params[2], '');

                preg_match('/(?P<start>.*<a[^<]+[^<]+ *[^\/?]>)(?P<stop>.*)/is', $strBuffer, $matches);

                if (is_array($matches) && isset($matches['start']) && isset($matches['stop'])) {
                    $arrCache[$strTag] = $matches['stop'];
                    return $arrCache[$strTag];
                }

                return false;
                break;
            case 'modal_url':
                if (null === ($objModal = $this->getModalModelFromCache($params[1], $blnCache))) {
                    return '';
                }

                static::$modalCache[$params[1]] = $objModal;

                $arrRow = $objModal->row();

                // set auto_item
                if (isset($params[3])) {
                    $arrRow = is_numeric($params[3]) ? ['id' => $params[3]] : ['alias' => $params[3]];
                }

                $arrCache[$strTag] = static::generateModalUrl($arrRow, $params[2]);
                return $arrCache[$strTag];
                break;
        }
    }

    /**
     * Get the cached modal model
     *
     * @param $id
     * @param bool $blnCache
     * @return ModalModel|null
     */
    protected function getModalModelFromCache($id, $blnCache = true)
    {
        $id = (int)$id;

        if (!isset(static::$modalCache[$id]) || !$blnCache) {
            static::$modalCache[$id] = ModalModel::findPublishedByIdOrAlias($id);
            return static::$modalCache[$id];
        }

        return static::$modalCache[$id];
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
            return [];
        }

        return array_keys($GLOBALS['TL_MODALS']);
    }

    public function setModalAutoItem($objPage, $objLayout, &$objPageRegular)
    {
        // Set the item from the auto_item parameter
        if (!isset($_GET['modals']) && \Config::get('useAutoItem') && isset($_GET['auto_item'])) {
            \Input::setGet('modals', \Input::get('auto_item'));
        }

        $blnForwardAutoItem = false;
        $objModal           = ModalModel::findPublishedByIdOrAliasWithoutLinkedPage(\Input::get('modals'));

        if ($objModal === null && $objPage->linkModal) {
            $objModal = ModalModel::findPublishedByIdOrAlias($objPage->modal);

            // only forward auto item if modal is found and auto_item not same as modal alias (otherwise for example ModuleNewsReader will generate 404)
            if ($objModal !== null) {

                $blnForwardAutoItem = true;

                if ($objModal->alias == $_GET['auto_item']) {
                    $blnForwardAutoItem = false;

                    if ($objModal->autoItemMode) {
                        if (Ajax::isRelated(\HeimrichHannot\Modal\Modal::MODAL_NAME)) {
                            $objResponse = new Response404('No related entity within modal found.');
                            $objResponse->output();
                        }

                        /** @var \PageError404 $objHandler */
                        $objHandler = new $GLOBALS['TL_PTY']['error_404']();
                        $objHandler->generate($objPage->id);
                    }
                }
            }
        }

        // if modal was not found by alias, unset the $_GET Parameter
        if ($objModal === null) {
            unset($_GET['modals']);

            return false;
        }

        // if modal not found by alias, unset auto_item $_GET Parameter as other modules may look for it
        if (!$blnForwardAutoItem) {
            unset($_GET['auto_item']);
        }

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
        // Do not handle the page if no modal item has been specified or page is no regular
        if (!\Input::get('modals') || $objPage->type != 'regular') {
            return;
        }

        $objModel = ModalModel::findPublishedByIdOrAliasWithoutLinkedPage(\Input::get('modals'));

        if ($objModel === null && $objPage->linkModal) {
            $objModel = ModalModel::findPublishedByIdOrAlias($objPage->modal);
        }

        $blnCheck = true;

        if ($objModel === null) {
            $blnCheck = false;
        }

        $objModel = $objModel->current();

        $arrConfig = static::getModalConfig($objModel, $objLayout, $objPage);

        Ajax::runActiveAction(Modal::MODAL_NAME, 'show', new ModalAjax($objModel->current(), $arrConfig));

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

        if ($objModal->keepGetParams) {
            $back = Url::addQueryString(Request::getInstance()->getQueryString(), $back);
        }

        $objModal->setBackLink($back);

        // render modal within main, as it is the most commonly used region and enabled within contao by default
        $strBuffer = $objModal->generate();

        $objPageRegular->Template->main .= $strBuffer;
    }

    /**
     * Get the config for a given modal
     *
     * @param ModalModel $objModal
     * @param null $objLayout
     *
     * @return array The configuration array
     */
    public static function getModalConfig(ModalModel $objModal, $objLayout = null)
    {
        $default   = static::getDefaultModalType();
        $arrTypes  = $GLOBALS['TL_MODALS'];
        $arrConfig = [];
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
            $arrConfig = array_merge(['type' => $strType], $arrTypes[$strType]);
        }

        return $arrConfig;
    }


    /**
     * Generate a modal link depending on the current rewriteURL setting
     *
     * @param \Model $objModal
     * @param mixed $jumpTo
     * @param mixed $linkText
     *
     * @return string
     */
    public static function generateModalLink(ModalModel $objModal, $jumpTo = null, $linkText = null, $arrRowOverride = null)
    {
        $arrConfig = static::getModalConfig($objModal, null);

        if (!is_array($arrConfig)) {
            return '';
        }

        $objLink = new ModalLink($objModal, $arrConfig);
        $objLink->setJumpTo($jumpTo);
        $objLink->setRowOverride($arrRowOverride);

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
    public static function generateModalUrl(array $arrRow = [], $jumpTo = null, &$blnAjax = true, &$blnRedirect = true)
    {
        global $objPage;
        $strUrl    = '';
        $strParams = ((\Config::get('useAutoItem') && !\Config::get('disableAlias')) ? '/' : '/modals/');
        $strParams .= ((!\Config::get('disableAlias') && $arrRow['alias'] != '') ? $arrRow['alias'] : $arrRow['id']);

        if ($jumpTo !== null && ($objJumpTo = \PageModel::findPublishedByIdOrAlias($jumpTo)) !== null) {
            $objJumpTo = $objJumpTo->current();
            $strUrl    = $objJumpTo->getFrontendUrl($strParams);
        } else {
            if ($objPage !== null) {
                $strUrl = $objPage->getFrontendUrl($strParams);
            }
        }

        if ($blnAjax) {
            // trigger ajax action if jumpto is same page
            if ($objJumpTo === null || $objPage->id == $objJumpTo->id) {
                $blnRedirect = false;
                $strUrl      = AjaxAction::generateUrl(Modal::MODAL_NAME, 'show', [], false, $strUrl);
            } // force redirect to new page and set ajax to false
            else {
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
     * Convert a anchor to a modal link by given configuration
     *
     * @param string $strLink The anchor link as string
     * @param string $strUrl The modal link from ModalController::generateModalUrl()
     * @param array $arrConfig The modal configuration
     * @param boolean $blnRedirect Is redirect to other page
     *
     * @return string The converted modal link or the link if pattern did not match
     */
    public static function convertLinkToModalLink($strLink, $strUrl, array $arrConfig = [], $blnRedirect)
    {
        $strSearch = '/<a(.*?)href="(?P<href>.*?)"(?P<attributes>.*?)>(?P<content>.*)/s';

        preg_match($strSearch, $strLink, $arrMatches);

        if (!isset($arrMatches['href'])) {
            return $strLink;
        }

        $strAttribues  = $arrMatches['attributes'];
        $arrAttributes = [];

        if (is_array($arrConfig['link']['attributes']) && !$blnRedirect) {
            foreach ($arrConfig['link']['attributes'] as $key => $value) {
                $arrAttributes[] = $key . '="' . $value . '"';
            }

            $strAttribues = ($strAttribues ? $strAttribues . ' ' : '') . implode(' ', $arrAttributes);
        }

        $strLink = sprintf(
            '<a href="%s"%s>%s',
            $strUrl,
            $strAttribues,
            $arrMatches['content']
        );


        return $strLink;
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