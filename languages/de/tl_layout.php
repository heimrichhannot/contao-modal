<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrLang = &$GLOBALS['TL_LANG']['tl_layout'];

/**
 * Fields
 */
$arrLang['customModal'] = array('Eigenen Modal-Typ verwenden', 'Überschreiben Sie den Standard-Modal-Typ (Standard: ' . \HeimrichHannot\Modal\ModalController::getDefaultModalType(true) . ')');
$arrLang['modal'] = array('Modal-Typ', 'Geben Sie den Standardtyp für die Darstellung von Modalfenster hier an.');


/**
 * Legends
 */
$arrLang['modal_legend'] = 'Modals';