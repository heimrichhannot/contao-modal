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


class PageModel extends \PageModel
{
    /**
     * Find pages linked with modal
     *
     * @param array $arrOptions An optional options array
     *
     * @return \Model\Collection|\PageModel[]|\PageModel|null A collection of models or null if there is no matching pages
     */
    public static function findAllPublishedLinkedWithModal(array $arrOptions = array())
    {
        $t          = static::$strTable;
        $arrColumns = array("$t.linkModal = 1 AND $t.modal > 0");

        // Check the publication status (see #4652)
        if (!BE_USER_LOGGED_IN)
        {
            $time         = \Date::floorToMinute();
            $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findBy($arrColumns, null, $arrOptions);
    }

}