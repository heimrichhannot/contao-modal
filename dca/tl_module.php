<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrDca = &$GLOBALS['TL_DCA']['tl_module'];

$arrDca['config']['onload_callback'][] = ['HeimrichHannot\Modal\Backend\ModuleBackend', 'modifyDca'];

/**
 * Selectors
 */
$arrDca['palettes']['__selector__'][] = 'useModal';

/**
 * Subpalettes
 */
$arrDca['subpalettes']['useModal'] = 'useModalExplanation';

/**
 * Fields
 */
$arrFields = [
    'useModal'            => [
        'label'     => &$GLOBALS['TL_LANG']['tl_module']['useModal'],
        'exclude'   => true,
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
        'sql'       => "char(1) NOT NULL default ''",
    ],
    'useModalExplanation' => [
        'inputType' => 'explanation',
        'eval'      => [
            'text'     => &$GLOBALS['TL_LANG']['tl_module']['useModalExplanation'],
            'class'    => 'tl_info',
            'tl_class' => 'clr long',
        ],
    ],
];

$arrDca['fields'] = array_merge($arrDca['fields'], $arrFields);