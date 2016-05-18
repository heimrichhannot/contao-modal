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
ClassLoader::addNamespaces(array
(
	'HeimrichHannot',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'HeimrichHannot\Modal\ModalModel'        => 'system/modules/modal/models/ModalModel.php',
	'HeimrichHannot\Modal\ModalArchiveModel' => 'system/modules/modal/models/ModalArchiveModel.php',

	// Classes
	'HeimrichHannot\Modal\Modal'             => 'system/modules/modal/classes/Modal.php',
	'HeimrichHannot\Modal\ModalLink'         => 'system/modules/modal/classes/ModalLink.php',
	'HeimrichHannot\Modal\ModalController'   => 'system/modules/modal/classes/ModalController.php',
	'HeimrichHannot\Modal\Backend\Layout'    => 'system/modules/modal/classes/backend/Layout.php',
	'HeimrichHannot\Modal\Backend\Content'   => 'system/modules/modal/classes/backend/Content.php',
	'HeimrichHannot\Modal\Elements\Teaser'   => 'system/modules/modal/classes/elements/Teaser.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'modal_bs3_default'     => 'system/modules/modal/templates/modals',
	'modallink_bs3_default' => 'system/modules/modal/templates/links',
));
