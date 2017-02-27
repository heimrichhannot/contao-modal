<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_modal'];

/**
 * Fields
 */
$arrLang['title']        = ['Titel', 'Geben Sie hier bitte den Titel ein.'];
$arrLang['alias']        = ['Modalalias', 'Der Modalalias ist eine eindeutige Referenz, die anstelle der numerischen Modal-ID aufgerufen werden kann.'];
$arrLang['headline']     = ['Überschrift', 'Fügen Sie dem Modal eine individuelle Überschrift hinzu, der Seitentitel würd dann überschrieben.'];
$arrLang['usePageTitle'] =
    ['Titel von Seitentitel setzen', 'Lassen Sie den Titel des Modal durch den Seitentitel überschreiben (pageTitle). Überschreibt die Überschrift.'];
$arrLang['customHeader'] = ['Kopfzeile überschreiben', 'Überschreiben Sie die Kopfzeile, der Titel wird dann nicht mehr verwendet.'];
$arrLang['header']       = ['Kopfzeile', 'Geben Sie den Inhalt der Kopfzeile an.'];
$arrLang['addFooter']    = ['Fußzeile hinzufügen', 'Fügen Sie eine individuelle Fußzeile hinzu.'];
$arrLang['footer']       = ['Fußzeile', 'Geben Sie den Inhalt der Fußzeile an.'];
$arrLang['customModal']  = [
    'Eigenen Modal-Typ verwenden',
    'Überschreiben Sie den Standard-Modal-Typ (Standard: ' . \HeimrichHannot\Modal\ModalController::getDefaultModalType(true) . ')',
];
$arrLang['autoItemMode'] = [
    'Nur anzeigen wenn auto_item vorhanden',
    'Aktivieren um das Modul-Fenster nur anzuzeigen, wenn ein zusätzliches auto_item (z.B. Nachrichten-Leser) in der URL vorhanden ist, andernfalls wird eine 404-Meldung erzeugt.',
];
$arrLang['staticBackdrop'] = [
    'Schließen durch Mausklick verhindern',
    'Aktivieren um das Schließen des Modul-Fenster durch einen Mausklick zu unterbinden.'
];
$arrLang['disableKeyboard'] = [
    'Schließen durch Esc-Taste verhindern',
    'Aktivieren um das Schließen des Modul-Fenster durch die Esc-Taste zu unterbinden.'
];
$arrLang['removeCloseButton'] = [
    'Schließen durch "Close" verhindern',
    'Aktivieren um das Schließen des Modul-Fenster durch drücken des "Close"-Buttons  zu unterbinden. (Diese Option entfernt den Button aus dem Template)'
];
$arrLang['modal']        = ['Modal-Typ', 'Geben Sie den Standardtyp für die Darstellung von Modalfenster hier an.'];
$arrLang['published']    = ['Veröffentlichen', 'Wählen Sie diese Option zum Veröffentlichen.'];
$arrLang['start']        = ['Anzeigen ab', 'Modal erst ab diesem Tag auf der Webseite anzeigen.'];
$arrLang['stop']         = ['Anzeigen bis', 'Modal nur bis zu diesem Tag auf der Webseite anzeigen.'];
$arrLang['tstamp']       = ['Änderungsdatum', ''];


/**
 * Legends
 */
$arrLang['general_legend'] = 'Allgemeine Einstellungen';
$arrLang['header_legend']  = 'Kopfzeile';
$arrLang['footer_legend']  = 'Fußzeile';
$arrLang['expert_legend']  = 'Experten-Einstellungen';
$arrLang['publish_legend'] = 'Veröffentlichung';


/**
 * Buttons
 */
$arrLang['new']      = ['Neues Modal', 'Modal erstellen'];
$arrLang['edit']     = ['Modal bearbeiten', 'Modal ID %s bearbeiten'];
$arrLang['editmeta'] = ['Die Modaleinstellungen bearbeiten', 'Die Modaleinstellungen bearbeiten'];
$arrLang['copy']     = ['Modal duplizieren', 'Modal ID %s duplizieren'];
$arrLang['delete']   = ['Modal löschen', 'Modal ID %s löschen'];
$arrLang['toggle']   = ['Modal veröffentlichen', 'Modal ID %s veröffentlichen/verstecken'];
$arrLang['show']     = ['Modal Details', 'Modal-Details ID %s anzeigen'];
