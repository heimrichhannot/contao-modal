<?php

namespace HeimrichHannot\Modal;

class ModalModel extends \Model
{

    protected static $strTable = 'tl_modal';

    /**
     * Find published modal items by their ID or alias for non page linked modals
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrOptions An optional options array
     *
     * @return static The ModalModel or null if there are no modals
     */
    public static function findPublishedByIdOrAliasWithoutLinkedPage($varId, array $arrOptions = array())
    {
        $objLinkedPages = PageModel::findAllPublishedLinkedWithModal();

        if ($objLinkedPages === null)
        {
            return static::findPublishedByIdOrAlias($varId);
        }

        $t = static::$strTable;

        $arrColumns = array("($t.id=? OR $t.alias=?)");

        $arrColumns[] = 'NOT ' . \Database::getInstance()->findInSet("$t.id", $objLinkedPages->fetchEach('modal'));

        if (!BE_USER_LOGGED_IN)
        {
            $time         = \Date::floorToMinute();
            $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
    }

    /**
     * Find published modal items by their ID or alias
     *
     * @param mixed $varId      The numeric ID or alias name
     * @param array $arrOptions An optional options array
     *
     * @return static The ModalModel or null if there are no modals
     */
    public static function findPublishedByIdOrAlias($varId, array $arrOptions = array())
    {
        $t          = static::$strTable;
        $arrColumns = array("($t.id=? OR $t.alias=?)");

        if (!BE_USER_LOGGED_IN)
        {
            $time         = \Date::floorToMinute();
            $arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
        }

        return static::findBy($arrColumns, array((is_numeric($varId) ? $varId : 0), $varId), $arrOptions);
    }
}