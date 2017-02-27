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

class ContentBackend extends \Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
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
        $arrOptions = [];

        $objModal = ModalModel::findAll();

        if ($objModal === null)
        {
            return $arrOptions;
        }

        while ($objModal->next())
        {
            if (($objArchive = $objModal->getRelated('pid')) === null)
            {
                continue;
            }

            $arrOptions[$objArchive->title][$objModal->id] = $objModal->title;
        }

        return $arrOptions;
    }

    /**
     * Add the source options depending on the allowed fields
     *
     * @param array           $arrOptions
     * @param  \DataContainer $dc
     *
     * @return array
     */
    public function addSourceOptions(array $arrOptions = [], \DataContainer $dc)
    {
        if ($this->User->isAdmin)
        {
            $arrOptions[] = 'modal';

            return $arrOptions;
        }

        // Add the "modal" option
        if ($this->User->hasAccess('tl_content::modal', 'alexf'))
        {
            $arrOptions[] = 'modal';
        }

        return $arrOptions;
    }
}