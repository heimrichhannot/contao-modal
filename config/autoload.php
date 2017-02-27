<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(
    [
        'HeimrichHannot',
    ]
);


/**
 * Register the classes
 */
ClassLoader::addClasses(
    [
        // Models
        'HeimrichHannot\Modal\PageModel'                 => 'system/modules/modal/models/PageModel.php',
        'HeimrichHannot\Modal\ModalModel'                => 'system/modules/modal/models/ModalModel.php',
        'HeimrichHannot\Modal\ModalArchiveModel'         => 'system/modules/modal/models/ModalArchiveModel.php',

        // Classes
        'HeimrichHannot\Modal\Modal'                     => 'system/modules/modal/classes/Modal.php',
        'HeimrichHannot\Modal\ModalAjax'                 => 'system/modules/modal/classes/ModalAjax.php',
        'HeimrichHannot\Modal\ModalLink'                 => 'system/modules/modal/classes/ModalLink.php',
        'HeimrichHannot\Modal\ModalController'           => 'system/modules/modal/classes/ModalController.php',
        'HeimrichHannot\Modal\Hooks'                     => 'system/modules/modal/classes/Hooks.php',
        'HeimrichHannot\Modal\Backend\NewsBackend'       => 'system/modules/modal/classes/backend/NewsBackend.php',
        'HeimrichHannot\Modal\Backend\LayoutBackend'     => 'system/modules/modal/classes/backend/LayoutBackend.php',
        'HeimrichHannot\Modal\Backend\ContentBackend'    => 'system/modules/modal/classes/backend/ContentBackend.php',
        'HeimrichHannot\Modal\Backend\ModuleBackend'     => 'system/modules/modal/classes/backend/ModuleBackend.php',
        'HeimrichHannot\Modal\Backend\DisclaimerBackend' => 'system/modules/modal/classes/backend/DisclaimerBackend.php',
        'HeimrichHannot\Modal\Backend\PageBackend'       => 'system/modules/modal/classes/backend/PageBackend.php',
        'HeimrichHannot\Modal\Elements\Teaser'           => 'system/modules/modal/classes/elements/Teaser.php',
    ]
);


/**
 * Register the templates
 */
TemplateLoader::addFiles(
    [
        'modal_bs3_sm'          => 'system/modules/modal/templates/modals',
        'modal_bs3_default'     => 'system/modules/modal/templates/modals',
        'modal_bs3_lg'          => 'system/modules/modal/templates/modals',
        'modallink_bs3_default' => 'system/modules/modal/templates/links',
    ]
);
