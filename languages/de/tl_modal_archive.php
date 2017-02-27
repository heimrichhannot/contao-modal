<?php

$arrLang = &$GLOBALS['TL_LANG']['tl_modal_archive'];

/**
 * Fields
 */
$arrLang['title']       = ['Titel', 'Geben Sie hier bitte den Titel ein.'];
$arrLang['customModal'] = [
    'Eigenen Modal-Typ verwenden',
    'Überschreiben Sie den Standard-Modal-Typ (Standard: ' . \HeimrichHannot\Modal\ModalController::getDefaultModalType(true) . ')',
];
$arrLang['modal']       = ['Modal-Typ', 'Geben Sie den Standardtyp für die Darstellung von Modalfenster hier an.'];
$arrLang['tstamp']      = ['Änderungsdatum', ''];


/**
 * Legends
 */
$arrLang['general_legend'] = 'Allgemeine Einstellungen';
$arrLang['expert_legend']  = 'Experten-Einstellungen';


/**
 * Buttons
 */
$arrLang['new']        = ['Neues Modal-Archiv', 'Modal-Archiv erstellen'];
$arrLang['edit']       = ['Modal-Archiv bearbeiten', 'Modal-Archiv ID %s bearbeiten'];
$arrLang['editheader'] = ['Modal-Archiv-Einstellungen bearbeiten', 'Modal-Archiv-Einstellungen ID %s bearbeiten'];
$arrLang['copy']       = ['Modal-Archiv duplizieren', 'Modal-Archiv ID %s duplizieren'];
$arrLang['delete']     = ['Modal-Archiv löschen', 'Modal-Archiv ID %s löschen'];
$arrLang['show']       = ['Modal-Archiv Details', 'Modal-Archiv-Details ID %s anzeigen'];
