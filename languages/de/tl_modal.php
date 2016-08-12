<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_modal'];

/**
 * Fields
 */
$arrLang['title'] = array('Titel', 'Geben Sie hier bitte den Titel ein.');
$arrLang['alias'] = array('Modalalias', 'Der Modalalias ist eine eindeutige Referenz, die anstelle der numerischen Modal-ID aufgerufen werden kann.');
$arrLang['headline'] = array('Überschrift', 'Fügen Sie dem Modal eine individuelle Überschrift hinzu, der Seitentitel würd dann überschrieben.');
$arrLang['usePageTitle'] = array('Titel von Seitentitel setzen', 'Lassen Sie den Titel des Modal durch den Seitentitel überschreiben (pageTitle). Überschreibt die Überschrift.');
$arrLang['customHeader'] = array('Kopfzeile überschreiben', 'Überschreiben Sie die Kopfzeile, der Titel wird dann nicht mehr verwendet.');
$arrLang['header'] = array('Kopfzeile', 'Geben Sie den Inhalt der Kopfzeile an.');
$arrLang['addFooter'] = array('Fußzeile hinzufügen', 'Fügen Sie eine individuelle Fußzeile hinzu.');
$arrLang['footer'] = array('Fußzeile', 'Geben Sie den Inhalt der Fußzeile an.');
$arrLang['customModal'] = array('Eigenen Modal-Typ verwenden', 'Überschreiben Sie den Standard-Modal-Typ (Standard: ' . \HeimrichHannot\Modal\ModalController::getDefaultModalType(true) . ')');
$arrLang['modal']       = array('Modal-Typ', 'Geben Sie den Standardtyp für die Darstellung von Modalfenster hier an.');
$arrLang['published'] = array('Veröffentlichen', 'Wählen Sie diese Option zum Veröffentlichen.');
$arrLang['start'] = array('Anzeigen ab', 'Modal erst ab diesem Tag auf der Webseite anzeigen.');
$arrLang['stop'] = array('Anzeigen bis', 'Modal nur bis zu diesem Tag auf der Webseite anzeigen.');
$arrLang['tstamp'] = array('Änderungsdatum', '');


/**
 * Legends
 */
$arrLang['general_legend'] = 'Allgemeine Einstellungen';
$arrLang['header_legend'] = 'Kopfzeile';
$arrLang['footer_legend'] = 'Fußzeile';
$arrLang['expert_legend'] = 'Experten-Einstellungen';
$arrLang['publish_legend'] = 'Veröffentlichung';


/**
 * Buttons
 */
$arrLang['new'] = array('Neues Modal', 'Modal erstellen');
$arrLang['edit'] = array('Modal bearbeiten', 'Modal ID %s bearbeiten');
$arrLang['editmeta'] = array('Die Modaleinstellungen bearbeiten', 'Die Modaleinstellungen bearbeiten');
$arrLang['copy'] = array('Modal duplizieren', 'Modal ID %s duplizieren');
$arrLang['delete'] = array('Modal löschen', 'Modal ID %s löschen');
$arrLang['toggle'] = array('Modal veröffentlichen', 'Modal ID %s veröffentlichen/verstecken');
$arrLang['show'] = array('Modal Details', 'Modal-Details ID %s anzeigen');
