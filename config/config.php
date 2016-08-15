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
$GLOBALS['TL_HOOKS']['getContentSourceOptions'][]           = array('HeimrichHannot\Modal\Backend\ContentBackend', 'addSourceOptions');
$GLOBALS['TL_HOOKS']['getDisclaimerSourceOptions'][]        = array('HeimrichHannot\Modal\Backend\DisclaimerBackend', 'addSourceOptions');
$GLOBALS['TL_HOOKS']['showDisclaimer'][]                    = array('HeimrichHannot\Modal\Hooks', 'showDisclaimerHook');
$GLOBALS['TL_HOOKS']['generateTeaserLink'][]                = array('HeimrichHannot\Modal\Elements\Teaser', 'generateModalTeaserLink');
$GLOBALS['TL_HOOKS']['getPageLayout'][]                     = array('HeimrichHannot\Modal\ModalController', 'setModalAutoItem');
$GLOBALS['TL_HOOKS']['generatePage'][]                      = array('HeimrichHannot\Modal\ModalController', 'generatePageWithModal');
$GLOBALS['TL_HOOKS']['replaceDynamicScriptTags'][]          = array('HeimrichHannot\Modal\ModalController', 'hookReplaceDynamicScriptTags');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]                 = array('HeimrichHannot\Modal\ModalController', 'replaceModalInsertTags');
$GLOBALS['TL_HOOKS']['parseArticles']['modalParseArticles'] = array('HeimrichHannot\Modal\Hooks', 'parseArticlesHook');

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_modal']         = 'HeimrichHannot\Modal\ModalModel';
$GLOBALS['TL_MODELS']['tl_modal_archive'] = 'HeimrichHannot\Modal\ModalArchiveModel';


/**
 * Register the auto_item keywords
 */
$GLOBALS['TL_AUTO_ITEM'][] = 'modals';

/**
 * Modal module configuration
 */
array_insert($GLOBALS['MODAL_MODULES'], 1, array
(
	'news_list' => array
	(
		'invokePalette' => 'news_archives'
	)
));


/**
 * Ajax Actions
 */
$GLOBALS['AJAX'][\HeimrichHannot\Modal\Modal::MODAL_NAME] = array
(
	'actions' => array
	(
		'show'     => array
		(
			'arguments' => array(),
			'optional'  => array(),
		),
		'redirect' => array
		(
			'arguments' => array(),
			'optional'  => array(),
		),
	),
);

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

$GLOBALS['TL_MODALS']['bs3_lg'] = array_merge
(
	$GLOBALS['TL_MODALS']['bs3_default'],
	array
	(
		'template' => 'modal_bs3_lg',
	)
);

$GLOBALS['TL_MODALS']['bs3_sm'] = array_merge
(
	$GLOBALS['TL_MODALS']['bs3_default'],
	array
	(
		'template' => 'modal_bs3_sm',
	)
);