<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$dc = &$GLOBALS['TL_DCA']['tl_layout'];

/**
 * Selectors
 */
$dc['palettes']['__selector__'][] = 'customModal';

/**
 * Palettes
 */
$modalPalette              = '{modal_legend},customModal;';
$dc['palettes']['default'] = str_replace('addJQuery;', 'addJQuery;' . $modalPalette, $dc['palettes']['default']);

/**
 * Subpalettes
 */
$dc['subpalettes']['customModal'] = 'modal';

/**
 * Fields
 */
$arrFields = [
    'customModal' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_layout']['customModal'],
        'exclude'   => true,
        'filter'    => true,
        'inputType' => 'checkbox',
        'eval'      => ['submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'modal'       => [
        'label'            => &$GLOBALS['TL_LANG']['tl_layout']['modal'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => ['HeimrichHannot\Modal\Backend\LayoutBackend', 'getModalOptions'],
        'reference'        => &$GLOBALS['TL_LANG']['modals'],
        'eval'             => ['includeBlankOption' => true, 'mandatory' => true],
        'sql'              => "varchar(64) NOT NULL default ''",
    ],
];

$dc['fields'] = array_merge($dc['fields'], $arrFields);