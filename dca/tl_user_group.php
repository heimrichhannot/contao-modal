<?php

/**
 * Extend default palette
 */
$GLOBALS['TL_DCA']['tl_user_group']['palettes']['default'] =
    str_replace('fop;', 'fop;{modal_legend},modals,modalp;', $GLOBALS['TL_DCA']['tl_user_group']['palettes']['default']);


/**
 * Add fields to tl_user_group
 */
$GLOBALS['TL_DCA']['tl_user_group']['fields']['modals'] = array(
    'label'      => &$GLOBALS['TL_LANG']['tl_user']['modals'],
    'exclude'    => true,
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_modal_archive.title',
    'eval'       => array('multiple' => true),
    'sql'        => "blob NULL",
);

$GLOBALS['TL_DCA']['tl_user_group']['fields']['modalp'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['modalp'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'options'   => array('create', 'delete'),
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => array('multiple' => true),
    'sql'       => "blob NULL",
);