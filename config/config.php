<?php

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['content']['modal'] = array(
	'tables' => array('tl_modal_archive', 'tl_modal', 'tl_content'),
	'icon'   => 'system/modules/modal/assets/img/icon_modal.png',
);

/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'modals';
$GLOBALS['TL_PERMISSIONS'][] = 'modalp';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getContentSourceOptions'][]  = array('HeimrichHannot\\Modal\\Backend\\Content', 'addSourceOptions');
$GLOBALS['TL_HOOKS']['generateTeaserLink'][]       = array('HeimrichHannot\\Modal\\Elements\\Teaser', 'generateModalTeaserLink');
$GLOBALS['TL_HOOKS']['generatePage'][]             = array('HeimrichHannot\\Modal\\ModalController', 'generatePageWithModal');
$GLOBALS['TL_HOOKS']['replaceDynamicScriptTags'][] = array('HeimrichHannot\\Modal\\ModalController', 'hookReplaceDynamicScriptTags');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]        = array('HeimrichHannot\\Modal\\ModalController', 'replaceModalInsertTags');

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_modal']         = 'HeimrichHannot\\Modal\\ModalModel';
$GLOBALS['TL_MODELS']['tl_modal_archive'] = 'HeimrichHannot\\Modal\\ModalArchiveModel';


/**
 * Register the auto_item keywords
 */
$GLOBALS['TL_AUTO_ITEM'][] = 'modals';

/**
 * Modal types
 */
$GLOBALS['TL_MODALS']['bs3_default'] = array
(
	'header'   => true,
	'footer'   => true,
	'template' => 'modal_bs3_default',
	'link'     => array(
		'attributes' => array(
			'data-toggle' => 'modal',
		),
	),
	'js'       => array
	(
		'system/modules/modal/assets/js/jquery.modal.bs3.js',
	),
);