<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

$arrLang = &$GLOBALS['TL_LANG']['tl_module'];

/**
 * Fields
 */
$arrLang['useModal'] = ['Elemente im Modalfenstern anzeigen', 'Wählen Sie diese Option, wenn die Elemente im Modalfenstern angezeigt werden sollen.'];

/**
 * Legends
 */
$arrLang['modal_legend'] = 'Modal-Einstellungen';

/**
 * Explanations
 */
$arrLang['useModalExplanation'] =
    'Zum Aufruf der Leser-Elemente (also den Entitäten) ist es nötig, die Weiterleitungsseite mit einem Modal zu verknüpfen. Dazu legen Sie das Modal an, hinterlegen in diesem ein Leser-Modul als Inhaltselement und weisen es über "Modal verknüpfen" an Ihrer Weiterleitungsseite in der Seitenstruktur zu. Wenn Sie keine Weiterleitungsseite auswählen, wird die aktuelle Seite genutzt, auf der sich dieses Modul befindet.<br/><br/>Hinweis: Sollten in Ihrem Modulkontext mehrere Leser-Module eine Rolle spielen (bspw. zum Lesen und Bearbeiten der Entität), nutzen Sie dafür bitte heimrichhannot/contao-blocks.';