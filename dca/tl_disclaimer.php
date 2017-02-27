<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

if (!in_array('disclaimer', \ModuleLoader::getActive()))
{
    return;
}

$arrDca = &$GLOBALS['TL_DCA']['tl_disclaimer'];

$arrDca['config']['onload_callback'][] = ['HeimrichHannot\Modal\Backend\DisclaimerBackend', 'modifyPalette'];

/**
 * Subpalettes
 */
$arrDca['subpalettes']['source_modal'] = 'modal,jumpTo';

/**
 * Fields
 */
$arrFields = [
    'modal' => [
        'label'            => &$GLOBALS['TL_LANG']['tl_disclaimer']['modal'],
        'exclude'          => true,
        'search'           => true,
        'inputType'        => 'select',
        'foreignKey'       => 'tl_modal.title',
        'options_callback' => ['HeimrichHannot\Modal\Backend\DisclaimerBackend', 'getModalOptions'],
        'eval'             => ['tl_class' => 'w50 clr', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
        'sql'              => "int(10) unsigned NOT NULL default '0'",
        'relation'         => ['type' => 'belongsTo', 'load' => 'lazy'],
    ],
];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);
