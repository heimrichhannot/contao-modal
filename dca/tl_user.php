<?php

/**
 * Extend default palette
 */
$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] =
    str_replace('fop;', 'fop;{modal_legend},modals,modalp;', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] =
    str_replace('fop;', 'fop;{modal_legend},modals,modalp;', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);


/**
 * Add fields to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['modals'] = [
    'label'      => &$GLOBALS['TL_LANG']['tl_user']['modals'],
    'exclude'    => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_modal_archive.title',
    'eval'       => ['multiple' => true],
    'sql'        => "blob NULL",
];

$GLOBALS['TL_DCA']['tl_user']['fields']['modalp'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['modalp'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => ['create', 'delete'],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => "blob NULL",
];
