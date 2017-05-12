<?php

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['content']['modal'] = [
    'tables' => ['tl_modal_archive', 'tl_modal', 'tl_content'],
    'icon'   => 'system/modules/modal/assets/img/icon_modal.png',
];

/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'modals';
$GLOBALS['TL_PERMISSIONS'][] = 'modalp';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getContentSourceOptions'][]           = ['HeimrichHannot\Modal\Backend\ContentBackend', 'addSourceOptions'];
$GLOBALS['TL_HOOKS']['generateTeaserLink'][]                = ['HeimrichHannot\Modal\Elements\Teaser', 'generateModalTeaserLink'];
$GLOBALS['TL_HOOKS']['getPageLayout'][]                     = ['HeimrichHannot\Modal\ModalController', 'setModalAutoItem'];
$GLOBALS['TL_HOOKS']['generatePage'][]                      = ['HeimrichHannot\Modal\ModalController', 'generatePageWithModal'];
$GLOBALS['TL_HOOKS']['replaceDynamicScriptTags'][]          = ['HeimrichHannot\Modal\ModalController', 'hookReplaceDynamicScriptTags'];
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]                 = ['HeimrichHannot\Modal\ModalController', 'replaceModalInsertTags'];
$GLOBALS['TL_HOOKS']['parseArticles']['modalParseArticles'] = ['HeimrichHannot\Modal\Hooks', 'parseArticlesHook'];
$GLOBALS['TL_HOOKS']['changelanguageNavigation']['modal']   = ['HeimrichHannot\Modal\Hooks', 'changelanguageNavigationHook'];

if (in_array('disclaimer', \ModuleLoader::getActive()))
{
    $GLOBALS['TL_HOOKS']['getDisclaimerSourceOptions'][] = ['HeimrichHannot\Modal\Backend\DisclaimerBackend', 'addSourceOptions'];
    $GLOBALS['TL_HOOKS']['showDisclaimer'][]             = ['HeimrichHannot\Modal\Hooks', 'showDisclaimerHook'];
}

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
array_insert(
    $GLOBALS['MODAL_MODULES'],
    1,
    [
        'news_list' => [
            'invokePalette' => 'news_archives',
        ],
    ]
);


/**
 * Ajax Actions
 */
$GLOBALS['AJAX'][\HeimrichHannot\Modal\Modal::MODAL_NAME] = [
    'actions' => [
        'show'     => [
            'arguments' => [],
            'optional'  => [],
        ],
    ],
];

/**
 * Modal types
 */
$GLOBALS['TL_MODALS']['bs3_default'] = [
    'header'   => true,
    'footer'   => true,
    'template' => 'modal_bs3_default',
    'link'     => [
        'attributes' => [
            'data-toggle' => 'modal',
        ],
    ],
    'js'       => [
        'system/modules/modal/assets/js/jquery.modal.bs3.js',
    ],
];

$GLOBALS['TL_MODALS']['bs3_lg'] = array_merge(
    $GLOBALS['TL_MODALS']['bs3_default'],
    [
        'template' => 'modal_bs3_lg',
    ]
);

$GLOBALS['TL_MODALS']['bs3_sm'] = array_merge(
    $GLOBALS['TL_MODALS']['bs3_default'],
    [
        'template' => 'modal_bs3_sm',
    ]
);