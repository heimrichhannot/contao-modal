<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_page'];

/**
 * Selectors
 */
$arrDca['palettes']['__selector__'][] = 'linkModal';

/**
 * Palettes
 */
$arrDca['palettes']['regular'] = str_replace('protected;', 'protected;{modal_legend},linkModal;', $arrDca['palettes']['regular']);

/**
 * Subpalettes
 */
$arrDca['subpalettes']['linkModal'] = 'modal';


/**
 * Fields
 */
$arrFields = [
    'linkModal' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_page']['linkModal'],
        'exclude'   => true,
        'filter'    => true,
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'modal'     => [
        'label'            => &$GLOBALS['TL_LANG']['tl_page']['modal'],
        'exclude'          => true,
        'search'           => true,
        'inputType'        => 'select',
        'foreignKey'       => 'tl_modal.title',
        'options_callback' => ['HeimrichHannot\Modal\Backend\PageBackend', 'getModalOptions'],
        'eval'             => ['tl_class' => 'w50 clr', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
        'sql'              => "int(10) unsigned NOT NULL default '0'",
        'relation'         => ['type' => 'belongsTo', 'load' => 'lazy'],
    ],
];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);