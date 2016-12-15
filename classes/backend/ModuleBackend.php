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


class ModuleBackend extends \Backend
{

    public function modifyDca(\DataContainer $dc)
    {
        $arrDca     = &$GLOBALS['TL_DCA']['tl_module'];
        $arrModules = $GLOBALS['MODAL_MODULES'];

        $strSuffix = '{modal_legend},useModal;';

        if (!is_array($arrModules))
        {
            return false;
        }

        foreach ($arrModules as $strModule => $arrConfig)
        {
            if (!is_array($arrConfig))
            {
                continue;
            }

            if ($arrConfig['invokePalette'])
            {
                $arrDca['palettes'][$strModule] =
                    str_replace($arrConfig['invokePalette'], $arrConfig['invokePalette'] . $strSuffix, $arrDca['palettes'][$strModule]);
            }
        }
    }

}